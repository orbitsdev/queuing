<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Branches</h1>
                <flux:button icon="plus" variant="primary" wire:click="create">
                    Add Branch
                </x-button>
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
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Code</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Services</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Counters</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Today's Queues</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-secondary-800">
                                    @foreach ($branches as $branch)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                                {{ $branch->name }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $branch->code }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $branch->services_count }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $branch->counters_count }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $branch->queues_count }}
                                            </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                <flux:button icon="pencil" variant="primary" size="sm" wire:click="edit({{ $branch->id }})" />
                                                <flux:button icon="trash" variant="danger" size="sm" wire:click="confirmDelete({{ $branch->id }})" />
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
                {{ $branches->links() }}
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <x-modal wire:model.defer="showModal">
        <x-card title="{{ $isEditing ? 'Edit Branch' : 'Create Branch' }}">
            <div class="grid grid-cols-1 gap-4">
                <x-input label="Name" placeholder="Enter branch name" wire:model="name" />

                <x-input label="Code" placeholder="Enter branch code" wire:model="code"
                    helper="A unique identifier for the branch (e.g., MAIN, BR01)" />

                <x-textarea label="Address" placeholder="Enter branch address" wire:model="address" />
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
        <x-card title="Delete Branch">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Are you sure you want to delete this branch? This action cannot be undone if the branch has any associated records.
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
