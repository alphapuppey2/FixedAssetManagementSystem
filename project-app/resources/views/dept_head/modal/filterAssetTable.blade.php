<div id="filterModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl font-semibold mb-4">Filter Assets</h2>
        <form id="filterForm" action="#" method="GET">
            <!-- Status Dropdown -->
            <div class="mb-4 relative">
                <button type="button" id="statusDropdownBtn"
                    class="w-full bg-gray-100 border border-gray-300 p-2 rounded-md text-left flex justify-between items-center">
                    Select Status
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="statusDropdown"
                    class="absolute hidden bg-white shadow-md border rounded-md w-full mt-2 z-10 max-h-56 overflow-y-auto">
                    <label class="block px-4 py-2">
                        <input type="checkbox" name="status[]" value="active" class="mr-2"> Active
                    </label>
                    <label class="block px-4 py-2">
                        <input type="checkbox" name="status[]" value="under_maintenance" class="mr-2"> Under Maintenance
                    </label>
                    <label class="block px-4 py-2">
                        <input type="checkbox" name="status[]" value="deployed" class="mr-2"> Deployed
                    </label>
                    <label class="block px-4 py-2">
                        <input type="checkbox" name="status[]" value="disposed" class="mr-2"> Disposed
                    </label>
                </div>
            </div>

            <!-- Category Dropdown -->
            <div class="mb-4 relative">
                <button type="button" id="categoryDropdownBtn"
                    class="w-full bg-gray-100 border border-gray-300 p-2 rounded-md text-left flex justify-between items-center">
                    Select Category
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <!-- Category Dropdown -->
                <div id="categoryDropdown" class="absolute hidden bg-white shadow-md border rounded-md w-full mt-2 z-10 max-h-56 overflow-y-auto">
                    @foreach ($categoriesList as $category)
                        <label class="block px-4 py-2">
                            <input type="checkbox" name="category[]" value="{{ $category->id }}"
                                {{ in_array($category->id, $categories ?? []) ? 'checked' : '' }} class="mr-2">
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Date Range Filter -->
            <div class="mb-4">
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                    class="mt-1 w-full p-2 border border-gray-300 rounded-md">

                <label for="end_date" class="block text-sm font-medium text-gray-700 mt-4">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                    class="mt-1 w-full p-2 border border-gray-300 rounded-md">

                <!-- Error Message Display -->
                <p id="dateError" class="text-red-500 text-sm mt-2 hidden">Start date cannot be greater than end date.</p>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-2">
                <button type="button" id="clearFiltersBtn"
                    class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</button>
                <button type="submit" id="applyFiltersBtn"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md">Apply</button>
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

// Date Range Validation
document.getElementById('applyFiltersBtn').addEventListener('click', function (event) {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const dateError = document.getElementById('dateError');

    dateError.classList.add('hidden');  // Clear any previous error

    if (startDate && endDate && startDate > endDate) {
        event.preventDefault();  // Stop form submission
        dateError.classList.remove('hidden');  // Display error message
    }
});

// Open the Filter Modal
document.getElementById('openFilterModalBtn').addEventListener('click', function () {
    document.getElementById('filterModal').classList.remove('hidden');
});

// Clear Filters and Close Modal
document.getElementById('clearFiltersBtn').addEventListener('click', function () {
    document.getElementById('filterForm').reset();  // Reset all inputs
    window.history.replaceState({}, document.title, window.location.pathname);  // Clear query params
    document.getElementById('filterModal').classList.add('hidden');  // Hide the modal
});

</script>
