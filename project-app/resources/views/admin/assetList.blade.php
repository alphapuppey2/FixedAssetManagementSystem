@extends('layouts.app')

@section('header')
    <div class="header flex w-full justify-between pr-3 pl-3 items-center">
        <div class="title">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $departmentName ? $departmentName . ' Assets' : 'Assets' }}
            </h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="cont">
        <div class="flex justify-between items-center">
            <div class="relative searchBox w-full max-w-md ml-2">
                <form action="{{ route('assetList') }}" method="GET" id="searchForm" class="relative flex items-center">
                    <!-- Filter Button Inside Search Input -->
                    <button type="button" id="openFilterModalBtn"
                        class="absolute inset-y-0 left-0 flex items-center pl-3 focus:outline-none">
                        <x-icons.filter-icon class="w-5 h-5 text-gray-600" />
                    </button>

                    <!-- Search Input Field -->
                    <x-text-input name="query" id="searchFilt" placeholder="Search by Code, Name"
                        value="{{ request('query') }}"
                        class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1 sm:text-sm" />

                    <!-- Retain department filter -->
                    <input type="hidden" name="dept" value="{{ request('dept') }}">

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
                <form action="{{ route('assetList') }}" method="GET" class="flex">
                    <button type="submit" class="p-0.5 rounded-md hover:bg-gray-100 focus:outline-none">
                        <x-icons.refresh-icon class="w-5 h-5 text-gray-600" />
                    </button>
                </form>
            </div>
        </div>
        {{-- reee --}}
        <div class="flex justify-between items-center mb-2">
            <div class="flex items-center">
                <form action="{{ route('assetList') }}" method="GET" id="rowsPerPageForm" class="flex items-center">
                    <input type="hidden" name="dept" value="{{ request('dept') }}"> <!-- Retain Department ID -->
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'asset.name') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">

                    @foreach ((array) request('status', []) as $status)
                        <input type="hidden" name="status[]" value="{{ $status }}">
                    @endforeach

                    @foreach ((array) request('category', []) as $category)
                        <input type="hidden" name="category[]" value="{{ $category }}">
                    @endforeach

                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">

                    <label for="rows_per_page" class="mr-2">Rows per page:</label>
                    <select name="rows_per_page" id="rows_per_page" class="border rounded-md"
                        onchange="document.getElementById('rowsPerPageForm').submit()">

                        <option value="10" {{ request('rows_per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('rows_per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('rows_per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </form>
            </div>
            <!-- Pagination Links and Showing Results -->

            {{-- <div class="flex items-center space-x-4 mt-4">
                <span class="text-gray-600">
                    Showing {{ $assets->firstItem() }} to {{ $assets->lastItem() }} of {{ $assets->total() }} assets
                </span>
                @if ($assets->hasPages())
                    <div>
                        {{ $assets->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div> --}}

            <div class="flex items-center justify-between mt-4 flex-col md:flex-row space-x-4 md:space-y-0">
                <span class="text-gray-600 hidden md:block">
                    Showing {{ $assets->firstItem() }} to {{ $assets->lastItem() }} of {{ $assets->total() }} assets
                </span>
                <div class="text-sm md:text-base">
                    @if ($assets->hasPages())
                        <div class="md:hidden md:hidden text-xs flex justify-center space-x-1 mt-2">
                            {{ $assets->appends(request()->query())->links() }}
                        </div>
                        <div class="hidden md:block">
                            {{ $assets->appends(request()->query())->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- <deletion tables with card Mobile> --}}

        <form action="{{ route('adminasset.multiDelete') }}" method="POST" id="multiDeleteForm">
            @csrf
            @method('DELETE')

            <!-- Selected Count Display -->
            <div class="mb-2 text-gray-600" id="selectedCountContainer">
                Selected Assets: <span id="selectedCount">0</span>
            </div>

            <div class="flex justify-between items-center mb-2">
                <!-- Multi-Delete Button -->
                <button type="button" onclick="openDeleteModal()"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md hidden" id="multiDeleteButton">
                    Delete Selected
                </button>
                <input type="hidden" name="selected_ids" id="selectedIdsInput">
                @include('admin.modal.deleteAssetModal')
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
                            @php
                                $queryParams = request()->query(); // Capture all query parameters for reuse
                            @endphp


                            <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                <a href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'asset.code', 'sort_order' => request('sort_order', 'asc') === 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex gap-1">
                                    Code
                                    <x-icons.sort-icon :direction="request('sort_by') === 'asset.code' ? request('sort_order') : null" />
                                </a>
                            </th>
                            <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider ">

                                image
                            </th>

                            <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider ">
                                <a href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'asset.name', 'sort_order' => request('sort_order', 'asc') === 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex gap-1">
                                    Name
                                    <x-icons.sort-icon :direction="request('sort_by') === 'asset.name' ? request('sort_order') : null" />
                                </a>
                            </th>

                            <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider ">
                                <a href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'category.name', 'sort_order' => request('sort_order', 'asc') === 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex gap-1">
                                    Category
                                    <x-icons.sort-icon :direction="request('sort_by') === 'category.name' ? request('sort_order') : null" />
                                </a>
                            </th>

                            <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider ">
                                <a href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'department.name', 'sort_order' => request('sort_order', 'asc') === 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex gap-1">
                                    Department
                                    <x-icons.sort-icon :direction="request('sort_by') === 'department.name' ? request('sort_order') : null" />
                                </a>
                            </th>

                            <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                <a href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'asset.depreciation', 'sort_order' => request('sort_order', 'asc') === 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex gap-1">
                                    Depreciation
                                    <x-icons.sort-icon :direction="request('sort_by') === 'asset.depreciation' ? request('sort_order') : null" />
                                </a>
                            </th>

                            <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider ">
                                <a href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'asset.status', 'sort_order' => request('sort_order', 'asc') === 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex gap-1">
                                    Status
                                    <x-icons.sort-icon :direction="request('sort_by') === 'asset.status' ? request('sort_order') : null" />
                                </a>
                            </th>

                            <th class="py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Actions
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
                                <td>{{ $asset->category }}</td>
                                <td>{{ $asset->department }}</td>
                                {{-- <td>{{ $asset->depreciation ?? 0.0 }}</td> --}}
                                <td>{{ number_format($asset->depreciation ?? 0, 2) }}</td>
                                <td>@include('components.asset-status', ['status' => $asset->status])</td>
                                <td class="align-middle font-bold text-left text-sm text-gray-900">
                                    <div class="deb flex justify-center">
                                        <a href="{{ route('adminAssetDetails', $asset->code) }}" class="text-blue-900">
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
                    <div class="bg-white shadow-md rounded-lg flex gap-2 items-center p-4">
                        <input type="checkbox" name="asset_ids[]" value="{{ $asset->id }}"
                            class="assetCheckbox w-5 h-5">
                        <div class="containCard flex w-full justify-between">
                            <div class="details">
                                <p><strong>Code:</strong> {{ $asset->code ?? 'NONE' }}</p>
                                <p><strong>Name:</strong> {{ $asset->name }}</p>
                                <p><strong>Category:</strong> {{ $asset->depreciation }}</p>
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


        <!-- Card Layout for Small Screens -->
        <div class="block md:hidden">
            {{-- Changed: Added 'block md:hidden' to show cards only on small screens --}}
            @foreach ($assets as $asset)
                <div class="bg-white shadow-md rounded-lg p-4 mb-2">
                    <p><strong>Code:</strong> {{ $asset->code ?? 'NONE' }}</p>
                    <p><strong>Name:</strong> {{ $asset->name }}</p>
                    <p><strong>Category:</strong> {{ $asset->category }}</p>
                    <p><strong>Department:</strong> {{ $asset->department }}</p>
                    <p><strong>Depreciation:</strong> {{ $asset->depreciation }}</p>
                    <p><strong>Status:</strong> @include('components.asset-status', ['status' => $asset->status])</p>
                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('adminAssetDetails', $asset->code) }}"
                            class="text-blue-900 hover:text-blue-700">
                            <x-icons.view-icon class="w-6 h-6" />
                        </a>
                        <button type="button" onclick="openDeleteModal('{{ $asset->id }}')">
                            <x-icons.cancel-icon class="text-red-500 hover:text-red-600 w-6 h-6" />
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @include('admin.modal.deleteAssetModal')
        @vite(['resources/js/flashNotification.js'])
        <!-- Flash notification -->
        @if (session('success'))
            <div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
                {{ session('error') }}
            </div>
        @endif
        </deletion>

        @vite(['resources/js/flashNotification.js'])
        @include('admin.modal.filterAssetTable', ['categoriesList' => $categoriesList])

        <script>
            // Ensure all modals are hidden on page load
            window.addEventListener('load', function() {
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
            document.getElementById('openFilterModalBtn').addEventListener('click', function() {
                document.getElementById('filterModal').classList.remove('hidden');
            });

            // Import Modal Script
            document.addEventListener('DOMContentLoaded', function() {
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
            document.getElementById('rows_per_page').addEventListener('change', function() {
                const rowsPerPage = this.value;
                console.log('Rows per page selected:', rowsPerPage);

                const form = document.getElementById('rowsPerPageForm');
                const formData = new FormData(form);
                console.log('Form data:', Object.fromEntries(formData));
            });

            // Multi-Delete and Sync Selection Logic
            document.addEventListener('DOMContentLoaded', function() {
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
                    checkbox.addEventListener('change', function() {
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

                selectAllDesktop.addEventListener('change', function() {
                    handleSelectAllChange(this.checked);
                    selectAllMobile.checked = this.checked;
                });

                selectAllMobile.addEventListener('change', function() {
                    handleSelectAllChange(this.checked);
                    selectAllDesktop.checked = this.checked;
                });

                updateSelectedCount(); // Initialize selected count on load
                syncSelectAllState(); // Sync "Select All" state
            });
        </script>
    @endsection
