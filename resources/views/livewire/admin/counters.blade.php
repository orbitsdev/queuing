<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Counters</h1>
                <flux:button icon="plus" variant="primary" wire:click="create">
                    Add Counter
                </x-button>
            </div>

            <!-- Branch Filter -->
            <div class="mt-4">
                <x-select
                    label="Filter by Branch"
                    placeholder="Select a branch"
                    :options="$branches"
                    option-label="name"
                    option-value="id"
                    wire:model.live="selectedBranch"
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
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">Name</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Branch</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Priority</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Break Message</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Total Queues</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-secondary-800">
                                    @foreach ($counters as $counter)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                                {{ $counter->name }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $counter->branch->name }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                <x-button 
                                                    :label="$counter->active ? 'Active' : 'Inactive'"
                                                    :icon="$counter->active ? 'check-circle' : 'x-circle'"
                                                    :positive="$counter->active"
                                                    :negative="!$counter->active"
                                                    wire:click="toggleStatus({{ $counter->id }})"
                                                    size="sm"
                                                />
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                @if($counter->is_priority)
                                                    <x-badge positive label="Priority" />
                                                @else
                                                    <x-badge gray label="Regular" />
                                                @endif
                                            </td>
                                            <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                <x-input 
                                                    placeholder="Set break message" 
                                                    wire:model.blur="break_message" 
                                                    wire:change="updateBreakMessage({{ $counter->id }}, $event.target.value)"
                                                    value="{{ $counter->break_message }}"
                                                />
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $counter->queues_count }}
                                            </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                <div class="flex justify-end gap-2">
                                                    <flux:button variant="ghost" size="sm" wire:click="edit({{ $counter->id }})">
                                                        <flux:icon name="pencil" />
                                                    </flux:button>
                                                    <flux:button icon="trash" variant="danger" size="sm" wire:click="confirmDelete({{ $counter->id }})" />
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
                {{ $counters->links() }}
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <x-modal wire:model.defer="showModal">
        <x-card title="{{ $isEditing ? 'Edit Counter' : 'Create Counter' }}">
            <div class="grid grid-cols-1 gap-4">
                <x-select
                    label="Branch"
                    placeholder="Select branch"
                    :options="$branches"
                    option-label="name"
                    option-value="id"
                    wire:model="branch_id"
                />

                <x-input 
                    label="Name" 
                    placeholder="Enter counter name" 
                    wire:model="name" 
                    helper="Example: Counter 1, Window 2, etc."
                />

                <div class="flex gap-4">
                    <x-toggle 
                        label="Priority Counter" 
                        wire:model="is_priority"
                        md
                    />

                    <x-toggle 
                        label="Active" 
                        wire:model="active"
                        md
                    />
                </div>
                
                <x-textarea 
                    label="Break Message" 
                    placeholder="Enter break message (optional)" 
                    wire:model="break_message" 
                />
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button primary label="Save" wire:click="save" />
                </div>
            </x-slot>
        </x-card>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model.defer="confirmingDeletion">
        <x-card title="Delete Counter">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Are you sure you want to delete this counter? This action cannot be undone if the counter has any queues.
            </p>

            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button negative label="Delete" wire:click="delete" />
                </div>
            </x-slot>
        </x-card>
    </x-modal>
</div>
