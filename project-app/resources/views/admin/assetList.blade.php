@extends('layouts.app')

@section('header')
    <div class="header flex w-full justify-between pr-3 pl-3 items-center">
        <div class="title">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Asset</h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="cont">
        <div class="flex justify-between items-center">
            <div class="relative searchBox w-full max-w-md ml-2">
                <form action="{{ route('assetList') }}" method="GET" id="searchForm" class="relative flex items-center">
                    <!-- Filter Button Inside Search Input -->
                    <button type="button" id="openFilterModalBtn" class="absolute inset-y-0 left-0 flex items-center pl-3 focus:outline-none">
                        <x-icons.filter-icon class="w-5 h-5 text-gray-600" />
                    </button>

                    <!-- Search Input Field -->
                    <x-text-input
                        name="search"
                        id="searchFilt"
                        placeholder="Search by Code, Name"
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
                <form action="{{ route('assetList') }}" method="GET" class="flex">
                    <button type="submit" class="p-0.5 rounded-md hover:bg-gray-100 focus:outline-none">
                        <x-icons.refresh-icon class="w-5 h-5 text-gray-600" />
                    </button>
                </form>
                {{-- <button id="openModalBtn" class="p-0.5 rounded-md hover:bg-gray-100 focus:outline-none">
                    <x-icons.importIcon />
                </button> --}}
                {{-- <button>
                    <span>
                        <x-icons.exportIcon />
                    </span>
                </button> --}}
            </div>
        </div>
        <div class="flex justify-between items-center mb-2">
            <div class="flex items-center">
                <label for="rows_per_page" class="mr-2 text-gray-700">Rows per page:</label>
                <form action="{{ route('assetList') }}" method="GET" id="rowsPerPageForm" class="flex items-center">
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

        {{-- <div> --}}
        <div class="hidden md:block">
            <table class="table table-hover">
                <thead class="p-5 bg-gray-100 border-b">
                    <tr>
                        @php
                            $queryParams = request()->query(); // Capture all query parameters for reuse
                        @endphp

                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                            <a href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'asset.code', 'sort_order' => request('sort_order', 'asc') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-center gap-1">
                                Code
                                <x-icons.sort-icon :direction="request('sort_by') === 'asset.code' ? request('sort_order') : null" />
                            </a>
                        </th>

                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                            <a href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'asset.name', 'sort_order' => request('sort_order', 'asc') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-center gap-1">
                                Name
                                <x-icons.sort-icon :direction="request('sort_by') === 'asset.name' ? request('sort_order') : null" />
                            </a>
                        </th>

                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                            <a href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'category.name', 'sort_order' => request('sort_order', 'asc') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-center gap-1">
                                Category
                                <x-icons.sort-icon :direction="request('sort_by') === 'category.name' ? request('sort_order') : null" />
                            </a>
                        </th>

                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                            <a href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'department.name', 'sort_order' => request('sort_order', 'asc') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-center gap-1">
                                Department
                                <x-icons.sort-icon :direction="request('sort_by') === 'department.name' ? request('sort_order') : null" />
                            </a>
                        </th>

                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                            <a href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'asset.depreciation', 'sort_order' => request('sort_order', 'asc') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-center gap-1">
                                Depreciation
                                <x-icons.sort-icon :direction="request('sort_by') === 'asset.depreciation' ? request('sort_order') : null" />
                            </a>
                        </th>

                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                            <a href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'asset.status', 'sort_order' => request('sort_order', 'asc') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-center gap-1">
                                Status
                                <x-icons.sort-icon :direction="request('sort_by') === 'asset.status' ? request('sort_order') : null" />
                            </a>
                        </th>

                        <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Actions</th>
                    </tr>
                </thead>



                <tbody id="table-body">
                    @if (!$assets->isEmpty())
                        @foreach ($assets as $asset)
                            <tr>
                                <th class="align-middle text-center text-sm text-gray-900" scope="col">
                                    {{ $asset->code ? $asset->code : 'NONE' }}</th>
                                <td class="align-middle text-center text-sm text-gray-900">{{ $asset->name }}</td>
                                <td class="align-middle text-center text-sm text-gray-900">{{ $asset->category }}</td>
                                <td class="align-middle text-center text-sm text-gray-900">{{ $asset->department }}</td>
                                <td class="align-middle text-center text-sm text-gray-900">{{ $asset->depreciation }}</td>

                                <td class="align-middle text-center text-sm text-gray-900">
                                    @include('components.asset-status', ['status' => $asset->status])
                                </td>
                                <td class="w-40">
                                    <div class="grp flex gap-2 justify-center">
                                        <a href="{{ route('adminAssetDetails', $asset->code) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 focus:outline-none focus:ring-0 transition-all duration-200 ease-in-out">
                                            <x-icons.view-icon class="text-blue-900 hover:text-blue-700 w-6 h-6" />
                                        </a>
                                        <button type="button" class="inline-flex items-center w-8 h-8"
                                            onclick="openDeleteModal('{{ $asset->id }}')">
                                            <x-icons.cancel-icon class="text-red-500 hover:text-red-600 w-6 h-6" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center text-gray-800">
                            <td colspan='7' style="color: rgb(177, 177, 177)">No Assets Found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

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
                        <a href="{{ route('adminAssetDetails', $asset->code) }}" class="text-blue-900 hover:text-blue-700">
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
        {{-- @include('admin.modal.filterAssetTable') --}}
        @vite(['resources/js/flashNotification.js'])
        <!-- Flash notification -->
        @if (session('success'))
            <div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
                {{ session('success') }}
            </div>
        @endif
    </div>

    @vite(['resources/js/flashNotification.js'])
    @include('dept_head.modal.filterAssetTable', ['categoriesList' => $categoriesList])

    <script>

        //Delete Modal Script

        document.getElementById('openFilterModalBtn').addEventListener('click', function () {
            document.getElementById('filterModal').classList.remove('hidden'); // Show the modal
        });

        function openDeleteModal(assetId) {
            const deleteForm = document.getElementById('deleteForm');
            const actionUrl = `{{ url('admin/asset/delete') }}/${assetId}`; // Absolute URL

            deleteForm.action = actionUrl; // Set the form action dynamically
            console.log(`Delete form action: ${deleteForm.action}`); // Debugging

            // Show the modal
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        document.getElementById('cancelDeleteBtn').addEventListener('click', () => {
            document.getElementById('deleteModal').classList.add('hidden'); // Hide the modal
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
            document.getElementById('deleteForm').submit(); // Submit the form
        });



    </script>
@endsection
