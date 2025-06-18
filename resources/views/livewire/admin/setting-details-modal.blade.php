<div class="space-y-6">
    <div class="grid grid-cols-2 gap-6">
        <div class="space-y-2">
            <div class="text-sm font-medium text-gray-500">Setting Key</div>
            <div class="text-base font-semibold text-gray-900">
                @php
                    $label = match($record->key) {
                        'ticket_prefix' => 'Ticket Prefix',
                        'print_logo' => 'Print Logo on Ticket',
                        'queue_reset_daily' => 'Reset Queue Daily',
                        'queue_reset_time' => 'Queue Reset Time',
                        'default_break_message' => 'Default Break Message',
                        default => $record->key
                    };
                @endphp
                {{ $label }}
            </div>
        </div>
        <div class="space-y-2">
            <div class="text-sm font-medium text-gray-500">Current Value</div>
            <div class="text-base font-semibold text-gray-900">
                @if($record->key === 'print_logo' || $record->key === 'queue_reset_daily')
                    {{ $record->value === 'true' ? 'Yes' : 'No' }}
                @else
                    {{ $record->value }}
                @endif
            </div>
        </div>
        <div class="space-y-2">
            <div class="text-sm font-medium text-gray-500">Branch</div>
            <div class="text-base font-semibold text-gray-900">
                @if($record->branch_id)
                    {{ $record->branch->name }} ({{ $record->branch->code }})
                @else
                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Global Default</span>
                @endif
            </div>
        </div>
        <div class="space-y-2">
            <div class="text-sm font-medium text-gray-500">Last Updated</div>
            <div class="text-base font-semibold text-gray-900">{{ $record->updated_at->format('M d, Y H:i:s') }}</div>
        </div>
        <div class="col-span-2 space-y-2">
            <div class="text-sm font-medium text-gray-500">Description</div>
            <div class="text-base font-semibold text-gray-900">
                @php
                    $description = match($record->key) {
                        'ticket_prefix' => 'Prefix for ticket numbers (e.g., QUE001)',
                        'print_logo' => 'Whether to print the branch logo on tickets',
                        'queue_reset_daily' => 'Automatically reset queue numbers daily',
                        'queue_reset_time' => 'Time of day to reset queue numbers (24-hour format)',
                        'default_break_message' => 'Default message shown when a counter goes on break',
                        default => 'System setting for branch configuration'
                    };
                @endphp
                {{ $description }}
            </div>
        </div>
    </div>
</div>
