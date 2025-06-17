<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Queue Management</h1>

            <!-- Filters -->
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <x-select
                    label="Branch"
                    placeholder="Select branch"
                    :options="$branches"
                    option-label="name"
                    option-value="id"
                    wire:model.live="selectedBranch"
                />

                <x-select
                    label="Service"
                    placeholder="Select service"
                    :options="$services"
                    option-label="name"
                    option-value="id"
                    wire:model.live="selectedService"
                />

                <x-select
                    label="Status"
                    placeholder="Select status"
                    :options="$availableStatuses"
                    wire:model.live="selectedStatus"
                />

                <x-input
                    type="date"
                    label="Date"
                    wire:model.live="selectedDate"
                />
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-secondary-800">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">Ticket</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Branch</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Service</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Counter</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Staff</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Time</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-secondary-800">
                                    @foreach ($queues as $queue)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                                {{ $queue->ticket_number }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $queue->branch->name }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $queue->service->name }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $queue->counter?->name ?? '-' }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                <x-dropdown>
                                                    <x-slot name="trigger">
                                                        <x-badge :color="$this->getStatusBadgeColor($queue->status)" :label="$availableStatuses[$queue->status]" />
                                                    </x-slot>

                                                    @if($queue->status === 'waiting')
                                                        <x-dropdown-item wire:click="updateStatus({{ $queue->id }}, 'called')">
                                                            <x-icon name="phone-arrow-up-right" class="w-5 h-5 mr-2" />
                                                            Call
                                                        </x-dropdown-item>
                                                        <x-dropdown-item wire:click="updateStatus({{ $queue->id }}, 'cancelled')">
                                                            <x-icon name="x" class="w-5 h-5 mr-2" />
                                                            Cancel
                                                        </x-dropdown-item>
                                                    @endif

                                                    @if($queue->status === 'called')
                                                        <x-dropdown-item wire:click="updateStatus({{ $queue->id }}, 'serving')">
                                                            <x-icon name="play" class="w-5 h-5 mr-2" />
                                                            Start Serving
                                                        </x-dropdown-item>
                                                        <x-dropdown-item wire:click="updateStatus({{ $queue->id }}, 'held')">
                                                            <x-icon name="pause" class="w-5 h-5 mr-2" />
                                                            Hold
                                                        </x-dropdown-item>
                                                        <x-dropdown-item wire:click="updateStatus({{ $queue->id }}, 'skipped')">
                                                            <x-icon name="fast-forward" class="w-5 h-5 mr-2" />
                                                            Skip
                                                        </x-dropdown-item>
                                                    @endif

                                                    @if($queue->status === 'serving')
                                                        <x-dropdown-item wire:click="updateStatus({{ $queue->id }}, 'completed')">
                                                            <x-icon name="check" class="w-5 h-5 mr-2" />
                                                            Complete
                                                        </x-dropdown-item>
                                                        <x-dropdown-item wire:click="updateStatus({{ $queue->id }}, 'held')">
                                                            <x-icon name="pause" class="w-5 h-5 mr-2" />
                                                            Hold
                                                        </x-dropdown-item>
                                                    @endif

                                                    @if($queue->status === 'held')
                                                        <x-dropdown-item wire:click="updateStatus({{ $queue->id }}, 'called')">
                                                            <x-icon name="phone-arrow-up-right" class="w-5 h-5 mr-2" />
                                                            Serve
                                                        </x-dropdown-item>
                                                        <x-dropdown-item wire:click="updateStatus({{ $queue->id }}, 'completed')">
                                                            <x-icon name="check" class="w-5 h-5 mr-2" />
                                                            Complete
                                                        </x-dropdown-item>
                                                    @endif

                                                    @if($queue->status === 'skipped')
                                                        <x-dropdown-item wire:click="updateStatus({{ $queue->id }}, 'called')">
                                                            <x-icon name="phone-arrow-up-right" class="w-5 h-5 mr-2" />
                                                            Recall
                                                        </x-dropdown-item>
                                                    @endif
                                                </x-dropdown>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $queue->user?->name ?? '-' }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $queue->created_at->format('H:i:s') }}
                                            </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                <div class="flex justify-end gap-2">
                                                    <flux:button icon="computer-desktop" variant="primary" size="sm"
                                                        wire:click="showReassign({{ $queue->id }})"
                                                        x-tooltip.raw="Reassign Counter" />
                                                    
                                                    @if($queue->status === 'held')
                                                        <flux:button variant="ghost" size="sm"
                                                            wire:click="setHoldReason({{ $queue->id }})"
                                                            x-tooltip.raw="Set Hold Reason">
                                                            <flux:icon name="annotation" />
                                                        </flux:button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $queues->links() }}
            </div>
        </div>
    </div>

    <!-- Reassign Counter Modal -->
    <x-modal wire:model.defer="showReassignModal">
        <x-card title="Reassign Counter">
            <div class="grid grid-cols-1 gap-4">
                <x-select
                    label="Counter"
                    placeholder="Select counter"
                    :options="$counters"
                    option-label="name"
                    option-value="id"
                    wire:model="counter_id"
                />
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button primary label="Reassign" wire:click="reassign" />
                </div>
            </x-slot>
        </x-card>
    </x-modal>

    <!-- Hold Reason Modal -->
    <x-modal wire:model.defer="showModal">
        <x-card title="Set Hold Reason">
            <div class="grid grid-cols-1 gap-4">
                <x-textarea
                    label="Hold Reason"
                    placeholder="Enter reason for holding"
                    wire:model="hold_reason"
                />
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button primary label="Save" wire:click="saveHoldReason" />
                </div>
            </x-slot>
        </x-card>
    </x-modal>
</div>
