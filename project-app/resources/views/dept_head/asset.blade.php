@extends('layouts.app')

@section('header')
<div class="header flex w-full justify-between pr-3 pl-3 items-center">
    <div class="title">
        <a href="{{ route('asset') }}">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Asset</h2>
        </a>
    </div>

</div>
@endsection

@section('content')
<div class="flex justify-between items-center mb-2">
    <div class="relative searchBox w-full max-w-md ml-2">
        <form action="{{ route('assets.search') }}" method="GET" id="searchForm" class="relative flex items-center">
            <!-- Filter Button Inside Search Input -->
            <button type="button" id="openFilterModalBtn" class="absolute inset-y-0 left-0 flex items-center pl-3 focus:outline-none">
                <x-icons.filter-icon class="w-5 h-5 text-gray-600" />
            </button>

            <!-- Search Input Field -->
            <x-text-input
                name="search"
                id="searchFilt"
                placeholder="Search by Code, Name, Category, etc..."
                value="{{ request('search') }}"
                class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1 sm:text-sm" />
        </form>
    </div>

    <div class="header-R flex items-center space-x-0.5">
        <!-- Refresh Button -->
        <form action="{{ route('asset') }}" method="GET" class="flex">
            <button type="submit" class="p-0.5 rounded-md hover:bg-gray-100 focus:outline-none">
                <x-icons.refresh-icon class="w-5 h-5 text-gray-600" />
            </button>
        </form>

        <!-- Import Button -->
        <button id="openModalBtn" class="p-0.5 rounded-md hover:bg-gray-100 focus:outline-none">
            <x-icons.importIcon />
        </button>

        <!-- Export Button -->
        {{-- <button class="p-0.5 rounded-md hover:bg-gray-100 focus:outline-none">
            <x-icons.exportIcon />
        </button> --}}

    </div>
</div>

<div class="flex justify-between items-center mb-2">
    <!-- Rows per page dropdown -->
    <div class="flex items-center">
        <label for="rows_per_page" class="mr-2 text-gray-700">Rows per page:</label>
        <form action="{{ route('asset') }}" method="GET" id="rowsPerPageForm" class="flex items-center">
            <!-- Preserve current filter and search parameters -->
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="sort" value="{{ request('sort', 'code') }}">
            <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="category" value="{{ request('category') }}">

            <!-- Rows per page select -->
            <select name="rows_per_page" id="rows_per_page"
                class="border rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                onchange="document.getElementById('rowsPerPageForm').submit()">
                <option value="5" {{ request('rows_per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('rows_per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('rows_per_page') == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('rows_per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
        </form>
    </div>

    <!-- Pagination -->
    {{-- <div class="ml-auto pagination-container">
        <div class="flex items-center space-x-4 mt-4">
        <span class="text-gray-600">
            Showing {{ $assets->firstItem() }} to {{ $assets->lastItem() }} of {{ $assets->total() }} assets
        </span>
        {{ $assets->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
        </div>
    </div> --}}

    <div class="flex items-center justify-between mt-4 flex-col md:flex-row space-x-4 md:space-y-0">
        <span class="text-gray-600 hidden md:block">
            Showing {{ $assets->firstItem() }} to {{ $assets->lastItem() }} of {{ $assets->total() }} assets
        </span>
        <div class="text-sm md:text-base">
            @if ($assets->hasPages())
                <div class="md:hidden md:hidden text-xs flex justify-center space-x-1 mt-2" >
                    {{ $assets->appends(request()->query())->links() }}
                </div>
                <div class="hidden md:block">
                    {{ $assets->appends(request()->query())->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>

</div>

<div class="ccAL relative flex flex-col bg-white border rounded-lg w-full h-full overflow-hidden p-[2px]">
    {{-- <div class="tableContainer overflow-auto rounded-md h-full w-full"> --}}
    <div class="hidden md:block tableContainer overflow-auto rounded-md h-full w-full">
        <table class="w-full  border-gray-300">
            <thead class="p-5 bg-gray-100 border-b">
                <tr>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                        <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'code', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-center gap-1">
                            Code
                            <x-icons.sort-icon :direction="request('sort') === 'code' ? request('direction') : null" />
                        </a>
                    </th>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                        <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-center gap-1">
                            Name
                            <x-icons.sort-icon :direction="request('sort') === 'name' ? request('direction') : null" />
                        </a>
                    </th>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                        <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'category_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-center gap-1">
                            Category
                            <x-icons.sort-icon :direction="request('sort') === 'category_name' ? request('direction') : null" />
                        </a>
                    </th>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                        <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-center gap-1">
                            Status
                            <x-icons.sort-icon :direction="request('sort') === 'status' ? request('direction') : null" />
                        </a>
                    </th>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assets as $asset)
                    <tr>
                        <td class="align-middle text-center text-sm text-gray-900">{{ $asset->code ?? 'NONE' }}</td>
                        <td class="align-middle text-center text-sm text-gray-900">{{ $asset->name }}</td>
                        <td class="align-middle text-center text-sm text-gray-900">{{ $asset->category_name }}</td>
                        <td class="align-middle text-center text-sm text-gray-900">@include('components.asset-status', ['status' => $asset->status])</td>
                        <td class="w-40">
                            <div class="flex gap-2 justify-center">
                                <a href="{{ route('assetDetails', $asset->code) }}" class="inline-flex items-center w-8 h-8">
                                    <x-icons.view-icon class="text-blue-900 hover:text-blue-700 w-6 h-6" />
                                </a>
                                <button type="button" class="inline-flex items-center w-8 h-8" onclick="openDeleteModal('{{ $asset->id }}')">
                                    <x-icons.cancel-icon class="text-red-500 hover:text-red-600 w-6 h-6" />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="bg-gray-100 text-center py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">No Assets</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Card layout for small screens -->
    <div class="block md:hidden space-y-4">
        {{-- Changed: Added 'block md:hidden' to display cards only on small screens --}}
        @forelse ($assets as $asset)
            <div class="bg-white shadow-md rounded-lg p-4">
                <p class="text-xs"><strong>Code:</strong> {{ $asset->code ?? 'NONE' }}</p>
                <p class="text-xs"><strong>Name:</strong> {{ $asset->name }}</p>
                <p class="text-xs"><strong>Category:</strong> {{ $asset->category_name }}</p>
                <p class="text-xs"><strong>Status:</strong>
                    @include('components.asset-status', ['status' => $asset->status])
                </p>
                <div class="flex justify-end space-x-2">
                    <a href="{{ route('assetDetails', $asset->code) }}" class="text-blue-900 hover:text-blue-700">
                        <x-icons.view-icon class="w-6 h-6" />
                        {{-- Changed: Adjusted icon size to 'w-5 h-5' for smaller screens --}}
                    </a>
                    <button type="button" onclick="openDeleteModal('{{ $asset->id }}')">
                        <x-icons.cancel-icon class="text-red-500 hover:text-red-600 w-6 h-6" />
                        {{-- Changed: Adjusted icon size to 'w-5 h-5' for smaller screens --}}
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-gray-100 p-4 rounded-lg text-center text-xs text-gray-500">
                {{-- Changed: Adjusted text size to 'text-xs' for consistency --}}
                No assets found.
            </div>
        @endforelse
    </div>

</div>

@include('dept_head.modal.modalImportAsset')
@include('dept_head.modal.filterAssetTable')
@include('dept_head.modal.deleteAssetModal')

<script>
    //Filter Modal Script
        document.getElementById('openFilterModalBtn').addEventListener('click', function () {
            document.getElementById('filterModal').classList.remove('hidden'); // Show the modal
        });

        document.getElementById('closeFilterModalBtn').addEventListener('click', function () {
            document.getElementById('filterModal').classList.add('hidden'); // Hide the modal
        });


    //Delete Modal Script
        function openDeleteModal(assetId) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/asset/delete/${assetId}`; // Set the form action with the asset ID
            console.log(`Delete form action: ${deleteForm.action}`); // For debugging
            document.getElementById('deleteModal').classList.remove('hidden'); // Show the modal
        }


        document.getElementById('cancelDeleteBtn').addEventListener('click', () => {
            document.getElementById('deleteModal').classList.add('hidden'); // Hide the modal
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
            document.getElementById('deleteForm').submit(); // Submit the form
        });


    //Import Modal Script
        document.addEventListener('DOMContentLoaded', function () {
            const modalId = 'importModal';
            document.getElementById('openModalBtn').addEventListener('click', () => openModal(modalId));
        });

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>

@endsection

