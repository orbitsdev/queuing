<x-admin-layout>

<div>
  <h3 class="text-base font-semibold text-gray-900">System Statistics</h3>

  <dl class="grid grid-cols-1 gap-5 mt-5 sm:grid-cols-4 lg:grid-cols-4">
    <!-- Total Services -->
    <div class="overflow-hidden relative px-4 pt-5 pb-12 bg-gradient-to-br rounded-lg shadow sm:px-6 sm:pt-6 from-denim-600 to-denim-800">
      <dt>
        <div class="absolute p-3 rounded-md backdrop-blur-sm bg-white/20">
          <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
            <path d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672ZM12 2.25V4.5m5.834.166-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243-1.59-1.59" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>
        <p class="ml-16 text-sm font-medium text-white truncate">Total Services</p>
      </dt>
      <dd class="flex items-baseline pb-6 ml-16 sm:pb-7">
        <p class="text-2xl font-semibold text-white">{{ $totalServices }}</p>
        <div class="absolute inset-x-0 bottom-0 px-4 py-4 backdrop-blur-sm bg-white/10 sm:px-6">
          <div class="text-sm">
            <a href="#" class="font-medium text-white hover:text-gray-100">View all<span class="sr-only"> Total Services stats</span></a>
          </div>
        </div>
      </dd>
    </div>
{{--
    <!-- Total Branches -->
    <div class="overflow-hidden relative px-4 pt-5 pb-12 bg-gradient-to-br rounded-lg shadow sm:px-6 sm:pt-6 from-denim-600 to-denim-800">
      <dt>
        <div class="absolute p-3 rounded-md backdrop-blur-sm bg-white/20">
          <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
            <path d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>
        <p class="ml-16 text-sm font-medium text-white truncate">Total Branches</p>
      </dt>
      <dd class="flex items-baseline pb-6 ml-16 sm:pb-7">
        <p class="text-2xl font-semibold text-white">{{ $totalBranches }}</p>
        <div class="absolute inset-x-0 bottom-0 px-4 py-4 backdrop-blur-sm bg-white/10 sm:px-6">
          <div class="text-sm">
            <a href="#" class="font-medium text-white hover:text-gray-100">View all<span class="sr-only"> Total Branches stats</span></a>
          </div>
        </div>
      </dd>
    </div> --}}

    <!-- Total Monitors -->
    <div class="overflow-hidden relative px-4 pt-5 pb-12 bg-gradient-to-br rounded-lg shadow sm:px-6 sm:pt-6 from-denim-600 to-denim-800">
      <dt>
        <div class="absolute p-3 rounded-md backdrop-blur-sm bg-white/20">
          <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
            <path d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>
        <p class="ml-16 text-sm font-medium text-white truncate">Total Monitors</p>
      </dt>
      <dd class="flex items-baseline pb-6 ml-16 sm:pb-7">
        <p class="text-2xl font-semibold text-white">{{ $totalMonitors }}</p>
        <div class="absolute inset-x-0 bottom-0 px-4 py-4 backdrop-blur-sm bg-white/10 sm:px-6">
          <div class="text-sm">
            <a href="#" class="font-medium text-white hover:text-gray-100">View all<span class="sr-only"> Total Monitors stats</span></a>
          </div>
        </div>
      </dd>
    </div>

    <!-- Total Users -->
    <div class="overflow-hidden relative px-4 pt-5 pb-12 bg-gradient-to-br rounded-lg shadow sm:px-6 sm:pt-6 from-denim-600 to-denim-800">
      <dt>
        <div class="absolute p-3 rounded-md backdrop-blur-sm bg-white/20">
          <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
            <path d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952a4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>
        <p class="ml-16 text-sm font-medium text-white truncate">Total Users</p>
      </dt>
      <dd class="flex items-baseline pb-6 ml-16 sm:pb-7">
        <p class="text-2xl font-semibold text-white">{{ $totalUsers }}</p>
        <div class="absolute inset-x-0 bottom-0 px-4 py-4 backdrop-blur-sm bg-white/10 sm:px-6">
          <div class="text-sm">
            <a href="#" class="font-medium text-white hover:text-gray-100">View all<span class="sr-only"> Total Users stats</span></a>
          </div>
        </div>
      </dd>
    </div>
  </dl>
</div>

</x-admin-layout>
