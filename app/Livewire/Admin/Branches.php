<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use Livewire\Component;
use Filament\Tables\Table;
use Livewire\Attributes\Title;
use WireUi\Traits\WireUiActions;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class Branches extends Component  implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use WireUiActions;

    public function table(Table $table): Table
    {
        return $table
            ->query(Branch::query())
            ->columns([
                TextColumn::make('name')
                    ->label('Branch Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Branch Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('services_count')
                    ->label('Services')
                    ->counts('services')
                    ->sortable(),
                TextColumn::make('counters_count')
                    ->label('Counters')
                    ->counts('counters')
                    ->sortable(),
                TextColumn::make('queues_count')
                    ->label('Tickets')
                    ->counts('queues')
                    ->sortable()
            ])
            ->headerActions([
                Action::make('create')
                ->modalWidth('7xl')
                    ->label('Create Branch')
                    ->icon('heroicon-o-plus')

                    ->modalHeading('Create New Branch')
                    ->modalDescription('Add a new branch to the queuing system. Each branch represents a physical location where services are offered.')

                    ->form([
                        Section::make('Branch Information')
                            ->description('Enter the basic details of the branch')

                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Branch Name')
                                    ->placeholder('Enter branch name')
                                    ->helperText('Full name of the branch location')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),

                                TextInput::make('code')
                                    ->label('Branch Code')
                                    ->placeholder('Enter unique code')
                                    ->helperText('Short unique identifier for this branch (e.g. HQ, BR01)')
                                    ->required()
                                    ->maxLength(50)
                                    ->unique('branches', 'code')
                                    ->columnSpan(1),
                            ]),

                        Section::make('Location Details')
                            ->description('Specify where this branch is located')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                TextInput::make('address')
                                    ->label('Branch Address')
                                    ->placeholder('Enter complete address')
                                    ->helperText('Physical location of this branch')
                                    ->maxLength(500),

                                Placeholder::make('note')
                                    ->content('This address will be displayed on tickets and public displays.')
                                    ->extraAttributes(['class' => 'text-sm text-gray-500'])
                            ]),
                    ])
                    ->action(function (array $data) {
                        Branch::create([
                            'name' => $data['name'],
                            'code' => $data['code'],
                            'address' => $data['address'] ?? null,
                        ]);
                        $this->dialog()->success(
                            title: 'Branch Created',
                            description: 'The new branch has been successfully added to the system'
                        );
                    }),
            ])
            ->filters([
                // ...
            ])
            ->actions([

                ActionGroup::make([
                    Action::make('view_settings')
                    ->label('View Settings')
                    ->size('sm')
                    ->color('gray')
                    ->icon('heroicon-o-eye')
                    ->modalWidth('5xl')
                    ->modalHeading(fn (Branch $record) => "Settings for {$record->name} ({$record->code})")
                    ->modalContent(function (Branch $record) {
                        return view('livewire.admin.branch-settings-modal', ['record' => $record]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn ($action) => $action->label('Close')),
                Action::make('manage_settings')
                    ->label('Manage Settings')
                    ->size('sm')
                    ->color('gray')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn (Branch $record): string => route('admin.settings', ['branch' => $record])),

                    EditAction::make('edit')
                    ->color('gray')
                    ->label('Edit')
                    ->modalWidth('7xl')
                    ->modalHeading('Edit Branch')
                    ->modalDescription('Update branch information. Changes will affect all associated services, counters, and queues.')
                    ->successNotification(null)
                    ->after(function () {
                        $this->dialog()->success(
                            title: 'Branch Updated',
                            description: 'Branch information has been successfully updated'
                        );
                    })
                    ->form([
                        Section::make('Branch Information')
                            ->description('Update the basic details of the branch')

                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Branch Name')
                                    ->placeholder('Enter branch name')
                                    ->helperText('Full name of the branch location')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),

                                TextInput::make('code')
                                    ->label('Branch Code')
                                    ->placeholder('Enter unique code')
                                    ->helperText('Short unique identifier for this branch (e.g. HQ, BR01)')
                                    ->required()
                                    ->maxLength(50)
                                    ->unique('branches', 'code', ignoreRecord: true)
                                    ->columnSpan(1),
                            ]),

                        Section::make('Location Details')
                            ->description('Update where this branch is located')

                            ->schema([
                                TextInput::make('address')
                                    ->label('Branch Address')
                                    ->placeholder('Enter complete address')
                                    ->helperText('Physical location of this branch')
                                    ->maxLength(500),

                                Placeholder::make('note')
                                    ->content('This address will be displayed on tickets and public displays.')
                                    ->extraAttributes(['class' => 'text-sm text-gray-500'])
                            ]),


                        ])

                        ,

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Branch')
                    ->modalDescription('Are you sure you want to delete this branch? This action cannot be undone if the branch has no associated records.')
                    ->modalIcon('heroicon-o-exclamation-triangle')
                    ->modalSubmitActionLabel('Yes, Delete Branch')
                    ->action(function (Branch $branch) {
                        // Check if branch has associated records
                        if ($branch->queues()->exists() || $branch->services()->exists() || $branch->counters()->exists()) {
                            $this->dialog()->error(
                                title: 'Cannot Delete Branch',
                                description: 'This branch has associated services, counters, or queues. Please remove those records first.'
                            );
                            return;
                        }

                        $branch->delete();
                        $this->dialog()->success(
                            title: 'Branch Deleted',
                            description: 'The branch has been permanently removed from the system'
                        );
                    }),
                ]),




            ])
            ->bulkActions([
                // ...
            ]);
    }





    public function render()
    {
        return view('livewire.admin.branches');
    }
}
