<?php

namespace App\Livewire\SuperAdmin;

use Filament\Tables;
use App\Models\Branch;
use Livewire\Component;
use Filament\Tables\Table;

use WireUi\Traits\WireUiActions;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ManageBranch extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use WireUiActions;

    public function table(Table $table): Table
    {
        return $table
            ->query(Branch::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admin_count'),
                Tables\Columns\ToggleColumn::make('is_active')
                ->label('Enable/Disable')
                ->afterStateUpdated(function ($record, $state) {

                    //add wire use dialog
                    $this->dialog()->success(
                        title: 'Branch Updated',
                        description: 'Branch information has been successfully updated'
                    );
                    $record->update(['is_active' => $state]);
                })
                ,

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Action::make('view_admin')
                // ->url(function (Branch $branch): string {
                //     return route('superadmin.branch-details', ['branch' => $branch]);
                // })
                // ->button()
                // ->openUrlInNewTab()
                // ->label('Branch Details')
                // ->icon('heroicon-o-eye'),

                Action::make('view_branch_details')
                    ->label('View ')
                    ->size('sm')
                    ->color('gray')
                    ->icon('heroicon-o-eye')
                    ->modalWidth('7xl')
                    ->modalHeading(fn (Branch $record) => "Branch Details for {$record->name} ({$record->code})")
                    ->modalContent(function (Branch $record) {
                        $record->load([
                        'users'=>function($query){
                            $query->where('role','admin');
                        },
                        'setting',
                        'counters',
                        'services',
                        'monitors'
                    ]);
                        return view('livewire.super-admin.view-branch-details', ['record' => $record]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn ($action) => $action->label('Close')),

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
                ActionGroup::make([

                    //actions routes



                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.super-admin.manage-branch');
    }
}
