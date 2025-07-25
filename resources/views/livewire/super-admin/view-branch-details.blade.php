<div>

    <div x-data="{ activeTab: 'admin' }">
        <div class="border-b border-gray-200">
          <nav aria-label="Tabs" class="flex -mb-px space-x-8">
            <!-- Current: "border-primary-500 text-primary-600", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
            <a href="#"
               x-on:click.prevent="activeTab = 'admin'"
               :class="{ 'border-primary-500 text-primary-600': activeTab === 'admin', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'admin' }"
               class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2">Admin</a>
            <a href="#"
               x-on:click.prevent="activeTab = 'services'"
               :class="{ 'border-primary-500 text-primary-600': activeTab === 'services', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'services' }"
               class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2">Services</a>
            <a href="#"
               x-on:click.prevent="activeTab = 'counter'"
               :class="{ 'border-primary-500 text-primary-600': activeTab === 'counter', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'counter' }"
               class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2">Counter</a>
            <a href="#"
               x-on:click.prevent="activeTab = 'monitor'"
               :class="{ 'border-primary-500 text-primary-600': activeTab === 'monitor', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'monitor' }"
               class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2">Monitor</a>
            <a href="#"
               x-on:click.prevent="activeTab = 'settings'"
               :class="{ 'border-primary-500 text-primary-600': activeTab === 'settings', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'settings' }"
               class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2">Settings</a>
          </nav>
        </div>

        <!-- Tab Content -->
        <div class="mt-2">
          <!-- Admin Tab -->
          <div x-show="activeTab === 'admin'">
            <h3 class="text-lg font-medium text-gray-900">Admin</h3>

            <div class="overflow-hidden mt-4 bg-white shadow sm:rounded-lg">
              @if($record->users->count() > 0)
                <div class="px-4 py-5 sm:px-6">
                  <h3 class="text-base font-semibold leading-6 text-gray-900">Branch Administrators</h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">List of administrators for this branch.</p>
                </div>
                <div class="border-t border-gray-200">
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Email</th>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Role</th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($record->users as $admin)
                          <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $admin->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $admin->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                Admin
                              </span>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              @else
                <div class="py-6 text-center">
                  <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                  <h3 class="mt-2 text-sm font-medium text-gray-900">No administrators</h3>
                  <p class="mt-1 text-sm text-gray-500">This branch doesn't have any administrators assigned yet.</p>
                </div>
              @endif
            </div>
          </div>

          <!-- Services Tab -->
          <div x-show="activeTab === 'services'" x-cloak>
            <h3 class="text-lg font-medium text-gray-900">Services</h3>

            <div class="overflow-hidden mt-4 bg-white shadow sm:rounded-lg">
              @if($record->services->count() > 0)
                <div class="px-4 py-5 sm:px-6">
                  <h3 class="text-base font-semibold leading-6 text-gray-900">Branch Services</h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">Services offered at this branch.</p>
                </div>
                <div class="border-t border-gray-200">
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Code</th>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Counters</th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($record->services as $service)
                          <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $service->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $service->code }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              {{ $service->counters->count() }}
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              @else
                <div class="py-6 text-center">
                  <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <h3 class="mt-2 text-sm font-medium text-gray-900">No services</h3>
                  <p class="mt-1 text-sm text-gray-500">This branch doesn't have any services configured yet.</p>
                </div>
              @endif
            </div>
          </div>

          <!-- Counter Tab -->
          <div x-show="activeTab === 'counter'" x-cloak>
            <h3 class="text-lg font-medium text-gray-900">Counter</h3>

            <div class="overflow-hidden mt-4 bg-white shadow sm:rounded-lg">
              @if($record->counters->count() > 0)
                <div class="px-4 py-5 sm:px-6">
                  <h3 class="text-base font-semibold leading-6 text-gray-900">Branch Counters</h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">Counters available at this branch.</p>
                </div>
                <div class="border-t border-gray-200">
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Number</th>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Services</th>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($record->counters as $counter)
                          <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $counter->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $counter->number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              {{ $counter->services->count() }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              @if($counter->status)
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                  Active
                                </span>
                              @else
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                  Inactive
                                </span>
                              @endif
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              @else
                <div class="py-6 text-center">
                  <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                  <h3 class="mt-2 text-sm font-medium text-gray-900">No counters</h3>
                  <p class="mt-1 text-sm text-gray-500">This branch doesn't have any counters configured yet.</p>
                </div>
              @endif
            </div>
          </div>

          <!-- Monitor Tab -->
          <div x-show="activeTab === 'monitor'" x-cloak>
            <h3 class="text-lg font-medium text-gray-900">Monitor</h3>

            <div class="overflow-hidden mt-4 bg-white shadow sm:rounded-lg">
              @if($record->monitors->count() > 0)
                <div class="px-4 py-5 sm:px-6">
                  <h3 class="text-base font-semibold leading-6 text-gray-900">Branch Monitors</h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">Display monitors at this branch.</p>
                </div>
                <div class="border-t border-gray-200">
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Location</th>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Services</th>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($record->monitors as $monitor)
                          <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $monitor->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $monitor->location }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              {{ $monitor->services->count() }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              @if($monitor->status)
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                  Active
                                </span>
                              @else
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                  Inactive
                                </span>
                              @endif
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              @else
                <div class="py-6 text-center">
                  <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  <h3 class="mt-2 text-sm font-medium text-gray-900">No monitors</h3>
                  <p class="mt-1 text-sm text-gray-500">This branch doesn't have any display monitors configured yet.</p>
                </div>
              @endif
            </div>
          </div>

          <!-- Settings Tab -->
          <div x-show="activeTab === 'settings'" x-cloak>
            <h3 class="text-lg font-medium text-gray-900">Settings</h3>

            <div class="overflow-hidden mt-4 bg-white shadow sm:rounded-lg">
              @if($record->setting)
                <div class="px-4 py-5 sm:px-6">
                  <h3 class="text-base font-semibold leading-6 text-gray-900">Branch Settings</h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">Configuration settings for this branch.</p>
                </div>
                <div class="border-t border-gray-200">
                  <dl>
                    <div class="px-4 py-5 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                      <dt class="text-sm font-medium text-gray-500">Ticket Prefix</dt>
                      <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $record->setting->ticket_prefix }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                      <dt class="text-sm font-medium text-gray-500">Print Logo</dt>
                      <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        @if($record->setting->print_logo)
                          <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">Enabled</span>
                        @else
                          <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">Disabled</span>
                        @endif
                      </dd>
                    </div>
                    <div class="px-4 py-5 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                      <dt class="text-sm font-medium text-gray-500">Queue Reset Daily</dt>
                      <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        @if($record->setting->queue_reset_daily)
                          <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">Enabled</span>
                        @else
                          <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">Disabled</span>
                        @endif
                      </dd>
                    </div>
                    <div class="px-4 py-5 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                      <dt class="text-sm font-medium text-gray-500">Queue Reset Time</dt>
                      <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $record->setting->queue_reset_time }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                      <dt class="text-sm font-medium text-gray-500">Queue Number Base</dt>
                      <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $record->setting->queue_number_base }}</dd>
                    </div>
                    <div class="px-4 py-5 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                      <dt class="text-sm font-medium text-gray-500">Default Break Message</dt>
                      <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $record->setting->default_break_message }}</dd>
                    </div>
                  </dl>
                </div>
              @else
                <div class="py-6 text-center">
                  <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <h3 class="mt-2 text-sm font-medium text-gray-900">No settings</h3>
                  <p class="mt-1 text-sm text-gray-500">This branch doesn't have any settings configured yet.</p>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

</div>
