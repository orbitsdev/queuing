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

    public ?Branch $branch = null;
    public bool $isGlobal = false;
    public array $settings = [];
    public string $settingsTitle = '';
    
    public function mount($branch)
    {
        // Handle 'global' parameter for global settings
        if ($branch === 'global') {
            $this->isGlobal = true;
            $this->settingsTitle = 'Global Default Settings';
        } else {
            $this->branch = $branch;
            $this->settingsTitle = "Settings for {$branch->name} ({$branch->code})";
        }
        
        // Load branch-specific settings or global settings
        $this->loadSettings();
    }

    protected function loadSettings()
    {
        // Define the keys we want to load
        $settingKeys = [
            'ticket_prefix',
            'print_logo',
            'queue_reset_daily',
            'queue_reset_time',
            'default_break_message',
        ];
        
        if ($this->isGlobal) {
            // For global settings, load directly from global settings
            foreach ($settingKeys as $key) {
                $setting = Setting::whereNull('branch_id')
                    ->where('key', $key)
                    ->first();
                
                // Set the value in our settings array if found
                if ($setting) {
                    $this->settings[$key] = $setting->value;
                } else {
                    // Provide sensible defaults if no setting exists at all
                    $this->settings[$key] = match($key) {
                        'ticket_prefix' => 'QUE',
                        'print_logo' => 'true',
                        'queue_reset_daily' => 'true',
                        'queue_reset_time' => '00:00',
                        'default_break_message' => 'On break, please proceed to another counter.',
                        default => ''
                    };
                }
            }
        } else {
            // For branch-specific settings, try to get branch setting or fall back to global default
            foreach ($settingKeys as $key) {
                // Try to get branch-specific setting first
                $setting = Setting::where('branch_id', $this->branch->id)
                    ->where('key', $key)
                    ->first();
                
                // If not found, fall back to global default (branch_id = NULL)
                if (!$setting) {
                    $setting = Setting::whereNull('branch_id')
                        ->where('key', $key)
                        ->first();
                }
                
                // Set the value in our settings array if found
                if ($setting) {
                    $this->settings[$key] = $setting->value;
                } else {
                    // Provide sensible defaults if no setting exists at all
                    $this->settings[$key] = match($key) {
                        'ticket_prefix' => 'QUE',
                        'print_logo' => 'true',
                        'queue_reset_daily' => 'true',
                        'queue_reset_time' => '00:00',
                        'default_break_message' => 'On break, please proceed to another counter.',
                        default => ''
                    };
                }
            }
        }
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
        $confirmMessage = $this->isGlobal 
            ? 'Are you sure you want to save these global default settings?' 
            : 'Are you sure you want to save these settings for ' . $this->branch->name . '?';
            
        $this->dialog()->confirm([
            'title' => $this->isGlobal ? 'Save Global Settings' : 'Save Branch Settings',
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
        // Get branch_id (null for global settings)
        $branchId = $this->isGlobal ? null : $this->branch->id;
        
        // Save each setting
        foreach ($this->settings as $key => $value) {
            Setting::updateOrCreate(
                ['branch_id' => $branchId, 'key' => $key],
                ['value' => $value]
            );
        }
        
        // Show success notification
        $successMessage = $this->isGlobal 
            ? 'Global default settings have been updated successfully.' 
            : 'Branch settings have been updated successfully.';
            
        $this->notification()->success(
            'Settings Saved',
            $successMessage
        );

        $this->redirect(route('admin.branch-settings'));
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
