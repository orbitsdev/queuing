<?php

namespace App\Livewire\Admin;

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

class ListSettings extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        return Branch::query();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Branch Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Branch Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('settings_count')
                    ->label('Settings')
                    ->counts('settings'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('manage_settings')
                    ->label('Manage Settings')
                    ->size('sm')
                    ->color('primary')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn (Branch $record): string => route('admin.settings', ['branch' => $record]))
            ]);
    }

    public function render(): View
    {
        return view('livewire.admin.list-settings');
    }
}
