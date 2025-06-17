<div>
    @include('partials.settings-heading')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Dashboard</h1>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Stats Overview -->
            <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Queues Today -->
                <x-card class="bg-white dark:bg-secondary-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-icon name="ticket" class="h-6 w-6 text-blue-600" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Total Queues Today
                                </dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $totalQueues }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </x-card>

                <!-- Active Counters -->
                <x-card class="bg-white dark:bg-secondary-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-icon name="desktop-computer" class="h-6 w-6 text-green-600" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Active Counters
                                </dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $activeCounters }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </x-card>

                <!-- Total Services -->
                <x-card class="bg-white dark:bg-secondary-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-icon name="collection" class="h-6 w-6 text-indigo-600" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Available Services
                                </dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $totalServices }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </x-card>

                <!-- Queue Status -->
                <x-card class="bg-white dark:bg-secondary-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-icon name="chart-pie" class="h-6 w-6 text-yellow-600" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Waiting in Queue
                                </dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $queuesByStatus['waiting'] ?? 0 }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Branch Overview -->
            <div class="mt-8">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Branch Overview</h2>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($branches as $branch)
                        <x-card class="bg-white dark:bg-secondary-800">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $branch->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Code: {{ $branch->code }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ $branch->queues_count }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Queues Today</p>
                                </div>
                            </div>
                        </x-card>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
