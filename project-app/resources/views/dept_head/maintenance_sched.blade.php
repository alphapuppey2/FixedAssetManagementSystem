<!-- resources/views/dept_head/maintenance_sched.blade.php -->
@extends('layouts.app')

@section('header')
    <h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Maintenance</h2>
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
                <a href="" class="p-2 text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                    </svg>
                </a>
                <a href="{{ route('formMaintenance') }}" class="px-3 py-1 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 focus:outline-none flex items-center">
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
                    <a href="{{ route('maintenance_sched') }}" class="inline-block px-4 py-2 {{ $tab === 'preventive' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Preventive Maintenance</a>
                </li>
                <li class="mr-4">
                    <a href="{{ route('maintenance_sched.predictive') }}" class="inline-block px-4 py-2 {{ $tab === 'predictive' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Predictive Maintenance</a>
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
                                    <td class="px-6 py-4">Every {{ $record->frequency }} days</td>
                                    <td class="ends px-6 py-4">After {{ $record->ends }} occurrence(s)</td>
                                    <td class="occurrences px-6 py-4">{{ $record->occurrences }}</td>
                                    <td class="status px-6 py-4">{{ ucfirst($record->status) }}</td>
                                    <td class="next-maintenance px-6 py-4" id="next-maintenance-{{ $loop->index }}"
                                        data-next-maintenance="{{ $record->next_maintenance_timestamp ?? 0 }}">
                                        Loading...
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
    document.addEventListener("DOMContentLoaded", function() {
        const checkInterval = 1000;  // Check every 1 second

        function updateCountdowns() {
            document.querySelectorAll('[id^="next-maintenance-"]').forEach(function(countdownElem) {
                let nextMaintenanceTimestamp = parseInt(countdownElem.getAttribute('data-next-maintenance'), 10);
                console.log('Next Maintenance Timestamp:', nextMaintenanceTimestamp);
                let currentTimestamp = Math.floor(Date.now() / 1000);  // Get current timestamp in seconds
                let totalSeconds = nextMaintenanceTimestamp - currentTimestamp;
                let occurrencesElem = countdownElem.closest('tr').querySelector('.occurrences');
                let endsElem = countdownElem.closest('tr').querySelector('.ends');
                let statusElem = countdownElem.closest('tr').querySelector('.status');

                if (isNaN(totalSeconds)) {
                    console.error('Invalid countdown data');
                    return;
                }

                // Stop the countdown if occurrences are equal to or greater than ends
                if (parseInt(occurrencesElem.innerText) >= parseInt(endsElem.innerText)) {
                    countdownElem.innerHTML = "Maintenance Completed";
                    statusElem.innerHTML = "Completed";
                    return;  // Prevent further countdown
                }

                if (totalSeconds <= 0) {
                    countdownElem.innerHTML = "Maintenance Due";
                    triggerMaintenanceCheck(countdownElem);  // Trigger maintenance logic
                } else {
                    // Update countdown with the remaining time
                    let days = Math.floor(totalSeconds / (3600 * 24));
                    let hours = Math.floor((totalSeconds % (3600 * 24)) / 3600);
                    let minutes = Math.floor((totalSeconds % 3600) / 60);
                    let seconds = totalSeconds % 60;

                    countdownElem.innerHTML =
                        days + ' days ' +
                        String(hours).padStart(2, '0') + ':' +
                        String(minutes).padStart(2, '0') + ':' +
                        String(seconds).padStart(2, '0');
                }
            });
        }

        setInterval(updateCountdowns, checkInterval);
    });

    function triggerMaintenanceCheck(countdownElem) {
        let occurrencesElem = countdownElem.closest('tr').querySelector('.occurrences');
        let endsElem = countdownElem.closest('tr').querySelector('.ends');
        let statusElem = countdownElem.closest('tr').querySelector('.status');


        // Check if the required elements exist
        if (!occurrencesElem || !endsElem || !statusElem) {
            console.error('Required element is missing in the row.');
            return;
        }

        // Stop if occurrences have reached or exceeded the ends value
        if (parseInt(occurrencesElem.innerText) >= parseInt(endsElem.innerText)) {
            countdownElem.innerHTML = "Maintenance Completed";
            statusElem.innerHTML = "Completed";
            return;  // Prevent further increments and stop maintenance checks
        }

        // Log asset_key to check its value
        console.log('Asset Key:', countdownElem.closest('tr').getAttribute('data-asset-key'));

        // Logic for generating maintenance requests and incrementing occurrences
        fetch('{{ route("run-maintenance-check") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                asset_key: countdownElem.getAttribute('data-asset-key'),
                occurrences: parseInt(occurrencesElem.innerText) + 1  // Increment occurrences
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                occurrencesElem.innerText = parseInt(occurrencesElem.innerText) + 1;
                console.log('Maintenance request generated:', data);
            } else {
                console.error('Error:', data.error);
            }
        })
        .catch(error => {
            console.error('Error generating maintenance request:', error);
        });
    }
</script>


@endsection
