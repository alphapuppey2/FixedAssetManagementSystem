@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight text-center md:text-left">
    {{ 'Maintenance Records' }}
</h2>
@endsection

@section('content')
<div class="">

    <!-- Top Section -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 space-y-2 md:space-y-0">
        <!-- Search Bar -->
        <div class="search-container w-full md:w-1/2 flex items-center">
            <form action="{{ route('adminMaintenanceRecords.search') }}" method="GET" class="w-full">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="hidden" name="rows_per_page" value="{{ $perPage }}">
                <x-search-input class="w-80" placeholder="Search Maintenance..." />
            </form>
        </div>

        <!-- Refresh Button -->
        <div class="w-full md:w-auto">
            <div class="flex items-center space-x-4">
                <form action="{{ route(Route::currentRouteName()) }}" method="GET">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <input type="hidden" name="query" value="{{ $searchQuery }}">
                    <button id="refreshButton" class="p-2 text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-4">
        <!-- Rows per page and pagination -->
        <div class="flex items-center">
            <label for="rows_per_page" class="mr-2 text-gray-700">Rows per page:</label>
            <form action="{{ route(Route::currentRouteName()) }}" method="GET" id="rowsPerPageForm">
                <input type="hidden" name="query" value="{{ $searchQuery }}"> <!-- Preserve search query -->
                <select name="rows_per_page" id="rows_per_page" class="border rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="document.getElementById('rowsPerPageForm').submit()">
                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                </select>
            </form>
        </div>

        {{-- <div class="ml-auto pagination-container">
            {{ $records->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
        </div> --}}

        <div class="flex items-center justify-between mb-4 mt-4 flex-col md:flex-row space-x-4 md:space-y-0 pagination-container">
            <!-- Pagination Info - Only show on medium screens and above -->
            <span class="text-gray-600 hidden md:block">
                Showing {{ $records->firstItem() }} to {{ $records->lastItem() }} of {{ $records->total() }} results
            </span>

            <!-- Pagination Links -->
            <div class="w-full md:w-auto">
                <!-- Use different pagination links based on screen size -->
                <div class="hidden md:block">
                    {{ $records->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                </div>
                <div class="block md:hidden text-sm">
                    {{ $records->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>

    </div>

    <!-- Tabs Section -->
    <div class="mb-4 flex justify-end">
        <ul class="flex border-b">
            <li class="mr-4">
                <a href="{{ route('adminMaintenance.records', ['tab' => 'completed']) }}"
                    class="inline-block px-4 py-2 {{ $tab === 'completed' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                    Completed
                </a>
            </li>
            <li class="mr-4">
                <a href="{{ route('adminMaintenance.records', ['tab' => 'cancelled']) }}"
                    class="inline-block px-4 py-2 {{ $tab === 'cancelled' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                    Cancelled
                </a>
            </li>
        </ul>
    </div>

    <!-- Table Section -->
    {{-- <div class="overflow-x-auto"> --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full bg-white border rounded-md">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Date</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-200">
                @forelse($records as $record)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->id ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->asset_code ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->description ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->category_name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->cost ?? '0.00' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($record->start_date)->format('Y-m-d') ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($record->completion_date)->format('Y-m-d') ?? 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-sm text-gray-500 text-center">No maintenance records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Card Layout for Small Screens -->
    <div class="block md:hidden space-y-4"> <!-- Added card layout for small screens -->
        @foreach($records as $record)
        <div class="bg-white p-4 rounded-md shadow-md border">
            <p><strong>Request ID:</strong> {{ $record->id ?? 'N/A' }}</p>
            <p><strong>Asset Code:</strong> {{ $record->asset_code ?? 'N/A' }}</p>
            <p><strong>Description:</strong> {{ $record->description ?? 'N/A' }}</p>
            <p><strong>Category:</strong> {{ $record->category_name ?? 'N/A' }}</p>
            <p><strong>Cost:</strong> {{ $record->cost ?? '0.00' }}</p>
            <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($record->start_date)->format('Y-m-d') ?? 'N/A' }}</p>
            <p><strong>Completion Date:</strong> {{ \Carbon\Carbon::parse($record->completion_date)->format('Y-m-d') ?? 'N/A' }}</p>
        </div>
        @endforeach
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

<script>
    // Hide toast after 3 seconds
    setTimeout(function() {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.style.transition = 'opacity 0.5s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }
    }, 3000);
</script>
@endsection
