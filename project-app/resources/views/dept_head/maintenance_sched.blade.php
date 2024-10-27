<!-- resources/views/dept_head/maintenance_sched.blade.php -->
@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight text-center md:text-left">
    {{ 'Maintenance Scheduling' }}
</h2>
@endsection

@section('content')
<div class="">

        <!-- Toast Notification -->
        {{-- @if(session('status'))
            <div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
                {{ session('status') }}
            </div>
        @endif --}}

        @if(session('status'))
            <div id="toast" class="fixed bottom-5 right-5 px-4 py-2 rounded shadow-lg
                        {{ session('status_type') === 'error' ? 'bg-red-500' : 'bg-green-500' }}
                        text-white">
                {{ session('status') }}
            </div>
        @endif

    <!-- Top Section -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-4 md:space-y-0">
        <!-- Search Bar -->
        <div class="search-container flex items-center w-1/2">
            <form action="{{ route('maintenanceSchedSearch') }}" method="GET" class="w-full">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <x-search-input class="w-80" placeholder="Search Maintenance..." />
            </form>
        </div>

        <!-- Right Section: Download Icon and Create Button -->
        <div class="w-full md:w-auto">
            <div class="flex items-center space-x-1 md:space-x-4">
                <form action="{{ route(Route::currentRouteName()) }}" method="GET">
                    <input type="hidden" name="tab" value="{{ $tab }}"> <!-- Preserve the current tab -->
                    <input type="hidden" name="query"> <!-- Preserve the current search query -->
                    <button id="refreshButton" class="p-2 text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </form>
                {{-- <a href="{{ route('maintenance.download', ['tab' => $tab, 'query']) }}"
                    class="p-2 text-black flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                    </svg>
                </a> --}}
                <!-- Create Maintenance Button -->
                <a href="{{ route('formMaintenance') }}"
                    class="px-3 py-1 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600
                                focus:outline-none flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-7 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Create Maintenance
                </a>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-4">
        <!-- Rows per page dropdown (on the left) -->
        <div class="flex items-center">
            <label for="rows_per_page" class="mr-2 text-gray-700">Rows per page:</label>
            <form action="" method="GET" id="rowsPerPageForm">
                <select name="rows_per_page" id="rows_per_page" class="border rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="document.getElementById('rowsPerPageForm').submit()">
                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                </select>
            </form>
        </div>

        <!-- Pagination (on the right) -->
        <div class="ml-auto pagination-container">
            {{ $records->appends(['rows_per_page' => $perPage])->links() }} <!-- Pagination Links -->
        </div>

    </div>

    <!-- Tabs Section -->
    <div class="mb-4 flex justify-end">
        <ul class="flex border-b">
            <li class="mr-4">
                <a href="{{ route('maintenance_sched') }}" class="inline-block px-4 py-2 {{ $tab === 'preventive' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Preventive</a>
            </li>
            <li class="mr-4">
                <a href="{{ route('maintenance_sched.predictive') }}" class="inline-block px-4 py-2 {{ $tab === 'predictive' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Predictive</a>
            </li>
        </ul>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <div class="hidden md:block">
            <table class="min-w-full bg-white border rounded-md">
                <thead class="bg-gray-100 border-b">
                    @if ($tab === 'preventive')
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('maintenance_sched', ['sort_by' => 'asset.code', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                                Asset Code
                                <x-icons.sort-icon :direction="$sortBy === 'asset.code' ? $sortOrder : null" />
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('maintenance_sched', ['sort_by' => 'asset.name', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                                Asset Name
                                <x-icons.sort-icon :direction="$sortBy === 'asset.name' ? $sortOrder : null" />
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('maintenance_sched', ['sort_by' => 'cost', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                                Cost
                                <x-icons.sort-icon :direction="$sortBy === 'cost' ? $sortOrder : null" />
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('maintenance_sched', ['sort_by' => 'frequency', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                                Frequency
                                <x-icons.sort-icon :direction="$sortBy === 'frequency' ? $sortOrder : null" />
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('maintenance_sched', ['sort_by' => 'ends', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                                Ends
                                <x-icons.sort-icon :direction="$sortBy === 'ends' ? $sortOrder : null" />
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Occurrences
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Next Maintenance In
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                    @elseif ($tab === 'predictive')
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('maintenance_sched.predictive', ['sort_by' => 'asset.code', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                                Asset Code
                                <x-icons.sort-icon :direction="$sortBy === 'asset.code' ? $sortOrder : null" />
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('maintenance_sched.predictive', ['sort_by' => 'asset.name', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                                Asset Name
                                <x-icons.sort-icon :direction="$sortBy === 'asset.name' ? $sortOrder : null" />
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('maintenance_sched.predictive', ['sort_by' => 'category.name', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                                Category
                                <x-icons.sort-icon :direction="$sortBy === 'category.name' ? $sortOrder : null" />
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('maintenance_sched.predictive', ['sort_by' => 'average_cost', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                                Average Cost
                                <x-icons.sort-icon :direction="$sortBy === 'average_cost' ? $sortOrder : null" />
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('maintenance_sched.predictive', ['sort_by' => 'repair_count', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                                Repair Count
                                <x-icons.sort-icon :direction="$sortBy === 'repair_count' ? $sortOrder : null" />
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('maintenance_sched.predictive', ['sort_by' => 'recommendation', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                                Recommendation
                                <x-icons.sort-icon :direction="$sortBy === 'recommendation' ? $sortOrder : null" />
                            </a>
                        </th>
                    </tr>
                    @endif
                </thead>

                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if ($records->isEmpty())
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-sm text-gray-500 text-center">No maintenance schedule found.</td>
                    </tr>
                    @else
                        @foreach ($records as $record)
                            {{-- <tr data-asset-key="{{ $record->asset_key }}"> --}}
                            <tr data-asset-key="{{ $record->asset_key }}" data-updated-at="{{ $record->updated_at }}" data-next-maintenance="{{ $record->next_maintenance_timestamp }}">
                                <td class="px-6 py-4">{{ $record->asset->code }}</td>
                                <td class="px-6 py-4">{{ $record->asset->name }}</td>
                                @if ($tab === 'preventive')
                                    <td class="px-6 py-4">₱ {{ $record->cost }}</td>
                                    <td class="frequency px-6 py-4">Every {{ $record->frequency }} day/s</td>
                                    <td class="ends px-6 py-4" data-ends="{{ $record->ends }}">After {{ $record->ends }} occurrence/s</td>
                                    <td class="occurrences px-6 py-4">{{ $record->occurrences }}</td>
                                    <td class="status px-6 py-4">{{ ucfirst($record->status) }}</td>
                                    <td class="next-maintenance px-6 py-4" id="next-maintenance-{{ $loop->index }}">
                                        Loading...
                                    </td>

                                    <td class="px-6 py-4">
                                        <button
                                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                                            onclick="openEditModal({{ $record->id }})">
                                            Edit
                                        </button>
                                    </td>
                                @elseif ($tab === 'predictive')
                                    <td class="px-6 py-4">{{ $record->asset->category->name }}</td>
                                    <td class="px-6 py-4">₱ {{ $record->average_cost }}</td>
                                    <td class="px-6 py-4">{{ $record->repair_count }}</td>
                                    <td class="px-6 py-4">{{ $record->recommendation }}</td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Card layout for small screens -->
        <div class="block md:hidden space-y-4"> <!-- Card layout for small screens -->
            @foreach ($records as $record)
            <div class="bg-white p-4 rounded-md shadow-md border" data-asset-key="{{ $record->asset_key }}">
                <p><strong>Asset Code:</strong> {{ $record->asset->code }}</p>
                <p><strong>Asset Name:</strong> {{ $record->asset->name }}</p>
                @if ($tab === 'preventive')
                <p><strong>Cost:</strong> ₱ {{ $record->cost }}</p>
                <p><strong>Frequency:</strong> Every {{ $record->frequency }} days</p>
                <p><strong>Ends:</strong> After {{ $record->ends }} occurrence(s)</p>
                <p class="occurrences"><strong>Occurrences:</strong> {{ $record->occurrences }}</p>
                <p class="status"><strong>Status:</strong> {{ ucfirst($record->status) }}</p>
                <p class="next-maintenance" id="next-maintenance-{{ $loop->index }}"
                    data-next-maintenance="{{ $record->next_maintenance_timestamp ?? 0 }}">
                    Loading...
                </p>
                <!-- Action Buttons -->
                <div class="flex justify-between mt-2">
                    <button
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                        onclick="openEditModal({{ $record->id }})">
                        Edit
                    </button>
                </div>
                @elseif ($tab === 'predictive')
                <p><strong>Category:</strong> {{ $record->asset->category->name }}</p>
                <p><strong>Average Cost:</strong> ₱ {{ $record->average_cost }}</p>
                <p><strong>Repair Count:</strong> {{ $record->repair_count }}</p>
                <p><strong>Recommendation:</strong> {{ $record->recommendation }}</p>
                @endif
            </div>
            @endforeach
        </div>

    </div>
</div>

<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    {{-- <div class="bg-white rounded-lg w-1/2 p-6 shadow-lg"> --}}
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6 sm:p-8 lg:max-w-xl">
        {{-- <h2 class="text-lg font-semibold mb-4">Edit Preventive Maintenance</h2> --}}
        <h2 class="text-lg font-semibold mb-4 text-center">Edit Preventive Maintenance</h2>

        <!-- Form to update preventive maintenance -->
        <form id="editForm" action="" method="POST">
            @csrf
            @method('PUT')

            {{-- <!-- Cost Field -->
            <div class="mb-4">
                <label for="cost" class="block text-sm font-medium text-gray-700">Cost</label>
                <input type="number" step="0.01" name="cost" id="edit_cost" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Frequency Field -->
            <div class="mb-4">
                <label for="frequency" class="block text-sm font-medium text-gray-700">Frequency (in days)</label>
                <input type="number" name="frequency" id="edit_frequency" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Ends Field -->
            <div class="mb-4">
                <label for="ends" class="block text-sm font-medium text-gray-700">Ends After (Occurrences)</label>
                <input type="number" name="ends" id="edit_ends" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div> --}}

            <!-- Status Field (Dropdown) -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="edit_status" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="toggleFieldsBasedOnStatus()">
                    <option value="active">Active</option>
                    {{-- <option value="completed">Completed</option> --}}
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Cancellation Reason (Read-only or editable based on status) -->
            <div class="mb-4" id="cancellation_reason_div">
                <label for="cancel_reason" class="block text-sm font-medium text-gray-700">Cancellation Reason</label>
                <textarea name="cancel_reason" id="cancel_reason" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <!-- Cancel Button -->
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none">
                    Cancel
                </button>

                <!-- Save Changes Button -->
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Styles for Pagination -->
<style>
    /* Reduce the size of pagination on smaller screens */
    @media (max-width: 640px) {
        .pagination-container nav {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination-container nav .page-item {
            margin: 0 2px;
            /* Reduce spacing between items */
        }

        .pagination-container nav .page-link {
            font-size: 0.75rem;
            /* Smaller text */
            padding: 0.25rem 0.5rem;
            /* Smaller padding */
            border-radius: 4px;
            /* Smaller corners */
        }
    }

    /* Optional: Add hover styles */
    .pagination-container nav .page-link:hover {
        background-color: #e5e7eb;
        /* Light gray hover background */
    }
</style>

<script>
function toggleFieldsBasedOnStatus() {
    const statusField = document.getElementById('edit_status');
    const cancelReasonField = document.getElementById('cancel_reason');
    const allFields = document.querySelectorAll('#editForm input, #editForm textarea, #editForm select');

        // Get the original status (from the database)
        const originalStatus = statusField.getAttribute('data-original-status');

        // If the status was already cancelled from the database, make everything read-only
        if (originalStatus === 'cancelled') {
            allFields.forEach(field => {
                field.readOnly = true;
                field.classList.add('bg-gray-200'); // Gray out fields
            });
            statusField.disabled = true; // Also disable the status dropdown
        } else {
            // If the status is now set to cancelled, allow the cancellation reason to be edited
            if (statusField.value === 'cancelled') {
                // Make all fields except 'cancel_reason' read-only and gray out
                allFields.forEach(field => {
                    if (field.id !== 'cancel_reason') {
                        field.readOnly = true;
                        field.classList.add('bg-gray-200'); // Gray out fields
                    }
                });
                // Enable the cancellation reason field to allow input
                cancelReasonField.readOnly = false;
                cancelReasonField.classList.remove('bg-gray-200'); // Make it editable
            } else {
                // If the status is active or completed, make all fields editable
                allFields.forEach(field => {
                    field.readOnly = false;
                    field.classList.remove('bg-gray-200'); // Remove gray background
                });
                statusField.disabled = false; // Enable the status dropdown
            }
        }
    }

    // On modal load or DOMContentLoaded, run the function to adjust fields based on the status
    document.addEventListener('DOMContentLoaded', function() {
        toggleFieldsBasedOnStatus(); // Call the function to handle fields based on the status
    });

    function openEditModal(id) {
        console.log('Edit button clicked for ID:', id);

        // Fetch data for the selected preventive maintenance
        fetch(`/preventive/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                // document.getElementById('edit_cost').value = data.cost;
                // document.getElementById('edit_frequency').value = data.frequency;
                // document.getElementById('edit_ends').value = data.ends;
                document.getElementById('edit_status').value = data.status;
                document.getElementById('cancel_reason').value = data.cancel_reason;

                // Set the original status from the backend
                document.getElementById('edit_status').setAttribute('data-original-status', data.status);

                document.getElementById('editForm').action = `/preventive/${id}`; // Set dynamically

                toggleFieldsBasedOnStatus(); // Call toggleFieldsBasedOnStatus based on the status value

                document.getElementById('editModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error fetching preventive maintenance data:', error);
            });
    }



    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

<script>
    // Toast Notification fade-out
    setTimeout(function() {
        var toast = document.getElementById('toast');
        if (toast) {
            toast.style.transition = 'opacity 1s ease';
            toast.style.opacity = '0';
            setTimeout(function() {
                toast.remove();
            }, 1000); // Remove it after fading out
        }
    }, 3000); // 3 seconds delay
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('tr[data-next-maintenance]');

    rows.forEach(row => {
        const countdownElem = row.querySelector('.next-maintenance');
        const statusElem = row.querySelector('.status');
        const nextMaintenance = row.getAttribute('data-next-maintenance');

        // Handle 'Cancelled' status early to prevent countdown
        if (statusElem && statusElem.innerText.trim().toLowerCase() === 'cancelled') {
            countdownElem.innerHTML = "Maintenance Cancelled";
            return;
        }

        // Ensure the timestamp exists and is valid
        if (nextMaintenance && !isNaN(nextMaintenance)) {
            const nextMaintenanceDate = new Date(parseInt(nextMaintenance) * 1000); // Convert to milliseconds

            if (!isNaN(nextMaintenanceDate.getTime())) {
                // Start the countdown interval
                row.interval = setInterval(() => {
                    updateCountdown(row, countdownElem, nextMaintenanceDate);
                }, 1000);
            } else {
                countdownElem.innerHTML = "Invalid Date";
            }
        } else {
            countdownElem.innerHTML = "No Maintenance Scheduled";
        }
    });
});

function updateCountdown(row, countdownElem, nextMaintenanceDate) {
    const statusElem = row.querySelector('.status');
    const occurrencesElem = row.querySelector('.occurrences');
    const endsElem = row.querySelector('.ends');

    const occurrences = parseInt(occurrencesElem.innerText) || 0;
    const ends = parseInt(endsElem.getAttribute('data-ends')) || 0;

    const currentTime = new Date();
    const timeDiff = nextMaintenanceDate - currentTime;

    console.log('Current Time:', currentTime);
    console.log('Next Maintenance Timestamp:', nextMaintenanceDate);
    console.log('Time Difference (ms):', timeDiff);

    // Stop the countdown if status is 'Cancelled'
    if (statusElem && statusElem.innerText.trim().toLowerCase() === 'cancelled') {
        countdownElem.innerHTML = "Maintenance Cancelled";
        clearInterval(row.interval);
        return;
    }

    // Stop if occurrences have reached or exceeded the limit
    if (ends !== 0 && occurrences >= ends) {
        countdownElem.innerHTML = "Maintenance Completed";
        clearInterval(row.interval);
        return;
    }

    // If the time difference is negative, trigger maintenance immediately
    if (timeDiff <= 0) {
        countdownElem.innerHTML = "Maintenance Due Now!";
        clearInterval(row.interval);
        generateMaintenanceRequest(row, occurrencesElem, () => {
            resetCountdown(row, countdownElem);
        });
        return;
    }

    // Calculate time components
    const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

    console.log(`Days: ${days}, Hours: ${hours}, Minutes: ${minutes}, Seconds: ${seconds}`);

    // Display the remaining time
    countdownElem.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
}


function generateMaintenanceRequest(row, occurrencesElem, callback) {
    const assetKey = row.getAttribute('data-asset-key');
    const countdownElem = row.querySelector('.next-maintenance');
    let occurrences = parseInt(occurrencesElem.innerText) || 0;

    fetch('{{ route("run-maintenance-check") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            asset_key: assetKey,
            occurrences: occurrences + 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            occurrencesElem.innerText = occurrences + 1;
            countdownElem.innerHTML = "Maintenance Generated";

            console.log('Maintenance request generated successfully.');

            setTimeout(() => callback(), 2000); // Reset after 2 seconds
        } else {
            console.error('Error:', data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}



function resetCountdown(row, countdownElem) {
    const frequencyElem = row.querySelector('.frequency');
    const frequencyText = frequencyElem ? frequencyElem.innerText : null;
    const frequencyMatch = frequencyText ? frequencyText.match(/\d+/) : null;

    if (!frequencyMatch) {
        console.error('Invalid frequency value.');
        return;
    }

    const frequency = parseInt(frequencyMatch[0]);
    const nextMaintenanceDate = new Date(Date.now() + frequency * 86400 * 1000); // Calculate next timestamp

    console.log(`Resetting countdown for the next ${frequency} day(s).`);

    // Update backend with the new timestamp
    fetch('{{ route("reset-countdown") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            asset_key: row.getAttribute('data-asset-key')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the data attribute with the new timestamp
            row.setAttribute('data-next-maintenance', Math.floor(nextMaintenanceDate.getTime() / 1000));
            countdownElem.innerHTML = "Maintenance Generated";

            // Clear the interval and start a new one
            clearInterval(row.interval);
            row.interval = setInterval(() => {
                updateCountdown(row, countdownElem, nextMaintenanceDate);
            }, 1000);
        } else {
            console.error('Failed to reset countdown:', data.error);
        }
    })
    .catch(error => {
        console.error('Error resetting countdown:', error);
    });
}

</script>


@endsection
