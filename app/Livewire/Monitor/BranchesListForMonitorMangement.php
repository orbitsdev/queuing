<?php

namespace App\Livewire\Monitor;

use App\Models\Branch;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Title;

class BranchesListForMonitorMangement extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    #[Title('Branches List For Monitor Management')]

    public function table(Table $table): Table
    {
        return $table
            ->query(Branch::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('monitors_count')
                    ->counts('monitors')
                    ->label('Monitors')
                    ->sortable(),

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
                Tables\Actions\Action::make('manage_monitors')
                ->label('Manage Monitors')
                ->button()
                ->url(fn (Branch $record): string => route('admin.monitors', ['branch' => $record]))
                ->icon('heroicon-o-tv')
                ->color('primary'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.monitor.branches-list-for-monitor-mangement');
    }
}
