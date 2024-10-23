@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight text-center md:text-left">
    {{ 'Maintenance Scheduling' }}
</h2>
@endsection

@section('content')
<div class="px-6 py-4">

    <!-- Toast Notification -->
    @if(session('status'))
    <div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
        {{ session('status') }}
    </div>
    @endif

    <!-- Top Section -->
    <div class="flex justify-between items-center mb-4">
        <!-- Search Bar -->
        <div class="flex items-center w-1/2">
            <form action="" method="GET" class="w-full">
                <input type="hidden" name="tab" value="{{ $tab }}"> <!-- Include the current tab -->
                <input type="text" name="query" placeholder="Search..." value="{{ request('query') }}" class="w-1/2 px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </form>
        </div>

        <!-- Right Section: Download Icon and Create Button -->
        <div class="flex items-center space-x-4">
            <form action="{{ route(Route::currentRouteName()) }}" method="GET">
                <input type="hidden" name="tab" value="{{ $tab }}"> <!-- Preserve the current tab -->
                <input type="hidden" name="query" value="{{ $searchQuery }}"> <!-- Preserve the current search query -->
                <button id="refreshButton" class="p-2 text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </button>
            </form>
            <a href="" class="p-2 text-black">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                </svg>
            </a>
            <a href="{{ route('adminFormMaintenance') }}" class="px-3 py-1 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 focus:outline-none flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Create Maintenance
            </a>
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
        <div class="ml-auto">
            {{ $records->appends(['rows_per_page' => $perPage])->links() }} <!-- Pagination Links -->
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="mb-4 flex justify-end">
        <ul class="flex border-b">
            <li class="mr-4">
                <a href="{{ route('adminMaintenance_sched') }}" class="inline-block px-4 py-2 {{ $tab === 'preventive' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Preventive Maintenance</a>
            </li>
            <li class="mr-4">
                <a href="{{ route('adminMaintenance_sched.predictive') }}" class="inline-block px-4 py-2 {{ $tab === 'predictive' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Predictive Maintenance</a>
            </li>
        </ul>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-md">
            <thead class="bg-gray-100 border-b">
                @if ($tab === 'preventive')
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('maintenance_sched', ['sort_by' => 'asset.code', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                            Asset Code
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('maintenance_sched', ['sort_by' => 'asset.name', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                            Asset Name
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('maintenance_sched', ['sort_by' => 'cost', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                            Cost
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('maintenance_sched', ['sort_by' => 'frequency', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                            Frequency
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('maintenance_sched', ['sort_by' => 'ends', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                            Ends
                        </a>
                    </th>
                    <!-- New Columns -->
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
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('maintenance_sched.predictive', ['sort_by' => 'asset.name', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                            Asset Name
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('maintenance_sched.predictive', ['sort_by' => 'category.name', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                            Category
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('maintenance_sched.predictive', ['sort_by' => 'average_cost', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                            Average Cost
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('maintenance_sched.predictive', ['sort_by' => 'repair_count', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                            Repair Count
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('maintenance_sched.predictive', ['sort_by' => 'recommendation', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}">
                            Recommendation
                        </a>
                    </th>
                </tr>
                @endif
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if ($records->isEmpty())
                <tr>
                    <td colspan="10" class="px-6 py-4 text-sm text-gray-500 text-center">No maintenance schedule found.</td>
                </tr>
                @else
                @foreach ($records as $record)
                <tr data-asset-key="{{ $record->asset_key }}">
                    <td class="px-6 py-4">{{ $record->asset->code }}</td>
                    <td class="px-6 py-4">{{ $record->asset->name }}</td>
                    @if ($tab === 'preventive')
                    <td class="px-6 py-4">₱ {{ $record->cost }}</td>
                    <td class="px-6 py-4">Every {{ $record->frequency }} day/s</td>
                    <td class="ends px-6 py-4" data-ends="{{ $record->ends }}">After {{ $record->ends }} occurrence/s</td>
                    <td class="occurrences px-6 py-4">{{ $record->occurrences }}</td>
                    <td class="status px-6 py-4">{{ ucfirst($record->status) }}</td>
                    <td class="next-maintenance px-6 py-4" id="next-maintenance-{{ $loop->index }}"
                        data-next-maintenance="{{ $record->next_maintenance_timestamp ?? 0 }}">
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
</div>

<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg w-1/2 p-6 shadow-lg">
        <h2 class="text-lg font-semibold mb-4">Edit Preventive Maintenance</h2>

        <!-- Form to update preventive maintenance -->
        <form id="editForm" action="" method="POST">
            @csrf
            @method('PUT')

            <!-- Cost Field -->
            <div class="mb-4">
                <label for="cost" class="block text-sm font-medium text-gray-700">Cost</label>
                <input type="number" step="0.01" name="cost" id="edit_cost" class="w-full border rounded-md" required>
            </div>

            <!-- Frequency Field -->
            <div class="mb-4">
                <label for="frequency" class="block text-sm font-medium text-gray-700">Frequency (in days)</label>
                <input type="number" name="frequency" id="edit_frequency" class="w-full border rounded-md" required>
            </div>

            <!-- Ends Field -->
            <div class="mb-4">
                <label for="ends" class="block text-sm font-medium text-gray-700">Ends After (Occurrences)</label>
                <input type="number" name="ends" id="edit_ends" class="w-full border rounded-md" required>
            </div>

            <!-- Status Field (Dropdown) -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="edit_status" class="w-full border rounded-md" required onchange="toggleFieldsBasedOnStatus()">
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Cancellation Reason (Read-only or editable based on status) -->
            <div class="mb-4" id="cancellation_reason_div">
                <label for="cancel_reason" class="block text-sm font-medium text-gray-700">Cancellation Reason</label>
                <textarea name="cancel_reason" id="cancel_reason" class="w-full border rounded-md"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <!-- Cancel Button -->
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                    Cancel
                </button>

                <!-- Save Changes Button -->
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

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
            statusField.disabled = false; // Also disable the status dropdown
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
    // Construct the URL

    console.log('Fetching data from URL:', url); // Log the URL to the console

    // Use fetch to load the data for the selected preventive maintenance
    fetch(`/admin/preventive/${id}/edit`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch preventive maintenance data');
            }
            console.log("logs");
            return response.json();
        })
        .then(data => {
            // Populate the modal fields with the fetched data
            document.getElementById('edit_cost').value = data.cost;
            document.getElementById('edit_frequency').value = data.frequency;
            document.getElementById('edit_ends').value = data.ends;
            document.getElementById('edit_status').value = data.status;
            document.getElementById('cancel_reason').value = data.cancel_reason || ''; // Handle null reason

            // Set the original status from the backend for status change logic
            document.getElementById('edit_status').setAttribute('data-original-status', data.status);

            // Set the form action dynamically
            document.getElementById('editForm').action = `/admin/preventive/${id}`;

            // Display the modal
            document.getElementById('editModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error fetching preventive maintenance data:', error);
            alert('Failed to load data. Please try again.');
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
    document.addEventListener('DOMContentLoaded', function() {
        const countdownElems = document.querySelectorAll('[id^="next-maintenance-"]');

        countdownElems.forEach(countdownElem => {
            let nextMaintenanceTimestamp = parseInt(countdownElem.getAttribute('data-next-maintenance')) * 1000;
            countdownElem.isRequestPending = false; // Initialize isRequestPending for each countdown element
            const row = countdownElem.closest('tr');
            const statusElem = row.querySelector('.status'); // Get the status element

            // If status is 'Cancelled', stop the countdown and show 'Maintenance Cancelled'
            if (statusElem.innerText.toLowerCase() === 'cancelled') {
                countdownElem.innerHTML = "Maintenance Cancelled"; // Show maintenance cancelled text
                return; // Stop any further countdown logic
            }

            if (nextMaintenanceTimestamp) {
                let nextMaintenanceDate = new Date(nextMaintenanceTimestamp);
                countdownElem.interval = setInterval(() => {
                    updateCountdown(countdownElem, nextMaintenanceDate);
                }, 1000); // Update every second
            } else {
                countdownElem.innerHTML = "Invalid Maintenance Date";
            }
        });
    });

    function updateCountdown(countdownElem, nextMaintenanceDate) {
        const row = countdownElem.closest('tr');
        const occurrencesElem = row.querySelector('.occurrences');
        const endsElem = row.querySelector('.ends');
        const statusElem = row.querySelector('.status'); // Get the status element

        const occurrences = parseInt(occurrencesElem.innerText);
        const ends = parseInt(endsElem.getAttribute('data-ends')); // Get the numeric value from data-ends

        // If status is 'Cancelled', stop the countdown and show 'Maintenance Cancelled'
        if (statusElem.innerText.toLowerCase() === 'cancelled') {
            countdownElem.innerHTML = "Maintenance Cancelled";
            clearInterval(countdownElem.interval); // Stop the countdown by clearing the interval
            return; // Exit the function to stop further countdown logic
        }

        // Handle NaN values and log for debugging
        if (isNaN(occurrences) || isNaN(ends)) {
            console.error(`Invalid occurrences or ends value: Occurrences = ${occurrences}, Ends = ${ends}`);
            return;
        }

        console.log(`Occurrences: ${occurrences}, Ends: ${ends}`);

        // If occurrences have reached or exceeded ends, stop the countdown and show "Maintenance Completed"
        if (occurrences >= ends) {
            countdownElem.innerHTML = "Maintenance Completed"; // Display "Maintenance Completed" in the Next Maintenance In column
            console.log('Stopping countdown because occurrences reached ends');
            clearInterval(countdownElem.interval); // Stop the countdown by clearing the interval
            return; // Exit the function to stop further countdown logic
        }

        const currentDate = new Date();
        const timeDiff = nextMaintenanceDate - currentDate;

        if (timeDiff <= 0 && !countdownElem.isRequestPending) {
            // Trigger the maintenance request only once per cycle when countdown hits 0
            countdownElem.isRequestPending = true; // Prevent multiple requests
            console.log('Countdown hit 0, generating maintenance request');

            // Trigger the maintenance request
            generateMaintenanceRequest(countdownElem, () => {
                // Reset the countdown after the request and set isRequestPending to false
                resetCountdown(countdownElem);
                countdownElem.isRequestPending = false; // Allow the next cycle to proceed
            });

        } else if (timeDiff > 0) {
            // Calculate time left in days, hours, minutes, and seconds
            const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

            // Display the remaining time
            countdownElem.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        }
    }


    function generateMaintenanceRequest(countdownElem, callback) {
        const row = countdownElem.closest('tr');
        const occurrencesElem = row.querySelector('.occurrences');
        const endsElem = row.querySelector('.ends');

        const occurrences = parseInt(occurrencesElem.innerText);
        const ends = parseInt(endsElem.getAttribute('data-ends'));

        console.log(`Generating request: Occurrences: ${occurrences}, Ends: ${ends}`);

        // If occurrences >= ends, stop and don't make a request
        if (occurrences >= ends) {
            countdownElem.innerHTML = "Maintenance Completed";
            console.log('Occurrences >= Ends, stopping requests');
            clearInterval(countdownElem.interval); // Stop the countdown when maintenance is completed
            return;
        }

        // Send the request to the backend to increment occurrences and generate the maintenance request
        fetch('{{ route("admin.run-maintenance-check") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    asset_key: row.getAttribute('data-asset-key'),
                    occurrences: occurrences + 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    occurrencesElem.innerText = occurrences + 1;

                    // Show "Maintenance Generated" message
                    countdownElem.innerHTML = "Maintenance Generated";
                    console.log('Maintenance request generated and occurrences incremented');

                    setTimeout(() => {
                        callback(); // Reset countdown after showing the message
                    }, 2000); // Show "Maintenance Generated" for 2 seconds

                } else {
                    console.error('Error:', data.error);
                    countdownElem.isRequestPending = false; // Reset flag on error
                }
            })
            .catch(error => {
                console.error('Error generating maintenance request:', error);
                countdownElem.isRequestPending = false; // Reset flag on error
            });
    }

    function resetCountdown(countdownElem) {
        const setTime = 10; // Set to 10 seconds for testing, adjust to 86400 (1 day) for actual
        const nextMaintenanceDate = new Date(new Date().getTime() + setTime * 1000); // Reset countdown
        console.log('Resetting countdown for the next cycle');

        // Clear existing interval
        if (countdownElem.interval) {
            clearInterval(countdownElem.interval);
        }

        // Start a new countdown
        countdownElem.interval = setInterval(() => {
            updateCountdown(countdownElem, nextMaintenanceDate);
        }, 1000); // Update every second
    }
</script>


@endsection
