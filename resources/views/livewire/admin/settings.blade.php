<div>
    @section('nav-title', 'Branch Settings')
    <x-admin-layout>
        <div class="max-w-8xl mx-auto">
            <div class="bg-white px-6 py-8 shadow-lg rounded-xl ring-1 ring-gray-900/5 sm:rounded-lg">
                <div class="mb-8 bg-gradient-to-r from-kiosqueeing-primary to-kiosqueeing-info px-6 py-6 shadow-sm ring-1 ring-gray-900/5 sm:rounded-lg">
                    <div class="flex items-start gap-x-4">
                        <div class="shrink-0">
                            <div class="flex size-12 items-center justify-center rounded-lg bg-white/20 ring-1 ring-white/30">
                                <svg class="size-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-x-3">
                                <h1 class="text-2xl font-semibold leading-7 text-white">{{ $branch->name }} Settings</h1>
                                <span class="inline-flex items-center rounded-md bg-white/20 px-2 py-1 text-sm text-white ring-1 ring-white/30">{{ $branch->code }}</span>
                            </div>
                            <p class="mt-2 text-[15px] leading-6 text-white/90">Configure branch-specific settings including ticket prefix, queue behavior, and default messages.</p>
                            <div class="mt-4 flex items-center gap-x-3">
                                <svg class="size-4 text-white/90" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-white/90">Changes will only affect {{ $branch->name }} branch</p>
                            </div>
                        </div>
                    </div>
                </div>
                <form wire:submit.prevent="save" class="space-y-12">
                <!-- Ticket Settings -->
                <div class="border-b border-gray-200 pb-12">
                    <div class="flex items-center gap-x-2">
                        <svg class="size-5 text-kiosqueeing-text" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                        </svg>
                        <h2 class="text-base/7 font-semibold text-kiosqueeing-text">Ticket Settings</h2>
                    </div>
                    <p class="mt-1 text-sm/6 text-gray-600">Configure how tickets are generated and displayed</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="ticket_prefix" class="block text-sm/6 font-medium text-kiosqueeing-text">Ticket Prefix</label>
                            <div class="mt-2">
                                <input type="text"
                                    wire:model="settings.ticket_prefix"
                                    id="ticket_prefix"
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-kiosqueeing-text shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-kiosqueeing-primary sm:text-sm/6"
                                    placeholder="{{ $this->getSettingPlaceholder('ticket_prefix') }}" />
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{ $this->getSettingHelperText('ticket_prefix') }}</p>
                            @error('settings.ticket_prefix') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <label for="print_logo" class="block text-sm/6 font-medium text-kiosqueeing-text">Print Logo on Ticket</label>
                            <div class="mt-2">
                                <select
                                    wire:model="settings.print_logo"
                                    id="print_logo"
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-kiosqueeing-text shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-kiosqueeing-primary sm:text-sm/6"
                                >
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </select>
                            </div>
                            @error('settings.print_logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Queue Settings -->
                <div class="border-b border-gray-200 pb-12">
                    <div class="flex items-center gap-x-2">
                        <svg class="size-5 text-kiosqueeing-text" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                        </svg>
                        <h2 class="text-base/7 font-semibold text-kiosqueeing-text">Queue Settings</h2>
                    </div>
                    <p class="mt-1 text-sm/6 text-gray-600">Configure how queues are managed and reset</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="queue_reset_daily" class="block text-sm/6 font-medium text-kiosqueeing-text">Reset Queue Daily</label>
                            <div class="mt-2">
                                <select
                                    wire:model="settings.queue_reset_daily"
                                    id="queue_reset_daily"
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-kiosqueeing-text shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-kiosqueeing-primary sm:text-sm/6"
                                >
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </select>
                            </div>
                            @error('settings.queue_reset_daily') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <label for="queue_reset_time" class="block text-sm/6 font-medium text-kiosqueeing-text">Queue Reset Time</label>
                            <div class="mt-2">
                                <input
                                    wire:model="settings.queue_reset_time"
                                    type="time"
                                    id="queue_reset_time"
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-kiosqueeing-text shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-kiosqueeing-primary sm:text-sm/6"
                                    placeholder="{{ $this->getSettingPlaceholder('queue_reset_time') }}"
                                >
                                <p class="mt-1 text-sm text-gray-500">Current value: {{ date('h:i A', strtotime($settings['queue_reset_time'])) }}</p>
                            </div>
                            @error('settings.queue_reset_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Counter Settings -->
                <div class="border-b border-gray-200 pb-12">
                    <div class="flex items-center gap-x-2">
                        <svg class="size-5 text-kiosqueeing-text" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                        <h2 class="text-base/7 font-semibold text-kiosqueeing-text">Counter Settings</h2>
                    </div>
                    <p class="mt-1 text-sm/6 text-gray-600">Configure counter behavior and messages</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="default_break_message" class="block text-sm/6 font-medium text-kiosqueeing-text">Default Break Message</label>
                            <div class="mt-2">
                                <textarea
                                    wire:model="settings.default_break_message"
                                    id="default_break_message"
                                    rows="3"
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-kiosqueeing-text shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-kiosqueeing-primary sm:text-sm/6"
                                    placeholder="{{ $this->getSettingPlaceholder('default_break_message') }}"></textarea>
                            </div>
                            @error('settings.default_break_message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <div class="flex justify-end gap-x-3">
                        <a href="{{ route('admin.branches') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </a>
                        <button wire:click="save" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span wire:loading.remove wire:target="save">Save Branch Settings</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </x-admin-layout>
</div>
