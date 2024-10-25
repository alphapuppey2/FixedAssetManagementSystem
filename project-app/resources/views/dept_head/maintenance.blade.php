<!-- resources/views/dept_head/maintenance.blade.php -->
@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight text-center md:text-left">
    {{ 'Maintenance Request' }}
</h2>
@endsection

@section('content')
<div class="">
    <!-- Top Section -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-4 md:space-y-0">

        <!-- Search Bar -->
        <div class="relative searchBox w-full max-w-md ml-2">
            <form action="{{ route('maintenance.search') }}" method="GET" id="searchForm" class="relative flex items-center">
                <input type="hidden" name="rows_per_page" value="{{ request('perPage') }}">
                <!-- Filter Button Inside Search Input -->
                <button type="button" id="openFilterModalBtn" class="absolute inset-y-0 left-0 flex items-center pl-3 focus:outline-none">
                    <x-icons.filter-icon class="w-5 h-5 text-gray-600" />
                </button>

                <!-- Search Input Field -->
                <x-text-input
                    name="query"
                    id="searchFilt"
                    placeholder="Search by ID, Requestor, Asset Code, Description"
                    value="{{ request('query') }}"
                    class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1 sm:text-sm" />
            </form>
        </div>

        <!-- Right Section: Icons and Button -->
        <div class="w-full md:w-auto">
            <div class="flex items-center space-x-1 md:space-x-4">
                <!-- Refresh Icon -->
                <form action="{{ route(Route::currentRouteName()) }}" method="GET">
                    <input type="hidden" name="tab" value="{{ $tab }}"> <!-- Preserve the current tab -->
                    <input type="hidden" name="query"> <!-- Preserve the current search query -->
                    <button id="refreshButton" class="p-2 text-black flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </form>

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
            <form action="{{ route(Route::currentRouteName()) }}" method="GET" id="rowsPerPageForm">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="hidden" name="query" value="{{ request('query') }}">
                <input type="hidden" name="sort_by" value="{{ $sortBy }}"> <!-- Preserve sorting -->
                <input type="hidden" name="sort_order" value="{{ $sortOrder }}"> <!-- Preserve sort order -->
                <select name="rows_per_page" id="rows_per_page" class="border rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="document.getElementById('rowsPerPageForm').submit()">
                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                </select>
            </form>
        </div>
        <!-- Pagination (on the right) -->
        <div class="ml-auto pagination-container flex items-center space-x-4">
            <span class="text-gray-600">
                Showing
                {{ $requests->firstItem() ?? 0 }}
                to
                {{ $requests->lastItem() ?? 0 }}
                of {{ $requests->total() }} requests
            </span>

            {{ $requests->appends([
                    'rows_per_page' => $perPage,
                    'tab' => $tab,
                    'query' => request('query'),
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder
                ])->links('vendor.pagination.tailwind') }}
        </div>

    </div>

    <!-- Tabs Section -->
    <div class="mb-4 flex justify-end">
        <ul class="flex border-b">
            <li class="mr-4">
                <a href="{{ route('maintenance', ['rows_per_page' => $perPage]) }}"
                    class="inline-block px-4 py-2 {{ $tab === 'requests' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                    Requests
                </a>
            </li>
            <li class="mr-4">
                <a href="{{ route('maintenance.approved', ['rows_per_page' => $perPage]) }}"
                    class="inline-block px-4 py-2 {{ $tab === 'approved' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                    Approved
                </a>
            </li>
            <li class="mr-4">
                <a href="{{ route('maintenance.denied', ['rows_per_page' => $perPage]) }}"
                    class="inline-block px-4 py-2 {{ $tab === 'denied' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                    Denied
                </a>
            </li>
        </ul>
    </div>



    <!-- Table Section -->
    <div class="overflow-x-auto hidden md:block">
        <table class="min-w-full bg-white border rounded-md">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort_by' => 'maintenance.id', 'sort_order' => $sortBy === 'maintenance.id' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Request ID
                            <x-icons.sort-icon :direction="$sortBy === 'maintenance.id' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort_by' => 'requestor_name', 'sort_order' => $sortBy === 'requestor_name' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Requestor
                            <x-icons.sort-icon :direction="$sortBy === 'requestor_name' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort_by' => 'asset_code', 'sort_order' => $sortBy === 'asset_code' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Asset Code
                            <x-icons.sort-icon :direction="$sortBy === 'asset_code' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort_by' => 'description', 'sort_order' => $sortBy === 'description' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Description
                            <x-icons.sort-icon :direction="$sortBy === 'description' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort_by' => 'category_name', 'sort_order' => $sortBy === 'category_name' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Category
                            <x-icons.sort-icon :direction="$sortBy === 'category_name' ? $sortOrder : null" />
                        </a>
                    </th>

                    @if($tab === 'approved')
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort_by' => 'type', 'sort_order' => $sortBy === 'type' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Type
                            <x-icons.sort-icon :direction="$sortBy === 'type' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Approved By
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort_by' => 'authorized_at', 'sort_order' => $sortBy === 'authorized_at' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Approved At
                            <x-icons.sort-icon :direction="$sortBy === 'authorized_at' ? $sortOrder : null" />
                        </a>
                    </th>
                    @elseif($tab === 'denied')
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Denied By
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort_by' => 'authorized_at', 'sort_order' => $sortBy === 'authorized_at' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Denied At
                            <x-icons.sort-icon :direction="$sortBy === 'authorized_at' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Reason
                    </th>
                    @else
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort_by' => 'location_name', 'sort_order' => $sortBy === 'location_name' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Location
                            <x-icons.sort-icon :direction="$sortBy === 'location_name' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort_by' => 'requested_at', 'sort_order' => $sortBy === 'requested_at' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Requested At
                            <x-icons.sort-icon :direction="$sortBy === 'requested_at' ? $sortOrder : null" />
                        </a>
                    </th>
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

    <!-- Card View for Small Screens -->
    <!-- Added a card layout for small screens (hidden on large screens) -->
    <div class="block md:hidden space-y-4">
        @forelse($requests as $maintenance)
        <div class="bg-white shadow-md rounded-md p-4">
            <h3 class="font-bold text-lg mb-2">Request ID: {{ $maintenance->id ?? 'N/A'}}</h3>
            <p><strong>Requestor:</strong> {{ $maintenance->requestor ? $maintenance->requestor_name : 'System-generated' }}</p>
            <p><strong>Asset Code:</strong> {{ $maintenance->asset_code ?? 'N/A'}}</p>
            <p><strong>Description:</strong> {{ $maintenance->description ?? 'N/A'}}</p>
            <p><strong>Category:</strong> {{ $maintenance->category_name ?? 'N/A'}}</p>

            <!-- Conditional fields based on tab -->
            @if($tab === 'approved')
            <p><strong>Type:</strong> {{ ucfirst($maintenance->type ?? 'N/A') }}</p>
            <p><strong>Approved By:</strong> {{ $maintenance->authorized_by ? $maintenance->authorized_by_name : 'System-generated' }}</p>
            <p><strong>Approved At:</strong> {{ \Carbon\Carbon::parse($maintenance->authorized_at)->format('Y-m-d h:i A') ?? 'N/A' }}</p>
            @elseif($tab === 'denied')
            <p><strong>Denied By:</strong> {{ $maintenance->denied_by_name ?? 'N/A'}}</p>
            <p><strong>Denied At:</strong> {{ \Carbon\Carbon::parse($maintenance->authorized_at)->format('Y-m-d h:i A') ?? 'N/A' }}</p>
            <p><strong>Reason:</strong> {{ $maintenance->reason ?? 'N/A'}}</p>
            @else
            <p><strong>Location:</strong> {{ $maintenance->location_name ?? 'N/A'}}</p>
            <p><strong>Requested At:</strong> {{ \Carbon\Carbon::parse($maintenance->requested_at)->format('Y-m-d h:i A') ?? 'N/A' }}</p>
            @endif

            <!-- Actions -->
            <div class="mt-4">
                @if($tab === 'requests')
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
                <a href="javascript:void(0)" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 block w-full text-center" onclick="openEditModal({{ $maintenance->id }})">Edit (Approved)</a>
                @elseif($tab === 'denied')
                <a href="javascript:void(0)" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 block w-full text-center" onclick="openEditDeniedModal({{ $maintenance->id }})">Edit (Denied)</a>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center text-gray-500">No maintenance requests found.</div>
        @endforelse
    </div>
</div>

<!-- Edit Approve Modal -->
<div id="editApproveModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 md:p-0 hidden">
    <div id="modalContentApprove"
        class="bg-white rounded-lg w-full max-w-md md:max-w-2xl p-6 shadow-lg">
        <!-- Content will be dynamically injected here via AJAX -->
    </div>
</div>

<!-- Modal Structure for Approve -->
<div id="approveModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 md:p-0 hidden">
    <div class="bg-white rounded-lg w-full max-w-sm md:max-w-lg p-6 shadow-lg">
        <!-- Responsive Text Alignment -->
        <h2 class="text-lg font-semibold mb-4 sm:text-center md:text-left">
            Approve Maintenance Request
        </h2>
        <p class="sm:text-center md:text-left">
            Are you sure you want to approve this request?
        </p>

        <!-- Responsive Button Layout -->
        <div class="mt-4 flex flex-col md:flex-row justify-end md:space-x-2 space-y-2 md:space-y-0">
            <button id="confirmApproveBtn"
                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Approve
            </button>
            <button id="cancelApproveBtn"
                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                Cancel
            </button>
        </div>
    </div>
</div>


<!-- Edit Denied Modal -->
<div id="editDeniedModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 md:p-0 hidden">
    <div id="modalContentDenied"
        class="bg-white rounded-lg w-full max-w-md md:max-w-2xl p-6 shadow-lg">
        <!-- Content will be dynamically injected here via AJAX -->
    </div>
</div>

<!-- Modal Structure for Deny -->
<div id="denyModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 md:p-0 hidden">
    <div class="bg-white rounded-lg w-full max-w-sm md:max-w-lg p-6 shadow-lg">
        <!-- Responsive Heading -->
        <h2 class="text-lg font-semibold mb-4 sm:text-center md:text-left">
            Deny Maintenance Request
        </h2>

        <form id="denyForm" action="" method="POST">
            @csrf
            <!-- Responsive Label and Input -->
            <label for="reason" class="block text-sm font-medium text-gray-700 sm:text-center md:text-left">
                Reason
            </label>
            <input type="text" name="reason" id="reason"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                required>

            <!-- Responsive Button Layout -->
            <div class="mt-4 flex flex-col md:flex-row justify-end md:space-x-2 space-y-2 md:space-y-0">
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Save
                </button>
                <button type="button" id="cancelDenyBtn"
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

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


<!-- Toast Notification -->
@if(session('status'))
<div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
    {{ session('status') }}
</div>
@endif

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Approve Modal
        const approveButtons = document.querySelectorAll('.approveButton');
        const approveModal = document.getElementById('approveModal');
        const confirmApproveBtn = document.getElementById('confirmApproveBtn');
        const cancelApproveBtn = document.getElementById('cancelApproveBtn');
        let formToSubmit = null;

        approveButtons.forEach(button => {
            button.addEventListener('click', function() {
                const maintenanceId = this.getAttribute('data-id');
                formToSubmit = document.getElementById(`approveForm_${maintenanceId}`);
                approveModal.classList.remove('hidden');
            });
        });

        cancelApproveBtn.addEventListener('click', function() {
            approveModal.classList.add('hidden');
            formToSubmit = null;
        });

        confirmApproveBtn.addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });

        // Hide approve modal when clicking outside
        window.addEventListener('click', function(e) {
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
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const formAction = this.dataset.action;
                denyForm.action = formAction;
                denyModal.classList.remove('hidden');
            });
        });

        cancelDenyBtn.addEventListener('click', function() {
            denyModal.classList.add('hidden');
        });

        // Hide deny modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === denyModal) {
                denyModal.classList.add('hidden');
            }
        });

        // Optional: Close modals with the Escape key
        document.addEventListener('keyup', function(e) {
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
            url: `{{ url('maintenance') }}/${maintenanceId}/editApproved`,
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