<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\Counter;
use Filament\Forms\Get;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Actions\Action;
use WireUi\Traits\WireUiActions;
use Filament\Actions\CreateAction;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;

class Counters extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;

    //create Action

    public function createAction(): CreateAction
    {
        return CreateAction::make('create')
            ->model(Counter::class)
            ->size('xs')
            ->label('Create Counter')
            ->button('dark-gray')
            ->icon('heroicon-o-plus')
            ->modalWidth('7xl')
            ->modalHeading('Create New Counter')
            ->modalDescription('Add a new counter to the system. Counters are used to group queues and define the order of processing.')
            ->form([
                Section::make('Basic Information')
                    ->description('Enter the counter identification details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Counter Name')
                            ->placeholder('e.g., Counter 1, Window 2')
                            ->helperText('Name should be clear and easily identifiable')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan(1),

                    ]),
                Section::make('Allowed Services')
                    ->description('Assign which services this counter can handle')
                    ->columns(2)
                    ->schema([
                        Select::make('services')
                        ->relationship(
                            name: 'services',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn (Builder $query) => $query->currentBranch(),
                        )
                            ->multiple()
                            ->preload()
                            ->required()
                            ->label('Services')
                            ->helperText('Select one or more services this counter can handle')
                            ->columnSpan(2),
                    ]),
                Section::make('Status Settings')
                    ->description('Configure counter status and priority settings')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_priority')
                            ->label('Priority Counter')
                            ->helperText('Priority counters handle special or urgent cases')
                            ->default(false)
                            ->inline()
                            ->columnSpan(1),
                        Toggle::make('active')
                            ->live()
                            ->label('Active Status')
                            ->helperText('Inactive counters will not accept new queues')
                            ->default(true)
                            ->inline()
                            ->columnSpan(1),
                        Textarea::make('break_message')
                            ->visible(fn (Get $get) => $get('active') === false)
                            ->label('Break Message')
                            ->placeholder('e.g., On lunch break, Back at 2:00 PM')
                            ->helperText('Message to display when counter is inactive')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpan(2),
                    ]),
            ])
              ->using(function (array $data, string $model): Model {
                  $data['branch_id'] =  Auth::user()->branch_id ?? null;
                      return $model::create($data);
    })
            ->after(function (Counter $record) {
                $this->dialog()->success(
                    title: 'Counter Created',
                    description: "Counter '{$record->name}' has been successfully created!"
                );
            });
    }

    //table

    public function table(Table $table): Table
    {
        return $table
            ->query(Counter::query())

            ->columns([
                ToggleColumn::make('active')
->label('Active/Inactive'),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                // TextColumn::make('branch.name')
                //     ->label('Branch')
                //     ->searchable(isIndividual: true)
                //     ->sortable(),
                // TextColumn::make('is_priority')
                //     ->label('Priority')
                //     ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No')
                //     ->sortable(),
                    //Toggle Column



                TextColumn::make('services.name')
                    ->badge()
                    ->wrap()
                    ->separator(',')
                    ->label('Services')
                    ->searchable(isIndividual: true),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->filters([

            ])
            ->actions([
                // ViewAction::make()
                //     ->button('dark-gray')
                //     ->icon('heroicon-o-eye'),
                EditAction::make()
                    ->button('dark-gray')
                    ->icon('heroicon-o-pencil')
                    ->form([
                        Section::make('Basic Information')
                            ->description('Enter the counter identification details')
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Counter Name')
                                    ->placeholder('e.g., Counter 1, Window 2')
                                    ->helperText('Name should be clear and easily identifiable')
                                    ->required()
                                    ->minLength(3)
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->columnSpan(1),

                            ]),
                        Section::make('Allowed Services')
                            ->description('Assign which services this counter can handle')
                            ->columns(2)
                            ->schema([
                                Select::make('services')
                                ->relationship(
                                    name: 'services',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn (Builder $query) => $query->currentBranch(),
                                )
                                    ->multiple()
                                    ->preload()
                                    ->required()
                                    ->label('Services')
                                    ->helperText('Select one or more services this counter can handle')
                                    ->columnSpan(2),
                            ]),
                        Section::make('Status Settings')
                            ->description('Configure counter status and priority settings')
                            ->columns(2)
                            ->schema([

                                Toggle::make('is_priority')
                                    ->label('Priority Counter')
                                    ->helperText('Priority counters handle special or urgent cases')
                                    ->default(false)
                                    ->inline()
                                    ->columnSpan(1),
                                Toggle::make('active')
                                    ->live()
                                    ->label('Active Status')
                                    ->helperText('Inactive counters will not accept new queues')
                                    ->default(true)
                                    ->inline()
                                    ->columnSpan(1),
                                Textarea::make('break_message')
                                    ->visible(function (Get $get) {
                                        return $get('active') == false;
                                    })
                                    ->label('Break Message')
                                    ->placeholder('e.g., On lunch break, Back at 2:00 PM')
                                    ->helperText('Message to display when counter is inactive')
                                    ->rows(2)
                                    ->maxLength(500)
                                    ->columnSpan(2)
                            ])


                    ])
                    ->after(function () {
                        $this->dialog()->success(
                            title: 'Counter Updated',
                            description: 'Counter has been successfully updated'
                        );
                    }),
                DeleteAction::make()
                    ->button('dark-gray')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Counter')
                    ->modalDescription('Are you sure you want to delete this counter? This action cannot be undone.')
                    ->after(function () {
                        $this->dialog()->success(
                            title: 'Counter Deleted',
                            description: 'Counter has been successfully deleted'
                        );
                    })
            ]);
    }

    public function render()
    {
        return view('livewire.admin.counters');
    }
}
