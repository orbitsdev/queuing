<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\Service;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Livewire\Attributes\Title;
use WireUi\Traits\WireUiActions;
use Filament\Tables\Grouping\Group;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Actions\Concerns\InteractsWithActions;

class Services extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;



    //cratee action
    public function createAction(): Action
    {
        return Action::make('create')
            ->size('xs')
            ->label('Create Service')
            ->button('dark-gray')
            ->icon('heroicon-o-plus')
            ->modalWidth('7xl')
            ->modalHeading('Create New Service')
            ->modalDescription('Add a new service to the system. Services are used to group queues and define the order of processing.')
            ->form([
                Section::make('Service Information')
                    ->description('Enter the basic details of the service')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Service Name')
                            ->placeholder('Enter service name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('code')
                            ->label('Service Code')
                            ->placeholder('Enter service code')
                            ->required()
                       ->unique(Service::class, 'code') // <-- important
                            ->maxLength(10)
                            ->columnSpan(1),
                        Textarea::make('description')
                            ->label('Service Description')
                            ->placeholder('Enter service description')
                            ->rows(3)
                            ->columnSpan(2),
                        // Select::make('branch_id')
                        //     ->label('Branch')
                        //     ->placeholder('Select branch')
                        //     ->options(Branch::all()->pluck('name', 'id'))
                        //     ->required()
                        //     ->columnSpan(2)
                    ]),
            ])
            ->action(function (array $data): void {
                $data['branch_id'] = Auth::user()->branch_id;
                Service::create($data);
                $this->dialog()->success(
                    title: 'Service Created',
                    description: 'Service has been successfully created'
                );
            });
    }

    public function table(Table $table): Table
    {
        return $table
        ->query(Service::query()->currentBranch()->latest())
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(isIndividual:true)
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable(isIndividual:true)
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable(isIndividual:true)
                    ->sortable(),
                // TextColumn::make('branch.name')
                //     ->label('Branch')
                //     ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->date('M d, Y')
                    ->sortable(),
            ])

            ->filters([
                // SelectFilter::make('branch_id')
                //     ->label('Branch')
                //     ->options(fn () => Branch::pluck('name', 'id')->toArray())
                //     ->placeholder('All Branches')
                //     ->indicator('Branch')
            ])
            ->actions([
                EditAction::make()
                    ->successNotification(null)
                    ->label('Edit')
                    ->button('dark-gray')
                    ->icon('heroicon-o-pencil')
                    ->modalWidth('7xl')
                    ->modalHeading('Edit Service')
                    ->modalDescription('Edit the details of the service')
                    ->form([
                        Section::make('Service Information')
                            ->description('Enter the basic details of the service')
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Service Name')
                                    ->placeholder('Enter service name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),
                                TextInput::make('code')
                                    ->label('Service Code')
                                    ->placeholder('Enter service code')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(10)
                                    ->columnSpan(1),
                                Textarea::make('description')
                                    ->label('Service Description')
                                    ->placeholder('Enter service description')
                                    ->rows(3)
                                    ->columnSpan(2),
                                // Select::make('branch_id')
                                //     ->label('Branch')
                                //     ->placeholder('Select branch')
                                //     ->options(Branch::all()->pluck('name', 'id'))
                                //     ->required()
                                //     ->columnSpan(2)
                            ])
                    ])
                    ->after(function () {
                        $this->dialog()->success(
                            title: 'Service Updated',
                            description: 'Service has been successfully updated'
                        );
                    }),

                DeleteAction::make()
                    ->label('Delete')
                    ->button('dark-gray')
                    ->icon('heroicon-o-trash')
                    ->modalWidth('7xl')
                    ->modalHeading('Delete Service')
                    ->modalDescription('Delete the service from the system')
                    ->action(function (Service $record): void {
                        $record->delete();
                       $this->dialog()->success(
                            title: 'Service Deleted',
                            description: 'Service has been successfully deleted'
                        );
                    })
            ]);
    }


    public function render()
    {
        return view('livewire.admin.services');
    }
}
