<!-- resources/views/dept_head/generatedReport.blade.php -->
@extends('layouts.app')

@section('header')
<h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Custom Asset Report</h2>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-full h-full p-6 bg-white shadow-lg rounded-lg">

        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold">Report Results</h3>
            <div class="flex space-x-4">
                <a href="{{ route('custom.report') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-md shadow">
                    Back to Report Generator
                </a>

                <!-- Download Button with Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown()"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded-md shadow">
                        Download
                    </button>

                    <div id="dropdownMenu"
                        class="absolute right-0 mt-2 w-40 bg-white border rounded-md shadow-lg hidden">
                        <a href="{{ route('report.download', array_merge(request()->query(), ['type' => 'csv'])) }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Download CSV
                        </a>
                        <a href="{{ route('report.download', array_merge(request()->query(), ['type' => 'pdf'])) }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Wrapper with Horizontal Scroll -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] table-auto border-collapse bg-white border border-gray-300">
                <thead class="bg-blue-500 text-white uppercase text-sm">
                    <tr>
                        @foreach ($fields as $field)
                        <th class="px-8 py-4 min-w-[150px] text-left border-b border-gray-300">
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
                        <td class="px-8 py-4 min-w-[150px] border-b border-gray-300">
                            @switch($field)
                            @case('asst_img')
                            <img src="{{ $asset->asst_img ? asset('storage/' . $asset->asst_img) : asset('images/no-image.png') }}"
                                alt="Asset Image" class="w-20 h-20 object-cover rounded">
                            @break
                            @case('qr_img')
                            <img src="{{ $asset->qr_img ? asset('storage/' . $asset->qr_img) : asset('images/defaultQR.png') }}"
                                alt="QR Code" class="w-20 h-20 object-cover rounded">
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

        <!-- Pagination Links -->
        <div class="mt-6 flex justify-end">
            {{ $assets->links('vendor.pagination.tailwind') }}
        </div>
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
</script>
@endsection