@extends('layouts.app')

@section('header')
<div class="header flex w-full justify-between pr-3 pl-3 items-center">
    <div class="title">
        <a href="{{ route('asset') }}">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Asset</h2>
        </a>
    </div>

    <div class="header-R flex items-center">
        <button id="openModalBtn">
            <x-icons.importIcon />
        </button>
        <button>
            <x-icons.exportIcon />
        </button>

        <!-- Search Form -->
        <div class="relative searchBox w-full max-w-lg">
            <form action="{{ route('assets.search') }}" method="GET" id="searchForm" class="relative flex items-center">
                <!-- Filter Button Inside Search Input -->
                <button type="button" id="openFilterModalBtn"
                    class="absolute inset-y-0 left-0 flex items-center pl-3 focus:outline-none">
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

    </div>
</div>
@endsection

@section('content')
<div class="ccAL relative flex flex-col bg-white border rounded-lg w-full h-full overflow-hidden p-[2px]">
    <div class="tableContainer overflow-auto rounded-md h-full w-full">
        <table class="w-full  border-gray-300">
            <thead class="p-5 bg-gray-100 border-b">
                <tr>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                        <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'code', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                           class="flex items-center justify-center gap-1">
                            Code
                            <x-icons.sort-icon :direction="request('sort') === 'code' ? request('direction') : null" />
                        </a>
                    </th>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                        <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                           class="flex items-center justify-center gap-1">
                            Name
                            <x-icons.sort-icon :direction="request('sort') === 'name' ? request('direction') : null" />
                        </a>
                    </th>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                        <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'category_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                           class="flex items-center justify-center gap-1">
                            Category
                            <x-icons.sort-icon :direction="request('sort') === 'category_name' ? request('direction') : null" />
                        </a>
                    </th>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                        <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                           class="flex items-center justify-center gap-1">
                            Status
                            <x-icons.sort-icon :direction="request('sort') === 'status' ? request('direction') : null" />
                        </a>
                    </th>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        Actions
                    </th>
                </tr>
            </thead>


            <tbody id="table-body">
                @forelse ($assets as $asset)
                    <tr>
                        <th class="align-middle text-center text-sm text-gray-900" scope="col">
                            {{ $asset->code ?? 'NONE' }}
                        </th>
                        <td class="align-middle text-center text-sm text-gray-900 py-2 text-balance">
                            {{ $asset->name }}
                        </td>
                        <td class="align-middle text-center text-sm text-gray-900 py-2">
                            {{ $asset->category_name }}
                        </td>
                        <td class="align-middle text-center text-sm text-gray-900 py-2">
                            @include('components.asset-status', ['status' => $asset->status])
                        </td>
                        <td class="w-40">
                            <div class="grp flex gap-2 justify-center">
                                <a href="{{ route('assetDetails', $asset->code) }}"
                                   class="inline-flex items-center w-8 h-8">
                                    <x-icons.view-icon class="text-blue-900 hover:text-blue-700 w-6 h-6" />
                                </a>

                                <!-- Delete Button to Open Modal -->
                             <button type="button"
                                class="inline-flex items-center w-8 h-8 focus:outline-none transition-all duration-200"
                                onclick="openDeleteModal('{{ $asset->id }}')">
                                <x-icons.cancel-icon class="text-red-500 hover:text-red-600 w-6 h-6" />
                            </button>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr >
                        <td colspan="5" class="bg-gray-100 align-middle text-center text-sm text-gray-400 py-2  text-balance">No List</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="page flex justify-between px-4 py-3">
        <div class="paginator">
            @if ($assets instanceof \Illuminate\Pagination\LengthAwarePaginator || $assets instanceof \Illuminate\Pagination\Paginator)
                <div id="pagination">
                    <!-- Number of Items Loaded -->
                    <div class="text-gray-600">
                        Showing <span class="font-semibold">{{ $assets->firstItem() }}</span> to
                        <span class="font-semibold">{{ $assets->lastItem() }}</span> of
                        <span class="font-semibold">{{ $assets->total() }}</span> items
                    </div>

                    <!-- Pagination Buttons -->
                    <div class="text-gray-500">
                        {{ $assets->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>
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
