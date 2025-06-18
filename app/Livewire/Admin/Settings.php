<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Settings extends Component
{
    use WireUiActions;

    public $ticket_prefix_style;
    public $print_logo = false;
    public $queue_reset_daily = false;
    public $queue_reset_time;
    public $default_break_message;

    public function mount(): void
    {
        // Load all settings
        $settings = Setting::all()->pluck('value', 'key');

        $this->ticket_prefix_style = $settings['ticket_prefix_style'] ?? '{branch}-{number}';
        $this->print_logo = filter_var($settings['print_logo'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $this->queue_reset_daily = filter_var($settings['queue_reset_daily'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $this->queue_reset_time = $settings['queue_reset_time'] ?? '00:00';
        $this->default_break_message = $settings['default_break_message'] ?? 'On break, please proceed to another counter.';
    }

    public function save(): void
    {
        if (!$this->dialog()->confirm([
            'title' => 'Save Settings',
            'description' => 'Are you sure you want to update the system settings? This will affect all branches and services.',
            'icon' => 'question',
            'accept' => [
                'label' => 'Yes, Save Changes',
                'method' => 'saveConfirmed',
            ],
            'reject' => [
                'label' => 'No, Cancel',
            ],
        ])) return;
        $this->validate([
            'ticket_prefix_style' => 'required|max:50',
            'queue_reset_time' => 'required|date_format:H:i',
            'default_break_message' => 'required|max:255',
        ]);

        $settings = [
            'ticket_prefix_style' => $this->ticket_prefix_style,
            'print_logo' => $this->print_logo ? 'true' : 'false',
            'queue_reset_daily' => $this->queue_reset_daily ? 'true' : 'false',
            'queue_reset_time' => $this->queue_reset_time,
            'default_break_message' => $this->default_break_message,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->dialog()->success(
            title: 'Settings Updated',
            description: 'System settings have been successfully updated'
        );
    }

    public function saveConfirmed(): void
    {
        $this->save();
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
