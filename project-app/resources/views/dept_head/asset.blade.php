@extends('layouts.app')
@section('header')
<div class="header flex flex-wrap w-full justify-between pr-3 pl-3 items-center gap-2">
    <div class="title">
        <a href="{{ asset('asset') }}">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Asset
            </h2>
        </a>
    </div>
    <div class="header-R flex items-center gap-2">
        <button id="openModalBtn">
            <span><x-icons.importIcon /></span>
        </button>
        <button><span><x-icons.exportIcon /></span></button>
        <div class="searchBox">
            <x-text-input name="search" id="searchFilt" placeholder="Search" />
        </div>
    </div>
</div>
@endsection

@section('content')
@if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<!-- Main Content Wrapper -->
<div class="ccAL relative flex flex-col bg-white border mt-4 rounded-lg w-full h-full overflow-hidden p-[2px]">

    <!-- Table Layout for Larger Screens -->
    <div class="tableContainer overflow-auto rounded-md h-full w-full hidden lg:block">
        <table class="w-full text-center table-fixed">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="py-3 px-2 text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Code
                    </th>
                    <th class="py-3 px-2 text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="py-3 px-2 text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Category
                    </th>
                    <th class="py-3 px-2 text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="py-3 px-2 text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody id="table-body">
                @if (!$asset->isEmpty())
                    @foreach ($asset as $asst)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="py-2 px-2 text-xs sm:text-sm">{{ $asst->code ?? 'NONE' }}</td>
                        <td class="py-2 px-2 text-xs sm:text-sm">{{ $asst->name }}</td>
                        <td class="py-2 px-2 text-xs sm:text-sm">{{ $asst->category }}</td>
                        <td class="py-2 px-2 text-xs sm:text-sm">
                            @include('components.asset-status', ['status' => $asst->status])
                        </td>
                        <td class="py-2 px-2 w-40">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('assetDetails', $asst->code) }}" class="w-6 h-6">
                                    <x-icons.view-icon class="text-blue-900 hover:text-blue-700" />
                                </a>
                                <form action="{{ route('asset.delete', $asst->code) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-6 h-6"
                                        onclick="return confirm('Are you sure you want to delete this asset?');">
                                        <x-icons.cancel-icon class="text-red-500 hover:text-red-600" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr class="text-center text-gray-800">
                        <td colspan="5" class="text-gray-400">No List</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <!-- Card Layout for Small Screens -->
    <div class="grid grid-cols-1 gap-2 lg:hidden">
        @foreach ($asset as $asst)
        <div class="border rounded-lg p-4 shadow-md">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-sm font-semibold">{{ $asst->name }}</h3>
                <div class="flex gap-2">
                    <a href="{{ route('assetDetails', $asst->code) }}" class="w-6 h-6">
                        <x-icons.view-icon class="text-blue-900 hover:text-blue-700" />
                    </a>
                    <form action="{{ route('asset.delete', $asst->code) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-6 h-6"
                            onclick="return confirm('Are you sure you want to delete this asset?');">
                            <x-icons.cancel-icon class="text-red-500 hover:text-red-600" />
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-xs text-gray-600"><strong>Code:</strong> {{ $asst->code ?? 'NONE' }}</p>
            <p class="text-xs text-gray-600"><strong>Category:</strong> {{ $asst->category }}</p>
            <p class="text-xs text-gray-600"><strong>Status:</strong> 
                @include('components.asset-status', ['status' => $asst->status])
            </p>
        </div>
        @endforeach
    </div>

    <!-- Pagination Section -->
    <div class="pagination-container mt-4 w-full bg-white shadow-md lg:mt-auto lg:fixed lg:bottom-0">
        @if ($asset instanceof \Illuminate\Pagination\LengthAwarePaginator || $asset instanceof \Illuminate\Pagination\Paginator)
        <div class="flex flex-col sm:flex-row sm:justify-between items-center gap-2 px-4 py-3">
            <div class="pagination-links w-full sm:w-auto flex justify-center sm:justify-end">
                <div class="text-xs sm:text-sm">
                    {{ $asset->appends(['query' => request()->query('query')])->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>





@include('dept_head.modal.modalImportAsset')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalId = 'importModal';
        document.getElementById('openModalBtn').addEventListener('click', () => openModal(modalId));
        document.getElementById('closeModalBtn').addEventListener('click', () => closeModal(modalId));
        window.addEventListener('click', (e) => closeModalOnClickOutside(modalId, e));

        handleSearch('searchFilt', 'table-body');
    });

    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function closeModalOnClickOutside(modalId, event) {
        if (event.target === document.getElementById(modalId)) {
            closeModal(modalId);
        }
    }

    function handleSearch(inputId, tableBodyId) {
        const input = document.getElementById(inputId);
        input.addEventListener('keyup', function () {
            const query = input.value;
            fetch(`/asset/search/row?search=${query}`, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById(tableBodyId);
                    tableBody.innerHTML = data.length ? data.map(asset => `
                        <tr>
                            <td>${asset.code || 'NONE'}</td>
                            <td>${asset.name}</td>
                            <td>${asset.category}</td>
                            <td>${asset.status}</td>
                        </tr>
                    `).join('') : '<tr><td colspan="5">Asset not found</td></tr>';
                });
        });
    }
</script>
@endsection
