<!-- resources/views/dept_head/reports/generatedMaintenanceReport.blade.php -->
@extends('layouts.app')

@section('header')
<h2 class="my-3 font-semibold text-xl md:text-2xl text-black-800 leading-tight text-center md:text-left">
    Maintenance Report Results
</h2>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-between">
    <div class="flex-grow overflow-y-auto p-4 md:p-6 bg-white shadow-lg rounded-lg">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 md:mb-6">
            <h3 class="text-lg md:text-2xl font-semibold mb-2 md:mb-0">
                Report Results
            </h3>
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                <a href="{{ route('maintenance.report') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-md shadow text-center">
                    Back to Report Generator
                </a>

                <!-- Download Button with Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown()"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded-md shadow">
                        Download
                    </button>

                    <div id="dropdownMenu" class="absolute right-0 mt-2 w-40 bg-white border rounded-md shadow-lg hidden">
                        <a href="{{ route('maintenance.report.download', array_merge(request()->query(), ['type' => 'csv'])) }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Download CSV
                        </a>
                        <a href="{{ route('maintenance.report.download', array_merge(request()->query(), ['type' => 'pdf'])) }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Download PDF
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Table Wrapper with Horizontal Scroll -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] md:min-w-[800px] table-auto border-collapse bg-white border border-gray-300">
                <thead class="bg-blue-500 text-white uppercase text-xs md:text-sm">
                    <tr>
                        @foreach ($fields as $field)
                        <th class="px-4 md:px-8 py-2 md:py-4 text-left border-b border-gray-300">
                            @switch($field)
                            @case('asset_key')
                            Asset Name
                            @break
                            @default
                            {{ ucfirst(str_replace('_', ' ', $field)) }}
                            @endswitch
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($maintenanceRecords as $record)
                    <tr class="hover:bg-gray-100">
                        @foreach ($fields as $field)
                        <td class="px-4 md:px-8 py-2 md:py-4 border-b border-gray-300">
                            @switch($field)
                            @case('authorized_by')
                            {{ $record->authorized_by_name ?? 'N/A' }}
                            @break
                            @case('requestor')
                            {{ $record->requestor_name ?? 'N/A' }}
                            @break
                            @case('asset_key')
                            {{ $record->asset_name ?? 'N/A' }}
                            @break
                            @default
                            {{ $record->$field ?? 'N/A' }}
                            @endswitch
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($fields) }}" class="text-center py-6 text-gray-500">
                            No maintenance records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="pagination-container" class="mt-4 flex justify-center md:justify-end flex-wrap space-x-1"></div>
    </div>
</div>

<!-- Dropdown Script -->
<script>
    function toggleDropdown() {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    }

    // Close the dropdown if clicked outside
    window.addEventListener('click', function(e) {
        const menu = document.getElementById('dropdownMenu');
        if (!menu.contains(e.target) && !e.target.closest('button')) {
            menu.classList.add('hidden');
        }
    });

    // Render pagination based on screen size
    function renderPagination() {
        const paginationContainer = document.getElementById('pagination-container');
        const isSmallScreen = window.innerWidth < 768;

        paginationContainer.innerHTML = isSmallScreen ?
            `{!! $maintenanceRecords->links() !!}` :
            `{!! $maintenanceRecords->links('vendor.pagination.tailwind') !!}`;
    }

    // Initial render and resize listener
    window.addEventListener('load', renderPagination);
    window.addEventListener('resize', renderPagination);
</script>
@endsection