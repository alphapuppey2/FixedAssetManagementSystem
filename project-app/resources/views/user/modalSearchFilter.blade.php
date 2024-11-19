<!-- Modal Search Filter -->
<div id="searchFilterModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Search & Filter</h2>
            <button onclick="closeDaModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Search Form with Filters -->
        <form method="GET" action="{{ route('requests.list') }}" class="space-y-4">
            <!-- Search Bar -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by ID, Description, Status..." class="block w-full pl-3 pr-4 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="block w-full pl-3 pr-10 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="request" {{ request('status') == 'request' ? 'selected' : '' }}>Request</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="denied" {{ request('status') == 'denied' ? 'selected' : '' }}>Denied</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" id="type" class="block w-full pl-3 pr-10 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Types</option>
                    <option value="repair" {{ request('type') == 'repair' ? 'selected' : '' }}>Repair</option>
                    <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="upgrade" {{ request('type') == 'upgrade' ? 'selected' : '' }}>Upgrade</option>
                    <option value="inspection" {{ request('type') == 'inspection' ? 'selected' : '' }}>Inspection</option>
                    <option value="replacement" {{ request('type') == 'replacement' ? 'selected' : '' }}>Replacement</option>
                    <option value="calibration" {{ request('type') == 'calibration' ? 'selected' : '' }}>Calibration</option>
                </select>
            </div>

            <!-- Date Range Filters -->
            <div class="flex space-x-2">
                <div class="w-1/2">
                    <label for="from_date" class="block text-sm font-medium text-gray-700">From</label>
                    <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" class="block w-full py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="w-1/2">
                    <label for="to_date" class="block text-sm font-medium text-gray-700">To</label>
                    <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}" class="block w-full py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <!-- Submit and Clear Buttons -->
            <div class="flex justify-end space-x-2">
                <a href="{{ route('requests.list') }}" class="text-sm hover:bg-red-300 text-red-800 font-semibold py-2 px-3 rounded-md focus:outline-none">
                    Clear Filters
                </a>
                <button type="submit" class="text-sm bg-blue-900 hover:bg-blue-600 text-white font-semibold py-2 px-3 rounded-md focus:outline-none">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal JavaScript -->
<script>
    function openModal() {
        const modal = document.getElementById('searchFilterModal');
        modal.classList.remove('hidden'); // Make the modal visible
        modal.classList.add('flex'); // Add flex layout to center the modal
        console.log("HELLLOOOSS");

    }


    function closeDaModal() {
        const modal = document.getElementById('searchFilterModal');
        modal.classList.add('hidden'); // Hide the modal
        modal.classList.remove('flex'); // Remove flex layout

    }



</script>
