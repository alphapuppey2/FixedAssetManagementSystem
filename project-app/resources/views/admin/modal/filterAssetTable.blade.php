<div id="filterModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl font-semibold mb-4">Filter Assets</h2>

        <form id="filterForm" action="{{ route('asset') }}" method="GET">
            <!-- Status Dropdown -->
            <div class="mb-4 relative">
                <button type="button" id="statusDropdownBtn"
                    class="w-full bg-gray-100 border border-gray-300 px-4 py-2 rounded-md flex justify-between items-center">
                    Select Status
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="statusDropdown"
                    class="absolute hidden bg-white border rounded-md mt-2 w-full z-10 shadow-lg max-h-56 overflow-y-auto">
                    @foreach (['active', 'under_maintenance', 'deployed', 'disposed'] as $status)
                        <label class="block px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="status[]" value="{{ $status }}"
                                {{ in_array($status, (array) request('status', [])) ? 'checked' : '' }} class="mr-2">
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Category Dropdown -->
            <div class="mb-4 relative">
                <button type="button" id="categoryDropdownBtn"
                    class="w-full bg-gray-100 border border-gray-300 px-4 py-2 rounded-md flex justify-between items-center">
                    Select Category
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="categoryDropdown"
                    class="absolute hidden bg-white border rounded-md mt-2 w-full z-10 shadow-lg max-h-56 overflow-y-auto">
                    @foreach ($categoriesList as $category)
                        <label class="block px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="category[]" value="{{ $category->id }}"
                                {{ in_array($category->id, (array) request('category', [])) ? 'checked' : '' }} class="mr-2">
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Date Range -->
            <div class="mb-4">
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">

                <label for="end_date" class="block mt-4 text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" id="cancelFilterBtn"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Apply
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle Status Dropdown
    document.getElementById('statusDropdownBtn').addEventListener('click', function () {
        document.getElementById('statusDropdown').classList.toggle('hidden');
    });

    // Toggle Category Dropdown
    document.getElementById('categoryDropdownBtn').addEventListener('click', function () {
        document.getElementById('categoryDropdown').classList.toggle('hidden');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (event) {
        if (!event.target.closest('#statusDropdown') &&
            !event.target.closest('#statusDropdownBtn')) {
            document.getElementById('statusDropdown').classList.add('hidden');
        }

        if (!event.target.closest('#categoryDropdown') &&
            !event.target.closest('#categoryDropdownBtn')) {
            document.getElementById('categoryDropdown').classList.add('hidden');
        }
    });

    // Cancel Button Logic
    document.getElementById('cancelFilterBtn').addEventListener('click', function () {
        document.getElementById('filterModal').classList.add('hidden'); // Hide the modal
        document.getElementById('filterForm').reset(); // Reset the form inputs
    });
</script>
