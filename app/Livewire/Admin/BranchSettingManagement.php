<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use WireUi\Traits\WireUiActions;

class BranchSettingManagement extends Component implements HasForms
{
    use InteractsWithForms;
    use WireUiActions;

    public ?array $data = [];
    public Setting $setting;

    public function mount(): void
    {
        $branch = Auth::user()->branch;
        $this->setting = Setting::forBranch($branch);
        $this->form->fill($this->setting->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Ticket Settings')
                    ->description('Customize how ticket numbers are generated and displayed.')
                    ->schema([
                        TextInput::make('ticket_prefix')
                            ->label('Ticket Prefix')
                            ->hint('Prefix shown before every ticket number (e.g., QUE-001)')
                            ->required(),
                        TextInput::make('queue_number_base')
                            ->label('Starting Queue Number')
                            ->numeric()
                            ->hint('The number where ticketing starts after a reset (e.g., 1)')
                            ->default(1)
                            ->required(),
                    ]),

                \Filament\Forms\Components\Section::make('Queue Reset')
                    ->description('Automatic reset settings for daily operations.')
                    ->schema([
                        Toggle::make('queue_reset_daily')
                            ->label('Reset Queues Daily')
                            ->hint('Enable to reset queue numbers every day automatically.')
                            ->default(true),
                        TimePicker::make('queue_reset_time')
                            ->label('Queue Reset Time')
                            ->hint('Time of day when the system resets ticket numbers. (e.g., 05:00 AM)')
                            ->required(),
                    ]),

                \Filament\Forms\Components\Section::make('Counter Break Message')
                    ->description('Message displayed to clients when a counter is on break.')
                    ->schema([
                        Textarea::make('default_break_message')
                            ->label('Default Break Message')
                            ->hint('This message appears when a counter is on break.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save()
    {
        $this->setting->update($this->form->getState());
        $this->dialog()->success(
            title: 'Settings Updated',
            description: 'Settings have been successfully updated'
        );
    }

    public function render(): View
    {
        return view('livewire.admin.branch-setting-management');
    }
}
