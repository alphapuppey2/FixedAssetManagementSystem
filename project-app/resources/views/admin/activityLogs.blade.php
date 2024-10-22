@extends('layouts.app')

@section('header')
<div class="header flex w-full justify-between pr-3 pl-3 items-center">
    <div class="title">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Activity Logs</h2>
    </div>
    <div class="header-R flex items-center space-x-4">
        <div class="relative">
            <!-- Export Button with Dropdown -->
            <button 
                onclick="toggleExportDropdown()">
                <x-icons.exportIcon />
            </button>

            <!-- Dropdown Options -->
            <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                <a href="{{ route('activityLogs.export', ['format' => 'csv']) }}" 
                   class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">
                    Export as CSV
                </a>
                <a href="{{ route('activityLogs.export', ['format' => 'pdf']) }}" 
                   class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">
                    Export as PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="w-full px-8 mt-4">
    <div>
        <form method="GET" action="{{ route('searchActivity') }}" class="flex flex-col space-y-4">
            <!-- Search Input and Button -->
            <div class="relative search-container">
                <x-search-input
                    placeholder="Search by activity or description"
                    class="w-72" />
            </div>

            <div class="flex justify-between items-center mb-4">
                <!-- Rows per page dropdown (Left) -->
                <div class="flex items-center space-x-2">
                    <label for="perPage">Rows per page: </label>
                    <select name="perPage" id="perPage" class="border border-gray-300 rounded px-2 py-1 w-16" onchange="this.form.submit()">
                        <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <!-- Pagination Links and Showing Results (Right) -->
                @if($logs->hasPages())
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} items</span>
                    <div>
                        {{ $logs->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="p-2 border">Activity</th>
                    <th class="p-2 border">Description</th>
                    <th class="p-2 border">User Role</th>
                    <th class="p-2 border">User ID</th>
                    <th class="p-2 border">Asset ID</th>
                    <th class="p-2 border">Request ID</th>
                    <th class="p-2 border">Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                <tr class="hover:bg-gray-100 transition">
                    <td class="p-2 border">{{ $log->activity }}</td>
                    <td class="p-2 border">{{ $log->description }}</td>
                    <td class="p-2 border">
                        @switch($log->userType)
                        @case('admin')
                        Admin
                        @break
                        @case('dept_head')
                        Department Head
                        @break
                        @default
                        System
                        @endswitch
                    </td>
                    <td class="p-2 border">{{ $log->user_id ?? 'System' }}</td>
                    <td class="p-2 border">{{ $log->asset_id ?? 'N/A' }}</td>
                    <td class="p-2 border">{{ $log->request_id ?? 'N/A' }}</td>
                    <td class="p-2 border">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center p-4">No activity logs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleExportDropdown() {
        const dropdown = document.getElementById('exportDropdown');
        dropdown.classList.toggle('hidden');
    }
</script>
@endsection