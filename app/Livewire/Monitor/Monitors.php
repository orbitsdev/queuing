<?php

namespace App\Livewire\Monitor;

use Filament\Tables;
use App\Models\Branch;
use App\Models\Monitor;
use App\Models\Service;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Components;
use Livewire\Attributes\Title;
use WireUi\Traits\WireUiActions;
use Filament\Actions\CreateAction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;


class Monitors extends Component implements HasForms, HasTable, HasActions
{
    use WireUiActions;
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    // public Branch $branch;

    #[Title('Monitor Management')]

    public function mount()
    {
        // $this->branch = $branch;
    }

    public function createAction(): CreateAction
    {
        return CreateAction::make()
            ->label('Create Monitor')
            ->model(Monitor::class)
            ->form([
                Section::make('Monitor Details')
                    ->schema([
                        Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Components\TextInput::make('location')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpan(2),
                        Select::make('services')
                            ->relationship(
                                name: 'services',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('branch_id', Auth::user()->branch_id),
                            )
                                ->multiple()
                                ->preload()
                                ->required()
                                ->label('Services')
                                ->helperText('Select one or more services for this monitor')
                                ->columnSpan(2),

                    ])
                    ->columns(2)
            ])
          ->using(function (array $data, string $model): Model {
            $data['branch_id'] = Auth::user()->branch_id;
               return $model::create($data);
    })
            ->after(function (Monitor $record) {
                // Sync the services with pivot data
                if (request()->has('data.services')) {
                    $servicesWithOrder = [];
                    foreach (request()->input('data.services') as $index => $serviceId) {
                        $servicesWithOrder[$serviceId] = ['sort_order' => $index + 1];
                    }
                    $record->services()->sync($servicesWithOrder);
                }

                $this->notification()->success(
                    $title = 'Monitor Created',
                    $description = 'Monitor has been created successfully.'
                );
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Monitor::query()->currentBranch())
            ->columns([
                TextColumn::make('name')
                ->searchable(isIndividual: true)

                    ->url(fn (Monitor $record): string => route('display.show', ['monitor' => $record]))
                    ->openUrlInNewTab(),
                TextColumn::make('location')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('services_count')
                    ->counts('services')
                    ->label('Services Count')
                    ->sortable(),
                TextColumn::make('services.name')
                    ->badge()
                    ->color('primary')
                    ->wrap()
                    ->separator(', ')
                    ->label('Services')
                    ->searchable(isIndividual: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('has_services')
                    ->label('Has Services')
                    ->options([
                        'with' => 'With Services',
                        'without' => 'Without Services',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['value'] === 'with', function ($query) {
                            return $query->has('services');
                        })->when($data['value'] === 'without', function ($query) {
                            return $query->doesntHave('services');
                        });
                    }),
            ])
            ->defaultSort('name', 'asc')
            ->paginated([10, 25, 50, 100])
            ->persistFiltersInSession()
            ->bulkActions([

                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->after(function () {
                            $this->notification()->success(
                                $title = 'Monitors Deleted',
                                $description = 'Selected monitors have been deleted successfully.'
                            );
                        }),

            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('view_display')
                    ->label('View Display')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Monitor $record): string => route('display.show', ['monitor' => $record]))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()
                    ->form([
                        Section::make('Monitor Details')
                            ->schema([
                                Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),
                                Components\TextInput::make('location')
                                    ->maxLength(255)
                                    ->columnSpan(1),
                                Components\Textarea::make('description')
                                    ->maxLength(65535)
                                    ->columnSpan(2),
                                Select::make('services')
                                    ->relationship(
                                        name: 'services',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn (Builder $query) => $query->where('branch_id', Auth::user()->branch_id),
                                    )
                                    ->multiple()
                                    ->preload()
                                    ->required()
                                    ->label('Services')
                                    ->helperText('Select one or more services for this monitor')
                                    ->columnSpan(2),
                            ])
                            ->columns(2)
                    ])
                    ->after(function (Monitor $record) {
                        // Sync the services with pivot data
                        if (request()->has('data.services')) {
                            $servicesWithOrder = [];
                            foreach (request()->input('data.services') as $index => $serviceId) {
                                $servicesWithOrder[$serviceId] = ['sort_order' => $index + 1];
                            }
                            $record->services()->sync($servicesWithOrder);
                        }

                        $this->notification()->success(
                            $title = 'Monitor Updated',
                            $description = 'Monitor has been updated successfully.'
                        );
                    }),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->after(function () {
                        $this->notification()->success(
                            $title = 'Monitor Deleted',
                            $description = 'Monitor has been deleted successfully.'
                        );
                    }),
                    ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.monitor.monitors', [
            'branch' =>Auth::user()->branch,
        ]);
    }
}
