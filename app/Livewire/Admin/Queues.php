<?php

namespace App\Livewire\Admin;

use App\Models\Queue;
use App\Models\Branch;
use App\Models\Counter;
use App\Models\Service;
use Livewire\Component;

use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use WireUi\Traits\WireUiActions;
use Filament\Actions\Action;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\Select;

use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Tables\Table;
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
            ->modalDescription('Add a new queue to the system. Queues are used to group counters and define the order of processing.')
            ->form([
                Section::make('Queue Information')
                    ->description('Enter the basic details of the queue')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Queue Name')
                            ->placeholder('Enter queue name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('code')
                            ->label('Queue Code')
                            ->placeholder('Enter queue code')
                            ->required()
                            ->maxLength(10)
                            ->columnSpan(1),
                        Textarea::make('description')
                            ->label('Queue Description')
                            ->placeholder('Enter queue description')
                            ->rows(3)
                            ->columnSpan(2),
                        Select::make('branch_id')
                            ->label('Branch')
                            ->placeholder('Select branch')
                            ->options(Branch::all()->pluck('name', 'id'))
                            ->required()
                            ->columnSpan(2),
                    ]),
            ])
            ->action(function (array $data): void {
                Queue::create($data);
                $this->dialog()->success(
                    title: 'Queue Created',
                    description: 'Queue has been successfully created'
                );
            });
    }

  public function table(Table $table): Table
    {
       return $table
            ->query(Queue::query())
            ->groups([
                Group::make('branch.name')
                    ->label('Branch')
                    ->getTitleFromRecordUsing(fn (Queue $queue) => $queue->branch?->name ?? 'Unassigned')
                    ->collapsible(),
            ])
            ->defaultGroup('branch.name')
            ->filters([
                SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->options(Branch::pluck('name', 'id'))
                    ->placeholder('All Branches')
                    ->indicator('Branch'),
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
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->date('M d, Y')
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
                        return view('livewire.admin.queue-details-modal', $queue);
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
