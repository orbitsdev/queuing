<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\Counter;
use App\Models\Service;
use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\Attributes\Title;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use WireUi\Traits\WireUiActions;

class TransactionHistories extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use WireUiActions;

    public $branch_id;

    #[Title('Transaction History')]

    public function mount()
    {
        $this->branch_id = Auth::user() ? Auth::user()->branch_id : null;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(TransactionHistory::query()
                ->with(['queue', 'user', 'counter', 'service', 'branch'])
                ->where('branch_id', Auth::user() ? Auth::user()->branch_id : null)
                ->orderBy('transaction_time', 'desc'))
            ->headerActions([
                TableAction::make('export')
                    ->label('Download CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->size(ActionSize::Small)
                    ->action(function (array $data, $livewire) {
                        // Get filtered records
                        $query = $livewire->getFilteredTableQuery();
                        
                        // Prepare CSV data
                        $records = $query->get();
                        $csvData = [];
                        
                        // Add headers
                        $csvData[] = [
                            'Time', 'Ticket Number', 'Raw Number', 'Action', 'Status Change',
                            'Service', 'Counter', 'Staff', 'Branch', 'Details'
                        ];
                        
                        // Add records
                        foreach ($records as $record) {
                            $statusChange = '';
                            if ($record->status_before && $record->status_after) {
                                $statusChange = ucfirst($record->status_before) . ' → ' . ucfirst($record->status_after);
                            }
                            
                            // Format metadata
                            $details = '';
                            if (!empty($record->metadata)) {
                                $data = $record->metadata;
                                if (is_string($data)) {
                                    $decoded = json_decode($data, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $data = $decoded;
                                    }
                                }
                                
                                if (is_array($data)) {
                                    $result = [];
                                    foreach ($data as $key => $value) {
                                        if ($key === 'counter_name') {
                                            $result[] = "Counter: $value";
                                        } elseif ($key === 'hold_reason') {
                                            $result[] = "Reason: $value";
                                        } elseif ($key === 'service_time' && $value) {
                                            $result[] = "Service time: $value min";
                                        } elseif ($key === 'hold_duration' && $value) {
                                            $result[] = "Hold duration: $value min";
                                        } elseif ($key === 'break_message' && $value) {
                                            $result[] = "Break reason: $value";
                                        }
                                    }
                                    $details = implode(', ', $result);
                                }
                            }
                            
                            $csvData[] = [
                                $record->transaction_time->format('M d, Y g:i A'),
                                $record->ticket_number,
                                $record->raw_number ?? '',
                                ucfirst($record->action),
                                $statusChange,
                                $record->service->name ?? '',
                                $record->counter->name ?? '',
                                $record->user->name ?? '',
                                $record->branch->name ?? '',
                                $details
                            ];
                        }
                        
                        // Generate CSV
                        $filename = 'transaction-history-' . now()->format('Y-m-d') . '.csv';
                        $headers = [
                            'Content-Type' => 'text/csv',
                            'Content-Disposition' => "attachment; filename=$filename",
                        ];
                        
                        // Create CSV content
                        $callback = function() use ($csvData) {
                            $file = fopen('php://output', 'w');
                            foreach ($csvData as $row) {
                                fputcsv($file, $row);
                            }
                            fclose($file);
                        };
                        
                        return response()->stream($callback, 200, $headers);
                    })
            ])
            ->actions([
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label('View Details')
                    ->color('info')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn ($action) => $action->label('Close'))
                    ->modalHeading(fn (TransactionHistory $record) => 'Transaction Details: ' . $record->ticket_number)
                    ->modalContent(fn (TransactionHistory $record) => view('livewire.admin.partials.transaction-details', ['transaction' => $record]))
            ])
            ->columns([
                TextColumn::make('transaction_time')
                    ->label('Time')
                    ->dateTime('M d, Y g:i A')
                    ->sortable(),
                TextColumn::make('ticket_number')
                    ->label('Ticket')
                    ->description(fn (TransactionHistory $record): string => $record->raw_number ?? '')
                    ->searchable(),
                TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'serving' => 'success',
                        'served' => 'success',
                        'called' => 'warning',
                        'held' => 'orange',
                        'resumed' => 'info',
                        'skipped' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),
                TextColumn::make('status_change')
                    ->label('Status Change')
                    ->formatStateUsing(function (TransactionHistory $record): string {
                        if (!$record->status_before || !$record->status_after) {
                            return '';
                        }
                        return ucfirst($record->status_before) . ' → ' . ucfirst($record->status_after);
                    }),
                TextColumn::make('service.name')
                    ->label('Service')
                    ->sortable(),
                TextColumn::make('counter.name')
                    ->label('Counter')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Staff')
                    ->sortable(),
                TextColumn::make('metadata')
                    ->label('Details')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) {
                            return '';
                        }

                        // Handle different types of metadata
                        $data = $state;

                        // If it's a string, try to decode it as JSON
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $data = $decoded;
                            } else {
                                return $state; // Return as is if not valid JSON
                            }
                        }

                        // If it's not an array at this point, convert to string and return
                        if (!is_array($data)) {
                            return (string)$data;
                        }

                        $result = [];
                        foreach ($data as $key => $value) {
                            if ($key === 'counter_name') {
                                $result[] = "Counter: $value";
                            } elseif ($key === 'hold_reason') {
                                $result[] = "Reason: $value";
                            } elseif ($key === 'service_time' && $value) {
                                $result[] = "Service time: $value min";
                            } elseif ($key === 'hold_duration' && $value) {
                                $result[] = "Hold duration: $value min";
                            } elseif ($key === 'break_message' && $value) {
                                $result[] = "Break reason: $value";
                            }
                        }

                        return empty($result) ? json_encode($data) : implode(', ', $result);
                    }),
            ])
            ->filters([
                SelectFilter::make('service_id')
                    ->label('Service')
                    ->options(fn () => Service::where('branch_id', Auth::user() ? Auth::user()->branch_id : null)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray())
                    ->placeholder('All Services'),

                SelectFilter::make('counter_id')
                    ->label('Counter')
                    ->options(fn () => Counter::where('branch_id', Auth::user() ? Auth::user()->branch_id : null)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray())
                    ->placeholder('All Counters'),

                SelectFilter::make('user_id')
                    ->label('Staff')
                    ->options(fn () => User::where('branch_id', Auth::user() ? Auth::user()->branch_id : null)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray())
                    ->placeholder('All Staff'),

                SelectFilter::make('action')
                    ->label('Action')
                    ->options([
                        'served' => 'Served',
                        'skipped' => 'Skipped',
                        'cancelled' => 'Cancelled'
                    ])
                    ->placeholder('All Actions'),

                Filter::make('date_range')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->default(now()->format('Y-m-d')),
                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->default(now()->format('Y-m-d')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'] && $data['end_date'],
                                fn (Builder $query, $date): Builder => $query->whereBetween('transaction_time', [
                                    $data['start_date'] . ' 00:00:00',
                                    $data['end_date'] . ' 23:59:59'
                                ]),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['start_date'] ?? null) {
                            $indicators['start_date'] = 'From: ' . $data['start_date'];
                        }

                        if ($data['end_date'] ?? null) {
                            $indicators['end_date'] = 'To: ' . $data['end_date'];
                        }

                        return $indicators;
                    }),
            ]);
    }


    public function render()
    {
        return view('livewire.admin.transaction-histories');
    }
}
