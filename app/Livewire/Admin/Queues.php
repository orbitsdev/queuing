<?php

namespace App\Livewire\Admin;

use App\Models\Queue;
use App\Models\Branch;
use App\Models\Counter;
use App\Models\Service;
use Livewire\Component;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use WireUi\Traits\WireUiActions;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Auth;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Actions\Concerns\InteractsWithActions;


class Queues extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;


    #[Title('Queue Management')]

    //create actions
    public function createAction(): Action
    {
        return Action::make('create')
            ->size('xs')
            ->label('Create Queue')
            ->button('dark-gray')
            ->icon('heroicon-o-plus')
            ->modalWidth('7xl')
            ->modalHeading('Create New Queue')
            ->modalDescription('Add a new ticket to the queue system.')
            ->form([
                Section::make('Ticket Information')
                    ->description('Enter the ticket details')
                    ->columns(2)
                    ->schema([

                        Select::make('service_id')
                            ->label('Service')
                            ->placeholder('Select service')
                            ->options(function (callable $get) {
                                return Service::where('branch_id', Auth::user()->branch_id)->pluck('name', 'id');
                            })
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('ticket_number')
                            ->label('Ticket Number')
                            ->placeholder('e.g., A001')
                            ->required()
                            ->maxLength(10)
                            ->columnSpan(1),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'waiting' => 'Waiting',
                                'called' => 'Called',
                                'serving' => 'Serving',
                                'held' => 'Held',
                                'served' => 'Served',
                                'skipped' => 'Skipped',
                                'cancelled' => 'Cancelled',
                                'expired' => 'Expired',
                                'completed' => 'Completed',
                            ])
                            ->default('waiting')
                            ->required()
                            ->columnSpan(1),
                    ]),
            ])

            ->action(function (array $data): void {

                // Generate a number for the ticket
                $data['number'] = Queue::where('branch_id', Auth::user()->branch_id)
                    ->where('service_id', $data['service_id'])
                    ->whereDate('created_at', now()->toDateString())
                    ->count() + 1;

                Queue::create($data);
                $this->dialog()->success(
                    title: 'Ticket Created',
                    description: 'Ticket has been successfully added to the queue'
                );
            });
    }

  public function table(Table $table): Table
    {
       return $table
            ->query(Queue::query()->currentBranch())


            ->filters([

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'waiting' => 'Waiting',
                        'called' => 'Called',
                        'serving' => 'Serving',
                        'held' => 'Held',
                        'served' => 'Served',
                        'skipped' => 'Skipped',
                        'cancelled' => 'Cancelled',
                        'expired' => 'Expired',
                        'completed' => 'Completed',
                    ])
                    ->placeholder('All Status')
                    ->indicator('Status'),
                SelectFilter::make('service_id')
                    ->label('Service')
                    ->options(Service::pluck('name', 'id'))
                    ->placeholder('All Services')
                    ->indicator('Service'),
                SelectFilter::make('counter_id')
                    ->label('Counter')
                    ->options(Counter::pluck('name', 'id'))
                    ->placeholder('All Counters')
                    ->indicator('Counter'),
            ])
            ->columns([
                TextColumn::make('number')
                    ->label('Number')
                    ->sortable()
                    ->searchable(isIndividual: true),
                TextColumn::make('ticket_number')
                    ->label('Ticket')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('counter.name')
                    ->label('Counter')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Staff')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'waiting' => 'gray',
                        'called' => 'warning',
                        'serving' => 'info',
                        'held' => 'danger',
                        'served' => 'success',
                        'skipped' => 'danger',
                        'cancelled' => 'danger',
                        'expired' => 'danger',
                        'completed' => 'success',
                        default => 'gray',
                    })
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->actions([
                TableAction::make('view_details')
                    ->label('View Details')
                    ->size('xs')
                    ->button('dark-gray')
                    ->icon('heroicon-o-eye')
                    ->modalWidth('5xl')
                    ->modalHeading(fn (Queue $queue) => "Queue Details: {$queue->name}")
                    ->modalContent(function (Queue $queue) {
                        return view('livewire.admin.queue-details-modal', compact('queue'));
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn ($action) => $action->label('Close')),
                ActionGroup::make([
                    DeleteAction::make()
                        ->modalWidth('7xl')
                        ->modalHeading('Delete Queue')
                        ->modalDescription('Delete the queue details')
                        ->action(function (Queue $queue): void {
                            $queue->delete();
                            $this->dialog()->success(
                                title: 'Queue Deleted',
                                description: 'Queue has been successfully deleted'
                            );
                        }),
                ]),
            ]);

    }



    public function render()
    {
        return view('livewire.admin.queues');
    }
}
