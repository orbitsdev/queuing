<div>
    @section('nav-title', 'System Settings')
    <x-admin-layout>
        <div class="max-w-8xl mx-auto">
            <!-- Page Description -->


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
                            <h1 class="text-2xl font-semibold leading-7 text-white">System Settings</h1>
                            <p class="mt-2 text-[15px] leading-6 text-white/90">ConfigureTicket SettingsTicket Settings core system settings including ticket formats, queue behavior, and default messages. These settings affect all branches and services.</p>
                            <div class="mt-4 flex items-center gap-x-3">
                                <svg class="size-4 text-white/90" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-white/90">Changes will affect all branches and services immediately</p>
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
                            <label for="ticket_prefix" class="block text-sm/6 font-medium text-kiosqueeing-text">Ticket Number Format</label>
                            <div class="mt-2">
                                <input type="text"
                                    wire:model="ticket_prefix_style"
                                    id="ticket_prefix"
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-kiosqueeing-text shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-kiosqueeing-primary sm:text-sm/6"
                                    placeholder="e.g., {branch}-{number} or {service}-{number}" />
                            </div>
                            <div class="mt-3 rounded-md bg-gray-50/50 p-3 ring-1 ring-gray-900/5">
                                <p class="text-sm/6 font-medium text-gray-900">Available Placeholders:</p>
                                <ul class="mt-2 space-y-1 text-sm/6 text-gray-600">
                                    <li><code class="rounded bg-gray-100 px-2 py-1 text-kiosqueeing-primary">{branch}</code> - Branch code (e.g., "BR1")</li>
                                    <li><code class="rounded bg-gray-100 px-2 py-1 text-kiosqueeing-primary">{service}</code> - Service code (e.g., "CS")</li>
                                    <li><code class="rounded bg-gray-100 px-2 py-1 text-kiosqueeing-primary">{number}</code> - Queue number (e.g., "001")</li>
                                </ul>
                                <p class="mt-2 text-sm/6 text-gray-500">Example: <code class="rounded bg-gray-100 px-2 py-1 text-gray-600">{branch}-{service}-{number}</code> becomes <span class="font-medium text-gray-900">BR1-CS-001</span></p>
                            </div>
                        </div>

                        <div class="col-span-full">
                            <div class="flex gap-3">
                                <div class="flex h-6 items-center">
                                    <input type="checkbox"
                                        wire:model="print_logo"
                                        id="print_logo"
                                        class="h-4 w-4 rounded border-gray-300 text-kiosqueeing-primary focus:ring-kiosqueeing-primary" />
                                </div>
                                <div class="text-sm/6">
                                    <label for="print_logo" class="font-medium text-kiosqueeing-text">Print Logo on Ticket</label>
                                    <p class="text-gray-500">Include your business logo on printed tickets</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Queue Settings -->
                <div class="border-b border-gray-200 pb-12">
                    <div class="flex items-center gap-x-2">
                        <svg class="size-5 text-kiosqueeing-text" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                        </svg>
                        <h2 class="text-base/7 font-semibold text-kiosqueeing-text">Queue Settings</h2>
                    </div>
                    <p class="mt-1 text-sm/6 text-gray-600">Configure queue behavior and display</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full">
                            <div class="flex gap-3">
                                <div class="flex h-6 items-center">
                                    <input type="checkbox"
                                        wire:model="queue_reset_daily"
                                        id="queue_reset_daily"
                                        class="h-4 w-4 rounded border-gray-300 text-kiosqueeing-primary focus:ring-kiosqueeing-primary" />
                                </div>
                                <div class="text-sm/6">
                                    <label for="queue_reset_daily" class="font-medium text-kiosqueeing-text">Reset Queue Numbers Daily</label>
                                    <p class="text-gray-500">Automatically reset queue numbers at specified time</p>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="reset_time" class="block text-sm/6 font-medium text-kiosqueeing-text">Reset Time</label>
                            <div class="mt-2">
                                <input type="time"
                                    wire:model="queue_reset_time"
                                    id="reset_time"
                                    :disabled="!$queue_reset_daily"
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-kiosqueeing-text shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-kiosqueeing-primary disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 sm:text-sm/6" />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="break_message" class="block text-sm/6 font-medium text-kiosqueeing-text">Default Break Message</label>
                            <div class="mt-2">
                                <textarea
                                    wire:model="default_break_message"
                                    id="break_message"
                                    rows="3"
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-kiosqueeing-text shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-kiosqueeing-primary sm:text-sm/6"
                                    placeholder="Message to display when counter is on break"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                </form>
            </div>
            <div class="mt-8 border-t border-gray-100 pt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-x-3">
                        <svg class="size-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-gray-600">Review changes carefully before saving</p>
                    </div>
                    <div class="flex items-center gap-x-6">
                        <button type="button" class="text-sm/6 font-semibold text-kiosqueeing-text hover:text-gray-500">Cancel</button>
                        <button type="submit" class="inline-flex items-center gap-x-2 rounded-md bg-kiosqueeing-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-kiosqueeing-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-kiosqueeing-primary">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </x-admin-layout>
