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
<!-- Loading Screen -->
<div id="loadingScreen" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden flex flex-col items-center justify-center z-50">
    <div class="loader mb-4"></div>
    <span class="text-white text-lg font-bold">Uploading, please wait...</span>
</div>

<div class="flex justify-between items-center mb-2">
    <div class="relative searchBox w-full max-w-md ml-2">
        <form action="{{ route('asset') }}" method="GET" id="searchForm" class="relative flex items-center">
            <!-- Filter Button Inside Search Input -->
            <button type="button" id="openFilterModalBtn"
                class="absolute inset-y-0 left-0 flex items-center pl-3 focus:outline-none">
                <x-icons.filter-icon class="w-5 h-5 text-gray-600" />
            </button>

            <!-- Search Input Field -->
            <x-text-input name="search" id="searchFilt" placeholder="Search by Code, Name"
                value="{{ request('search') }}"
                class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1 sm:text-sm" />

            <!-- Retain the filter values as hidden inputs -->
            <input type="hidden" name="sort" value="{{ request('sort', 'code') }}">
            <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
            <input type="hidden" name="rows_per_page" value="{{ request('rows_per_page', 10) }}">

            <!-- Handle status and category as arrays -->
            @foreach ((array) request('status', []) as $status)
            <input type="hidden" name="status[]" value="{{ $status }}">
            @endforeach

            @foreach ((array) request('category', []) as $category)
            <input type="hidden" name="category[]" value="{{ $category }}">
            @endforeach

            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
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

    </div>
</div>

<div class="flex justify-between items-center mb-2">
    <!-- Rows per page dropdown -->
    <div class="flex items-center">
        <label for="rows_per_page" class="mr-2 text-gray-700">Rows per page:</label>
        <form action="{{ route('asset') }}" method="GET" id="rowsPerPageForm" class="flex items-center">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="sort" value="{{ request('sort', 'code') }}">
            <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">

            <!-- Handle status and category as JSON -->
            @foreach ((array) request('status', []) as $status)
            <input type="hidden" name="status[]" value="{{ $status }}">
            @endforeach

            @foreach ((array) request('category', []) as $category)
            <input type="hidden" name="category[]" value="{{ $category }}">
            @endforeach

            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">

            <select name="rows_per_page" id="rows_per_page"
                class="border rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                onchange="document.getElementById('rowsPerPageForm').submit()">
                <option value="5" {{ request('rows_per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('rows_per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('rows_per_page') == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('rows_per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
        </form>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-4 flex-col md:flex-row space-x-4 md:space-y-0">
        <span class="text-gray-600 hidden md:block">
            Showing {{ $assets->firstItem() }} to {{ $assets->lastItem() }} of {{ $assets->total() }} assets
        </span>
        <div class="text-sm md:text-base">
            @if ($assets->hasPages())
            <div class="md:hidden text-xs flex justify-center space-x-1 mt-2">
                {{ $assets->appends(request()->except('page'))->links() }}
            </div>
            <div class="hidden md:block">
                {{ $assets->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
            </div>
            @endif
        </div>
    </div>
</div>

<form action="{{ route('asset.multiDelete') }}" method="POST" id="multiDeleteForm">
    @csrf
    @method('DELETE')

    <!-- Selected Count Display -->
    <div class="mb-2 text-gray-600" id="selectedCountContainer">
        Selected Assets: <span id="selectedCount">0</span>
    </div>

        <div class="flex justify-between items-center mb-2">
            <!-- Multi-Delete Button -->
            <button type="button" onclick="openDeleteModal()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md hidden"
                id="multiDeleteButton">
                Delete Selected
            </button>

            <input type="hidden" name="selected_ids" id="selectedIdsInput">

        @include('dept_head.modal.deleteAssetModal')
    </div>

        <!-- Desktop Table Layout -->
        <div class="hidden md:block tableContainer overflow-auto rounded-md h-full w-full">
            <table class="w-full border border-gray-300 rounded-lg text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="w-12 py-3 text-xs font-medium text-gray-500 uppercase text-center">
                            <input type="checkbox" id="selectAllDesktop" class="w-5 h-5">
                        </th>
                        <th>#</th>
                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'code', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="flex  gap-1">
                                Code
                                <x-icons.sort-icon :direction="request('sort') === 'code' ? request('direction') : null" />
                            </a>
                        </th>
                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                            Image
                        </th>
                        {{-- Name --}}
                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left w-40">
                            <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="flex gap-1">
                                Name
                                <x-icons.sort-icon :direction="request('sort') === 'name' ? request('direction') : null" />
                            </a>
                        </th>
                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                            <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'category_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="flex gap-1">
                                Category
                                <x-icons.sort-icon :direction="request('sort') === 'category_name' ? request('direction') : null" />
                            </a>
                        </th>
                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                            <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'purchase_date', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="flex gap-1">
                                Purchase Date
                                <x-icons.sort-icon :direction="request('sort') === 'purchase_date' ? request('direction') : null" />
                            </a>
                        </th>
                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                            <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'depreciation', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="flex gap-1">
                                Depreciation
                                <x-icons.sort-icon :direction="request('sort') === 'depreciation' ? request('direction') : null" />
                            </a>
                        </th>
                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                            <a href="{{ route('asset', array_merge(request()->except('sort', 'direction'), ['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="flex gap-1">
                                Status
                                <x-icons.sort-icon :direction="request('sort') === 'status' ? request('direction') : null" />
                            </a>
                        </th>
                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assets as $asset)
                        <tr class="hover:bg-gray-100">
                            <td class="text-center">
                                <input type="checkbox" name="asset_ids[]" value="{{ $asset->id }}"
                                    class="assetCheckbox w-5 h-5">
                            </td>
                            <td class="align-middle font-bold text-left text-sm text-gray-900">
                                {{ $loop->iteration }}
                            </td>
                            <td>{{ $asset->code ?? 'NONE' }}</td>
                            <td>
                                <img src="{{ isset($asset->asst_img) ? asset('storage/' . $asset->asst_img) : asset('images/no-image.png') }}"
                                    class="w-12 h-12 shrink" alt="">
                            </td>
                            <td>{{ $asset->name }}</td>
                            <td>{{ $asset->category_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($asset->purchase_date)->format('m/d/Y') }}</td>
                            {{-- <td>{{ $asset->depreciation ?? 0.0 }}</td> --}}
                            <td>{{ number_format($asset->depreciation ?? 0, 2) }}</td>
                            <td>@include('components.asset-status', ['status' => $asset->status])</td>
                            <td class="align-middle font-bold text-left text-sm text-gray-900">
                               <div class="deb flex justify-center">
                                <a href="{{ route('assetDetails', $asset->code) }}" class="text-blue-900">
                                    <x-icons.view-icon class="w-6 h-6" />
                                </a>
                               </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10"
                                class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">No
                                Assets Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card Layout -->
        <div class="block md:hidden space-y-4">
            <div class="flex items-center space-x-2">
                <input type="checkbox" id="selectAllMobile" class="w-5 h-5">
                <span>Select All</span>
            </div>
            @foreach ($assets as $asset)
                <div class="bg-white shadow-md rounded-lg flex gap-2 items-center p-4 contain-card">
                    <input type="checkbox" name="asset_ids[]" value="{{ $asset->id }}"
                        class="assetCheckbox w-5 h-5">
                    <div class="containCard flex w-full justify-between">
                        <div class="details">
                            <p><strong>Code:</strong> {{ $asset->code ?? 'NONE' }}</p>
                            <p><strong>Name:</strong> {{ $asset->name }}</p>
                            <p><strong>Category:</strong> {{ $asset->category_name }}</p>
                            <p><strong>Status:</strong>
                                @include('components.asset-status', ['status' => $asset->status])
                            </p>
                        </div>
                        <a href="{{ route('assetDetails', $asset->code) }}" class="text-blue-900">
                            <x-icons.view-icon class="w-6 h-6" />
                        </a>
                    </div>
                </div>
            @endforeach
        </div>


</form>

@include('dept_head.modal.modalImportAsset')
@include('dept_head.modal.filterAssetTable', ['categoriesList' => $categoriesList])

<!-- Toast Container -->
<div id="toastContainer" class="fixed bottom-5 right-5 space-y-2 z-50 hidden"></div>

<style>
    .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>


<script>
    // Ensure all modals are hidden on page load
    window.addEventListener('load', function () {
        document.getElementById('deleteModal').classList.add('hidden'); // Reset delete modal
        isFormSubmitted = false; // Reset form submission flag
    });

    let isFormSubmitted = false; // Track if the form has been submitted

    // Prevent form submission or modal state on refresh
    window.addEventListener('beforeunload', () => {
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.classList.add('hidden'); // Hide the delete modal on page unload
        isFormSubmitted = false; // Ensure no form submission happens
    });

    // Confirm Delete Button - Trigger Form Submission
    document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
        isFormSubmitted = true; // Mark form as submitted
        document.getElementById('multiDeleteForm').submit(); // Submit the form
    });

    // Cancel Button - Hide the Modal
    document.getElementById('cancelDeleteBtn').addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementById('deleteModal').classList.add('hidden'); // Close the modal
    });

    // Open Delete Modal
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden'); // Show the modal
    }

    // Filter Modal Script
    document.getElementById('openFilterModalBtn').addEventListener('click', function () {
        document.getElementById('filterModal').classList.remove('hidden');
    });

    // Import Modal Script
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

    // Rows per page selection logic
    document.getElementById('rows_per_page').addEventListener('change', function () {
        const rowsPerPage = this.value;
        console.log('Rows per page selected:', rowsPerPage);

        const form = document.getElementById('rowsPerPageForm');
        const formData = new FormData(form);
        console.log('Form data:', Object.fromEntries(formData));
    });

    // Multi-Delete and Sync Selection Logic
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllDesktop = document.getElementById('selectAllDesktop');
        const selectAllMobile = document.getElementById('selectAllMobile');
        const checkboxes = document.querySelectorAll('.assetCheckbox');
        const multiDeleteButton = document.getElementById('multiDeleteButton');
        const selectedCount = document.getElementById('selectedCount');
        const selectedIdsInput = document.getElementById('selectedIdsInput');
        let selectedIds = new Set(); // Use Set to avoid duplicates

        function updateSelectedCount() {
            selectedCount.textContent = selectedIds.size; // Update count display
            multiDeleteButton.classList.toggle('hidden', selectedIds.size === 0); // Show/hide delete button
            selectedIdsInput.value = JSON.stringify([...selectedIds]); // Store selected IDs
            document.getElementById('assetCount').innerText = selectedIds.size; // Update asset count
        }

        function syncSelectAllState() {
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            selectAllDesktop.checked = allChecked;
            selectAllMobile.checked = allChecked;
        }

        function syncCheckboxState(assetId, isChecked) {
            checkboxes.forEach(checkbox => {
                if (checkbox.value === assetId) {
                    checkbox.checked = isChecked;
                }
            });
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const assetId = this.value;
                const isChecked = this.checked;

                if (isChecked) {
                    selectedIds.add(parseInt(assetId));
                } else {
                    selectedIds.delete(parseInt(assetId));
                }

                syncCheckboxState(assetId, isChecked);
                updateSelectedCount();
                syncSelectAllState();
            });
        });

        function handleSelectAllChange(checked) {
            checkboxes.forEach(checkbox => {
                checkbox.checked = checked;
                if (checked) {
                    selectedIds.add(parseInt(checkbox.value));
                } else {
                    selectedIds.delete(parseInt(checkbox.value));
                }
            });
            updateSelectedCount();
        }

        selectAllDesktop.addEventListener('change', function () {
            handleSelectAllChange(this.checked);
            selectAllMobile.checked = this.checked;
        });

        selectAllMobile.addEventListener('change', function () {
            handleSelectAllChange(this.checked);
            selectAllDesktop.checked = this.checked;
        });

        updateSelectedCount(); // Initialize selected count on load
        syncSelectAllState();  // Sync "Select All" state
    });

    // Toast Notification Script
    setTimeout(function () {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.style.transition = 'opacity 0.5s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }
    }, 3000);
</script>

@endsection
