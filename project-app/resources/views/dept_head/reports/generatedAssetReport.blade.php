<!-- resources/views/dept_head/generatedAssetReport.blade.php -->
@extends('layouts.app')

@section('header')
{{-- <h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Custom Asset Report</h2> --}}
<h2 class="my-3 font-semibold text-xl md:text-2xl text-black-800 leading-tight text-center md:text-left"> <!-- Responsive update -->
    Asset Report
</h2>
@endsection

@section('content')
{{-- <div class="min-h-screen bg-gray-50"> --}}
<div class="min-h-screen bg-gray-50 flex flex-col justify-between">
    {{-- <div class="max-w-full h-full p-6 bg-white shadow-lg rounded-lg"> --}}
    <div class="flex-grow overflow-y-auto p-4 md:p-6 bg-white shadow-lg rounded-lg">
        {{-- <div class="w-full max-w-screen-lg mx-auto h-full p-4 md:p-6 bg-white shadow-lg rounded-lg"> <!-- Responsive update --> --}}

        <!-- Header Section -->
        {{-- <div class="flex justify-between items-center mb-6"> --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 md:mb-6"> <!-- Responsive update -->
            {{-- <h3 class="text-2xl font-semibold">Report Results</h3> --}}
            <h3 class="text-lg md:text-2xl font-semibold mb-2 md:mb-0"> <!-- Responsive update -->
                Asset Report Results
            </h3>
            {{-- <div class="flex space-x-4"> --}}
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4"> <!-- Responsive update -->
                <a href="{{ route('asset.report') }}"
                    {{-- class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-md shadow"> --}}
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400"> <!-- Responsive update -->
                    Back to Report Generator
                </a>

                <!-- Download Button with Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown()"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Download
                    </button>

                    <div id="dropdownMenu"
                        class="absolute right-0 mt-2 w-40 bg-white border rounded-md shadow-lg hidden">
                        <a href="{{ route('asset.report.download', array_merge(request()->query(), ['type' => 'csv'])) }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Download CSV
                        </a>
                        <a href="{{ route('asset.report.download', array_merge(request()->query(), ['type' => 'pdf'])) }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Wrapper with Horizontal Scroll -->
        <div class="overflow-x-auto">
            {{-- <table class="w-full min-w-[800px] table-auto border-collapse bg-white border border-gray-300"> --}}
            <table class="w-full min-w-[600px] md:min-w-[800px] table-auto border-collapse bg-white border border-gray-300"> <!-- Responsive update -->
                {{-- <thead class="bg-blue-500 text-white uppercase text-sm"> --}}
                <thead class="bg-blue-500 text-white uppercase text-xs md:text-sm"> <!-- Responsive update -->
                    <tr>
                        @foreach ($fields as $field)
                        {{-- <th class="px-8 py-4 min-w-[150px] text-left border-b border-gray-300"> --}}
                        <th class="px-4 md:px-8 py-2 md:py-4 text-left border-b border-gray-300 min-w-[100px] md:min-w-[150px]"> <!-- Responsive update -->
                            @switch($field)
                            @case('asst_img')
                            Asset Image
                            @break
                            @case('qr_img')
                            QR Image
                            @break
                            @case('ctg_ID')
                            Category
                            @break
                            @case('dept_ID')
                            Department
                            @break
                            @case('manufacturer_key')
                            Manufacturer
                            @break
                            @case('model_key')
                            Model
                            @break
                            @case('loc_key')
                            Location
                            @break
                            @default
                            {{ ucfirst(str_replace('_', ' ', $field)) }}
                            @endswitch
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($assets as $asset)
                    <tr class="hover:bg-gray-100">
                        @foreach ($fields as $field)
                        {{-- <td class="px-8 py-4 min-w-[150px] border-b border-gray-300"> --}}
                        <td class="px-4 md:px-8 py-2 md:py-4 border-b border-gray-300 min-w-[100px] md:min-w-[150px]"> <!-- Responsive update -->
                            @switch($field)
                            @case('asst_img')
                            <img src="{{ $asset->asst_img ? asset('storage/' . $asset->asst_img) : asset('images/no-image.png') }}"
                                {{-- alt="Asset Image" class="w-20 h-20 object-cover rounded"> --}}
                                alt="Asset Image" class="w-16 h-16 md:w-20 md:h-20 object-cover rounded"> <!-- Responsive update -->
                            @break
                            @case('qr_img')
                            <img src="{{ $asset->qr_img ? asset('storage/' . $asset->qr_img) : asset('images/defaultQR.png') }}"
                                {{-- alt="QR Code" class="w-20 h-20 object-cover rounded"> --}}
                                alt="QR Code" class="w-16 h-16 md:w-20 md:h-20 object-cover rounded"> <!-- Responsive update -->
                            @break
                            @case('ctg_ID')
                            {{ $asset->category_name ?? 'N/A' }}
                            @break
                            @case('dept_ID')
                            {{ $asset->department_name ?? 'N/A' }}
                            @break
                            @case('manufacturer_key')
                            {{ $asset->manufacturer_name ?? 'N/A' }}
                            @break
                            @case('model_key')
                            {{ $asset->model_name ?? 'N/A' }}
                            @break
                            @case('loc_key')
                            {{ $asset->location_name ?? 'N/A' }}
                            @break
                            @case('isDeleted')
                            {{ $asset->isDeleted === 0 ? 'NO' : 'YES' }}
                            @break
                            @default
                            {{ $asset->$field ?? 'N/A' }}
                            @endswitch
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($fields) }}" class="text-center py-6 text-gray-500">
                            No assets found within the selected date range.
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
            `{!! $assets->links() !!}` :
            `{!! $assets->links('vendor.pagination.tailwind') !!}`;
    }

    // Initial render and resize listener
    window.addEventListener('load', renderPagination);
    window.addEventListener('resize', renderPagination);
</script>
@endsection