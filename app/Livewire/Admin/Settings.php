<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Title;
use WireUi\Traits\WireUiActions;

#[Title('Settings')]
class Settings extends Component
{
    use WireUiActions;

    public Branch $branch;
    public array $settings = [];
    public string $settingsTitle = '';

    public function mount(Branch $branch)
    {
        $this->branch = $branch;
        $this->settingsTitle = "Settings for {$branch->name} ({$branch->code})";
        
        // Load branch-specific settings
        $this->loadSettings();
    }

    protected function loadSettings()
    {
        // Get branch settings with fallback to global
        $settings = Setting::forBranch($this->branch);
        
        // Copy all settings to our settings array
        $this->settings = [
            'ticket_prefix' => $settings->ticket_prefix ?? 'QUE',
            'print_logo' => $settings->print_logo ? 'true' : 'false',
            'queue_reset_daily' => $settings->queue_reset_daily ? 'true' : 'false',
            'queue_reset_time' => $settings->queue_reset_time ?? '00:00',
            'default_break_message' => $settings->default_break_message ?? 'On break, please proceed to another counter.',
        ];
    }

    public function rules()
    {
        return [
            'settings.ticket_prefix' => 'required|string|min:2|max:5',
            'settings.print_logo' => 'required|in:true,false',
            'settings.queue_reset_daily' => 'required|in:true,false',
            'settings.queue_reset_time' => 'required|string',
            'settings.default_break_message' => 'required|string|min:5',
        ];
    }

    /**
     * Get placeholder text for a setting field
     */
    public function getSettingPlaceholder(string $key): string
    {
        return match($key) {
            'ticket_prefix' => 'QUE',
            'queue_reset_time' => '00:00',
            'default_break_message' => 'On break, please proceed to another counter.',
            default => ''
        };
    }

    /**
     * Get helper text for a setting field
     */
    public function getSettingHelperText(string $key): string
    {
        return match($key) {
            'ticket_prefix' => 'Short prefix for ticket numbers (e.g. QUE001)',
            'print_logo' => 'Whether to print the logo on tickets',
            'queue_reset_daily' => 'Reset queue numbers daily',
            'queue_reset_time' => 'Time to reset queue numbers (24h format)',
            'default_break_message' => 'Message displayed when a counter is on break',
            default => ''
        };
    }

    public function save()
    {
        // Validate settings
        $this->validate($this->rules());

        // Confirm before saving
        $confirmMessage = 'Are you sure you want to save these settings for ' . $this->branch->name . '?';

        $this->dialog()->confirm([
            'title' => 'Save Branch Settings',
            'description' => $confirmMessage,
            'icon' => 'question',
            'accept' => [
                'label' => 'Yes, Save Settings',
                'method' => 'saveConfirmed',
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function saveConfirmed()
    {
        // Convert string boolean values to actual booleans
        $printLogo = $this->settings['print_logo'] === 'true';
        $queueResetDaily = $this->settings['queue_reset_daily'] === 'true';
        
        // Save all settings at once
        Setting::updateOrCreate(
            ['branch_id' => $this->branch->id],
            [
                'ticket_prefix' => $this->settings['ticket_prefix'],
                'print_logo' => $printLogo,
                'queue_reset_daily' => $queueResetDaily,
                'queue_reset_time' => $this->settings['queue_reset_time'],
                'default_break_message' => $this->settings['default_break_message'],
            ]
        );

        // Show success notification
        $this->dialog()->success(
            'Settings Saved',
            'Branch settings have been updated successfully.',
            ['icon' => 'check']
        );

        $this->redirect(route('admin.branch-settings'));
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
