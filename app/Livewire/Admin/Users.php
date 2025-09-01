<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Branch;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Livewire\Attributes\Title;
use WireUi\Traits\WireUiActions;
use Filament\Tables\Grouping\Group;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Validation\Rules\Password;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Actions\Concerns\InteractsWithActions;

class Users extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;

    #[Title('User Management')]

    // filament actions
    public function createAction(): Action
    {
        return Action::make('create')
        ->size('xs')

        ->label('Create User')
        ->button('dark-gray')
        ->icon('heroicon-o-plus')
        ->modalWidth('7xl')
        ->modalHeading('Create New User')
        ->modalDescription('Add a new user to the system. Users can be assigned to branches and given different roles.')
        ->form([
            Section::make('User Information')
                ->description('Enter the basic details of the user')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Full Name')
                        ->placeholder('Enter user\'s full name')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(1),

                    TextInput::make('email')
                        ->label('Email Address')
                        ->placeholder('Enter email address')
                        ->email()
                        ->required()
                        ->unique('users', 'email')
                        ->maxLength(255)
                        ->columnSpan(1),
                ]),

            Section::make('Access Details')
                ->description('Set user role and branch assignment')
                ->columns(2)
                ->schema([
                    // Select::make('branch_id')
                    //     ->label('Branch')
                    //     ->options(Branch::pluck('name', 'id'))
                    //     ->required()
                    //     ->placeholder('Select a branch')
                    //     ->columnSpan(1),

                    Select::make('role')
                        ->label('Role')
                        ->options([
                            // 'superadmin' => 'Super Admin',
                            'admin' => 'Branch Admin',
                            'staff' => 'Staff',
                        ])
                        ->required()
                        ->placeholder('Select a role')
                        ->columnSpan(1),
                ]),

            Section::make('Password')
                ->description('Set the initial password for this user')
                ->columns(2)
                ->schema([
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required()
                                                ->revealable()
                        ->rule(Password::defaults())
                        ->autocomplete('new-password')
                        ->columnSpan(1),

                    TextInput::make('password_confirmation')
                        ->label('Confirm Password')
                        ->password()
                        ->revealable()
                        ->required()
                        ->same('password')
                        ->autocomplete('new-password')
                        ->columnSpan(1),
                ]),
        ])
        ->action(function (array $data) {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'branch_id' => Auth::user()->branch_id,
                'role' => $data['role'],
                'password' => Hash::make($data['password']),
            ]);

            $this->dialog()->success(
                title: 'User Created',
                description: 'The new user has been successfully added to the system'
            );
        });
    }


    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->notDefaultAdmin()->currentBranch()->notEqualCurrentAuthAdminUser()->where('role', '!=', 'superadmin'))
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(isIndividual:true)
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                ->searchable(isIndividual:true)
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'superadmin' => 'danger',
                        'admin' => 'warning',
                        'staff' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('queues_count')
                    ->label('Tickets Processed')
                    ->counts('queues')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->date('M d, Y')
                    ->sortable(),
            ])

            ->headerActions([

            ])
                // Group users by role
                ->groups([
                       Group::make('role')
                        ->label('Role')
                        ->getTitleFromRecordUsing(fn ($record) => ucfirst($record->role))
                        ->collapsible()
                ])
                ->defaultGroup('role')
                ->filters([
                    SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'staff' => 'Staff',
                    ])
                    ->placeholder('All Roles')
                    ->indicator('Role'),

                // SelectFilter::make('branch_id')
                //     ->label('Branch')
                //     ->options(fn () => Branch::pluck('name', 'id')->toArray())
                //     ->placeholder('All Branches')
                //     ->indicator('Branch')
            ])
            ->actions([
              //filament table action group

              TableAction::make('view_details')
                ->label('View Details')
                ->size('xs')
                ->button('dark-gray')
                ->icon('heroicon-o-eye')
                ->modalWidth('5xl')
                ->modalHeading(fn (User $record): string => "User Details: {$record->name}")
                ->modalContent(function (User $record) {
                    // Get total tickets processed
                    $ticketsProcessed = $record->queues()->count();

                    // Get tickets processed today
                    $ticketsToday = $record->queues()
                        ->whereDate('created_at', now()->toDateString())
                        ->count();

                    // Calculate average processing time (in minutes)
                    // Based on the queues that have been completed
                    $completedQueues = $record->queues()
                        ->where('status', 'completed')
                        ->get();

                    $averageProcessingTime = 'N/A';
                    if ($completedQueues->count() > 0) {
                        $totalMinutes = 0;
                        $count = 0;

                        foreach ($completedQueues as $queue) {
                            // Using created_at and updated_at as approximation
                            // since we don't have specific timestamp fields
                            $startTime = $queue->created_at;
                            $endTime = $queue->updated_at;

                            if ($startTime && $endTime) {
                                $minutes = $startTime->diffInMinutes($endTime);
                                $totalMinutes += $minutes;
                                $count++;
                            }
                        }

                        if ($count > 0) {
                            $avg = $totalMinutes / $count;
                            $averageProcessingTime = number_format($avg, 1) . ' min';
                        }
                    }

                    // Get recent queues
                    $recentQueues = $record->queues()
                        ->with(['service'])
                        ->latest()
                        ->take(5)
                        ->get();

                    return view('livewire.admin.user-details-modal', [
                        'user' => $record,
                        'ticketsProcessed' => $ticketsProcessed,
                        'ticketsToday' => $ticketsToday,
                        'averageProcessingTime' => $averageProcessingTime,
                        'recentQueues' => $recentQueues,
                    ]);
                })
                ->modalSubmitAction(false)
                ->modalCancelAction(fn ($action) => $action->label('Close')),
              ActionGroup::make([

                EditAction::make('edit')
                ->size('xs')
                ->label('Edit')
                ->modalWidth('7xl')
                ->modalHeading('Edit User')
                ->modalDescription('Update user information and access settings.')
                ->successNotification(null)
                ->after(function () {
                    $this->dialog()->success(
                        title: 'User Updated',
                        description: 'User information has been successfully updated'
                    );
                })
                ->form(function (User $record) {
                    return [
                        Section::make('User Information')
                            ->description('Update the basic details of the user')
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Full Name')
                                    ->placeholder('Enter user\'s full name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),

                                TextInput::make('email')
                                    ->label('Email Address')
                                    ->placeholder('Enter email address')
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email', ignoreRecord: true)
                                    ->maxLength(255)
                                    ->columnSpan(1),
                            ]),

                        Section::make('Access Details')
                            ->description('Update user role and branch assignment')
                            ->columns(2)
                            ->schema([
                                // Select::make('branch_id')
                                //     ->label('Branch')
                                //     ->options(Branch::pluck('name', 'id'))
                                //     ->required()
                                //     ->placeholder('Select a branch')
                                //     ->columnSpan(1),

                                Select::make('role')
                                    ->label('Role')
                                    ->options([
                                        // 'superadmin' => 'Super Admin',
                                        'admin' => 'Admin',
                                        'staff' => 'Staff',
                                    ])
                                    ->required()
                                    ->placeholder('Select a role')
                                    ->columnSpan(1),
                            ]),
                    ];
                }),



            Action::make('reset_password')
            ->size('xs')
                ->label('Reset Password')
                ->icon('heroicon-o-key')
                ->color('gray')
                ->modalWidth('md')
                ->modalHeading('Reset User Password')
                ->modalDescription('Set a new password for this user.')
                ->form([
                    Section::make('New Password')
                        ->description('Enter the new password for this user')
                        ->columns(2)
                        ->schema([
                            TextInput::make('password')
                                ->label('New Password')
                                ->password()
                                ->required()
                                ->rule(Password::defaults())
                                ->autocomplete('new-password')
                                ->columnSpan(1),

                            TextInput::make('password_confirmation')
                                ->label('Confirm Password')
                                ->password()
                                ->required()
                                ->same('password')
                                ->autocomplete('new-password')
                                ->columnSpan(1),
                        ]),
                ])
                ->action(function (array $data, User $record) {
                    $record->update([
                        'password' => Hash::make($data['password']),
                    ]);

                    $this->dialog()->success(
                        title: 'Password Reset',
                        description: 'The user\'s password has been successfully reset'
                    );
                }),
                //Delete Action
               DeleteAction::make()
                   ->visible(function (User $record) {
                       // Only visible if not superadmin and doesn't have queues or branch
                       return $record->role !== 'superadmin' &&
                              !$record->queues()->exists() &&
                              $record->id !== auth()->guard()->id();
                   })
                   ->requiresConfirmation()
                   ->modalHeading('Delete User')
                   ->modalDescription('Are you sure you want to delete this user? This action cannot be undone.')
                   ->modalIcon('heroicon-o-exclamation-triangle')
                   ->modalSubmitActionLabel('Yes, Delete User')
                   ->successNotification(
                       Notification::make()
                           ->title('User Deleted')
                           ->body('The user has been permanently removed from the system')
                           ->success()
                   )
              ])

                ])
            ->bulkActions([
                // Bulk actions can be added here if needed
            ]);
    }


    public function render()
    {
        return view('livewire.admin.users');
    }
}
