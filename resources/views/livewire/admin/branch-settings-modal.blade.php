<div class="space-y-6">
    @php
        // Get branch settings with fallback to global
        $branchSettings = \App\Models\Setting::where('branch_id', $record->id)->first();
        $globalSettings = \App\Models\Setting::whereNull('branch_id')->first() ?? new \App\Models\Setting();
        
        // Helper function to check if a setting is using the global default
        function isUsingGlobalDefault($key, $branchSettings) {
            return !$branchSettings || $branchSettings->$key === null;
        }
        
        // Helper function to get setting value with fallback
        function getSettingValue($key, $branchSettings, $globalSettings) {
            $defaults = [
                'ticket_prefix' => 'QUE',
                'print_logo' => true,
                'queue_reset_daily' => true,
                'queue_reset_time' => '00:00',
                'default_break_message' => 'On break, please proceed to another counter.'
            ];
            
            if ($branchSettings && $branchSettings->$key !== null) {
                return $branchSettings->$key;
            } elseif ($globalSettings && $globalSettings->$key !== null) {
                return $globalSettings->$key;
            } else {
                return $defaults[$key];
            }
        }
        
        // Format time to be human readable
        function formatTime($timeString) {
            if (empty($timeString)) return '';
            try {
                return date('h:i A', strtotime($timeString));
            } catch (\Exception $e) {
                return $timeString;
            }
        }
    @endphp

    <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Ticket Settings</h3>
                <p class="mt-1 text-sm text-gray-500">Ticket configuration for {{ $record->name }}</p>
            </div>
            <div class="mt-5 md:col-span-2 md:mt-0">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Ticket Prefix</label>
                        <div class="mt-1 text-sm text-gray-900">
                            <span class="font-semibold">{{ getSettingValue('ticket_prefix', $branchSettings, $globalSettings) }}</span>
                            @if(isUsingGlobalDefault('ticket_prefix', $branchSettings))
                                <span class="ml-2 inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Global Default</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Print Logo</label>
                        <div class="mt-1 text-sm text-gray-900">
                            <span class="font-semibold">{{ getSettingValue('print_logo', $branchSettings, $globalSettings) ? 'Yes' : 'No' }}</span>
                            @if(isUsingGlobalDefault('print_logo', $branchSettings))
                                <span class="ml-2 inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Global Default</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Queue Settings</h3>
                <p class="mt-1 text-sm text-gray-500">Queue management configuration</p>
            </div>
            <div class="mt-5 md:col-span-2 md:mt-0">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Reset Queue Daily</label>
                        <div class="mt-1 text-sm text-gray-900">
                            <span class="font-semibold">{{ getSettingValue('queue_reset_daily', $branchSettings, $globalSettings) ? 'Yes' : 'No' }}</span>
                            @if(isUsingGlobalDefault('queue_reset_daily', $branchSettings))
                                <span class="ml-2 inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Global Default</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Queue Reset Time</label>
                        <div class="mt-1 text-sm text-gray-900">
                            <span class="font-semibold">{{ formatTime(getSettingValue('queue_reset_time', $branchSettings, $globalSettings)) }}</span>
                            @if(isUsingGlobalDefault('queue_reset_time', $branchSettings))
                                <span class="ml-2 inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Global Default</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Counter Settings</h3>
                <p class="mt-1 text-sm text-gray-500">Counter behavior configuration</p>
            </div>
            <div class="mt-5 md:col-span-2 md:mt-0">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                        <label class="block text-sm font-medium text-gray-700">Default Break Message</label>
                        <div class="mt-1 text-sm text-gray-900">
                            <span class="font-semibold">{{ getSettingValue('default_break_message', $branchSettings, $globalSettings) }}</span>
                            @if(isUsingGlobalDefault('default_break_message', $branchSettings))
                                <span class="ml-2 inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Global Default</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end">
        <a href="{{ route('admin.settings', ['branch' => $record]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Manage Settings
        </a>
    </div>
</div>
