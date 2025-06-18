<div>
    @section('nav-title', 'Counter')
    <x-admin-layout>
        <div class="max-w-8xl mx-auto px-4 py-12">

            <!-- GRID: 2 COLUMNS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

              <!-- LEFT COLUMN: STATUS + NOW SERVING -->
              <div class="bg-white shadow rounded-lg p-8 space-y-8">

                <!-- Now Serving -->
                <div>
                  <h2 class="text-lg uppercase font-medium text-gray-600 mb-4 text-center">Now Serving</h2>
                  <div class="flex justify-center items-center aspect-square w-80 h-80 mx-auto border-2 border-gray-200 rounded-xl mb-8 shadow-lg">
                    <div class="text-8xl font-bold text-gray-900">QUE-123</div>
                  </div>
                  <div class="grid grid-cols-4 gap-4">
                    <button class="px-5 py-3 border border-gray-300 text-gray-800 rounded hover:bg-gray-100">Complete</button>
                    <button class="px-5 py-3 border border-gray-300 text-gray-800 rounded hover:bg-gray-100">Hold</button>
                    <button class="px-5 py-3 border border-gray-300 text-gray-800 rounded hover:bg-gray-100">Skip</button>
                    <button class="px-5 py-3 border border-gray-300 text-gray-800 rounded hover:bg-gray-100 ">
                        Cancel
                      </button>
                  </div>
                </div>

              </div>

              <!-- RIGHT COLUMN: NEXT NUMBERS + CURRENTLY SERVING + HOLD SELECT -->
              <div class="bg-white shadow rounded-lg p-8 space-y-8">

                <!-- Next Numbers -->
                <div>
                  <div class="flex justify-between items-center mb-4">
                    <h2 class="text-sm uppercase font-medium text-gray-500">Next Tickets</h2>
                    <button class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 transition">
                      Start Break
                    </button>
                  </div>
                  <div class="grid grid-cols-3 gap-4 mb-2">
                    <div class="px-6 py-4 bg-gray-100 rounded text-3xl font-semibold text-gray-800">QUE-124</div>
                    <div class="px-6 py-4 bg-gray-100 rounded text-3xl font-semibold text-gray-800">QUE-125</div>
                    <div class="px-6 py-4 bg-gray-100 rounded text-3xl font-semibold text-gray-800">QUE-126</div>
                  </div>
                </div>
                <div>
                    <h2 class="text-sm uppercase font-medium text-gray-500 mb-4">Resume Hold</h2>
                    <select class="w-full border border-gray-300 rounded px-4 py-3 mb-4">
                      <option value="">Select a Hold Ticket</option>
                      <option value="QUE-110">QUE-110</option>
                      <option value="QUE-115">QUE-115</option>
                      <option value="QUE-120">QUE-120</option>
                    </select>
                    <div class="flex gap-4">


                    </div>
                  </div>
                <!-- Currently Serving -->
                <div>
                  <h2 class="text-sm uppercase font-medium text-gray-500 mb-4">Currently Serving By Other Counter</h2>
                  <div class="grid grid-cols-1 gap-2">
                    <span class="px-4 py-2 bg-gray-50 border border-gray-200 rounded text-sm font-medium text-gray-800">
                      QUE-101
                    </span>
                    <span class="px-4 py-2 bg-gray-50 border border-gray-200 rounded text-sm font-medium text-gray-800">
                      QUE-102
                    </span>
                    <span class="px-4 py-2 bg-gray-50 border border-gray-200 rounded text-sm font-medium text-gray-800">
                      QUE-103
                    </span>
                  </div>
                </div>

                <!-- Hold Ticket Selector -->


              </div>

            </div>

          </div>

    </x-admin-layout>
</div>
