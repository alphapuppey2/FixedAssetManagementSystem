<!-- resources/views/dept_head/maintenance.blade.php -->
@extends('layouts.app')

@section('header')
    <h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Maintenance</h2>
@endsection

@section('content')
    <div class="px-6 py-4">
        <!-- Top Section -->
        <div class="flex justify-between items-center mb-4">

            <!-- Search Bar -->
            <div class="flex items-center w-1/2">
                <form action="{{ route('maintenance.search') }}" method="GET" class="w-full">
                    <input type="hidden" name="tab" value="{{ $tab }}"> <!-- Include the current tab -->
                    <input type="text" name="query" placeholder="Search..." value="{{ $searchQuery }}" class="w-1/2 px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </form>
            </div>

            <!-- Right Section: Download Icon and Create Button -->
            <div class="flex items-center space-x-4">
                <form action="{{ route(Route::currentRouteName()) }}" method="GET">
                    <input type="hidden" name="tab" value="{{ $tab }}"> <!-- Preserve the current tab -->
                    <input type="hidden" name="query" value="{{ $searchQuery }}"> <!-- Preserve the current search query -->
                    <button id="refreshButton" class="p-2 text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </form>
                <a href="{{ route('maintenance.download', ['tab' => $tab, 'query' => $searchQuery]) }}" class="p-2 text-black">
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
                <form action="{{ route(Route::currentRouteName()) }}" method="GET" id="rowsPerPageForm">
                    <input type="hidden" name="tab" value="{{ $tab }}"> <!-- Preserve the current tab -->
                    <input type="hidden" name="query" value="{{ $searchQuery }}"> <!-- Preserve the current search query -->
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
                {{ $requests->appends(['rows_per_page' => $perPage, 'tab' => $tab, 'query' => $searchQuery])->links() }} <!-- Pagination Links -->
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="mb-4 flex justify-end">
            <ul class="flex border-b">
                <li class="mr-4">
                    <a href="{{ route('maintenance') }}" class="inline-block px-4 py-2 {{ $tab === 'requests' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Requests</a>
                </li>
                <li class="mr-4">
                    <a href="{{ route('maintenance.approved') }}" class="inline-block px-4 py-2 {{ $tab === 'approved' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Approved</a>
                </li>
                <li class="mr-4">
                    <a href="{{ route('maintenance.denied') }}" class="inline-block px-4 py-2 {{ $tab === 'denied' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Denied</a>
                </li>
            </ul>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded-md">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requestor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        @if($tab === 'approved')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved At</th>
                        @elseif($tab === 'denied')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denied By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denied At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        @else
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested At</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-gray-200">
                    @forelse($requests as $maintenance)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->id ?? 'N/A'}}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->requestor ? $maintenance->requestor_name : 'System-generated' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->asset_code ?? 'N/A'}}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->description ?? 'N/A'}}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->category_name ?? 'N/A'}}</td>
                            @if($tab === 'approved')
                                <td class="px-6 py-4 text-sm text-gray-900">{{ ucfirst($maintenance->type ?? 'N/A') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->authorized_by ? $maintenance->authorized_by_name : 'System-generated' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($maintenance->authorized_at)->format('Y-m-d h:i A') ?? 'N/A' }}</td>
                            @elseif($tab === 'denied')
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->denied_by_name ?? 'N/A'}}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($maintenance->authorized_at)->format('Y-m-d h:i A') ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->reason ?? 'N/A'}}</td>
                            @else
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->location_name ?? 'N/A'}}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($maintenance->requested_at)->format('Y-m-d h:i A') ?? 'N/A' }}</td>
                            @endif
                            <!-- Action column for each tab -->
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($tab === 'requests')
                                    <!-- Approve and Deny buttons for Requests tab -->
                                    <form id="approveForm_{{ $maintenance->id }}" action="{{ route('maintenance.approve', $maintenance->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="button" class="approveButton px-2 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600" data-id="{{ $maintenance->id }}">Approve</button>
                                    </form>
                                    <form id="denyForm_{{ $maintenance->id }}" data-id="{{ $maintenance->id }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="reason" value="N/A">
                                        <button type="button" class="denyButton px-2 py-2 bg-red-500 text-white rounded-md hover:bg-red-600" data-action="{{ route('maintenance.deny', $maintenance->id) }}">Deny</button>
                                    </form>
                                @elseif($tab === 'approved')
                                    <a href="javascript:void(0)" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 block w-48 text-center" onclick="openEditModal({{ $maintenance->id }})">Edit (Approved)</a>
                                @elseif($tab === 'denied')
                                    <a href="javascript:void(0)" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 block w-48 text-center" onclick="openEditDeniedModal({{ $maintenance->id }})">Edit (Denied)</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-sm text-gray-500 text-center">No maintenance requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="editApproveModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div id="modalContentApprove" class="bg-white rounded-lg w-1/2 p-6 shadow-lg">
            <!-- Content will be dynamically injected here via AJAX -->
        </div>
    </div>

    <div id="editDeniedModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div id="modalContentDenied" class="bg-white rounded-lg w-1/2 p-6 shadow-lg">
            <!-- Content will be dynamically injected here via AJAX -->
        </div>
    </div>

    <!-- Modal Structure for Approve -->
    <div id="approveModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg w-1/3 p-6 shadow-lg">
            <h2 class="text-lg font-semibold mb-4">Approve Maintenance Request</h2>
            <p>Are you sure you want to approve this request?</p>
            <div class="mt-4 flex justify-end">
                <button id="cancelApproveBtn" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 mr-2">Cancel</button>
                <button id="confirmApproveBtn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Approve</button>
            </div>
        </div>
    </div>

    <!-- Modal Structure for Deny -->
    <div id="denyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg w-1/3 p-6">
            <h2 class="text-lg font-semibold mb-4">Deny Maintenance Request</h2>
            <form id="denyForm" action="" method="POST">
                @csrf
                <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                <input type="text" name="reason" id="reason" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <div class="mt-4 flex justify-end">
                    <button type="button" id="cancelDenyBtn" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 mr-2">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Save</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Toast Notification -->
    @if(session('status'))
    <div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
        {{ session('status') }}
    </div>
    @endif

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Approve Modal
            const approveButtons = document.querySelectorAll('.approveButton');
            const approveModal = document.getElementById('approveModal');
            const confirmApproveBtn = document.getElementById('confirmApproveBtn');
            const cancelApproveBtn = document.getElementById('cancelApproveBtn');
            let formToSubmit = null;

            approveButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const maintenanceId = this.getAttribute('data-id');
                    formToSubmit = document.getElementById(`approveForm_${maintenanceId}`);
                    approveModal.classList.remove('hidden');
                });
            });

            cancelApproveBtn.addEventListener('click', function () {
                approveModal.classList.add('hidden');
                formToSubmit = null;
            });

            confirmApproveBtn.addEventListener('click', function () {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
            });

            // Hide approve modal when clicking outside
            window.addEventListener('click', function (e) {
                if (e.target === approveModal) {
                    approveModal.classList.add('hidden');
                    formToSubmit = null;
                }
            });

            // Deny Modal
            const denyButtons = document.querySelectorAll('.denyButton');
            const denyModal = document.getElementById('denyModal');
            const denyForm = document.getElementById('denyForm');
            const cancelDenyBtn = document.getElementById('cancelDenyBtn');

            denyButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const formAction = this.dataset.action;
                    denyForm.action = formAction;
                    denyModal.classList.remove('hidden');
                });
            });

            cancelDenyBtn.addEventListener('click', function () {
                denyModal.classList.add('hidden');
            });

            // Hide deny modal when clicking outside
            window.addEventListener('click', function (e) {
                if (e.target === denyModal) {
                    denyModal.classList.add('hidden');
                }
            });

            // Optional: Close modals with the Escape key
            document.addEventListener('keyup', function (e) {
                if (e.key === "Escape") {
                    approveModal.classList.add('hidden');
                    denyModal.classList.add('hidden');
                }
            });
        });
    </script>


    <script>
        // Function to open the modal with the AJAX-loaded content
        function openEditDeniedModal(maintenanceId) {
            $.ajax({
                url: `/maintenance/${maintenanceId}/editDenied`,
                type: 'GET',
                success: function(data) {
                    // Inject the modal content into the correct modal container
                    $('#modalContentDenied').html(data);
                    // Show the modal
                    $('#editDeniedModal').removeClass('hidden');
                },
                error: function() {
                    alert('Failed to load the modal.');
                }
            });
        }

        function openEditModal(maintenanceId) {
            $.ajax({
                url: `/maintenance/${maintenanceId}/editApproved`,
                type: 'GET',
                success: function(data) {
                    // Inject the modal content into the correct modal container
                    $('#modalContentApprove').html(data);
                    // Show the modal
                    $('#editApproveModal').removeClass('hidden');
                },
                error: function() {
                    alert('Failed to load the modal.');
                }
            });
        }

        $(document).on('click', function(event) {
            // Check if the click is outside of both modals
            if (!$(event.target).closest('#modalContentApprove, #modalContentDenied').length &&
                !$(event.target).is('.editButton')) {
                closeEditModal();
            }
        });

        // Function to close the modal
        function closeEditModal() {
            $('#editApproveModal').addClass('hidden');
            $('#editDeniedModal').addClass('hidden');
        }

        $(document).keyup(function(e) {
            if (e.key === "Escape") { // escape key maps to keycode `27`
                closeEditModal();
            }
        });

        // Hide the toast after 3 seconds
        setTimeout(function() {
            var toast = document.getElementById('toast');
            if (toast) {
                toast.style.transition = 'opacity 0.5s';
                toast.style.opacity = '0';
                setTimeout(function() {
                    toast.remove();
                }, 500); // Remove after the fade-out transition
            }
        }, 3000); // 3 seconds before hiding

    </script>

@endsection
