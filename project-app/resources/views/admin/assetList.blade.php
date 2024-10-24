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
                <form method="GET" action="{{ route('assetList') }}" class="flex flex-col space-y-4">
                    <!-- Add hidden fields to retain sorting and pagination parameters -->
                    <input type="hidden" name="perPage" value="{{ request('perPage') }}">
                    <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                    <input type="hidden" name="dept" value="{{ request('dept') }}">

                    <!-- Search Input -->
                    <x-search-input placeholder="Search by name or code" />
                </form>
            </div>

            <div class="header-R flex items-center space-x-0.5">
                <form action="{{ route('assetList') }}" method="GET" class="flex">
                    <button type="submit" class="p-0.5 rounded-md hover:bg-gray-100 focus:outline-none">
                        <x-icons.refresh-icon class="w-5 h-5 text-gray-600" />
                    </button>
                </form>
                <button id="openModalBtn" class="p-0.5 rounded-md hover:bg-gray-100 focus:outline-none">
                    <x-icons.importIcon />
                </button>
                {{-- <button>
                    <span>
                        <x-icons.exportIcon />
                    </span>
                </button> --}}
            </div>
        </div>
        <div class="flex justify-between items-center mb-2">
            <div class="flex items-center">
                <!-- Rows Per Page Dropdown Form -->
                <form method="GET" action="{{ route('assetList') }}" class="flex items-center space-x-2 mt-4">
                    <input type="hidden" name="dept" value="{{ request('dept') }}"> <!-- Include department ID -->
                    <input type="hidden" name="query" value="{{ request('query') }}">
                    <label for="perPage">Rows per page:</label>
                    <select name="perPage" id="perPage" class="border border-gray-300 rounded px-2 py-1 w-16"
                        onchange="this.form.submit()">
                        <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
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
                    @php
                        $queryParams = request()->query(); // Capture all query parameters
                    @endphp

                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a
                            href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'asset.code', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Code
                            <x-icons.sort-icon :direction="$sortBy === 'asset.code' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a
                            href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'asset.name', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Name
                            <x-icons.sort-icon :direction="$sortBy === 'asset.name' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a
                            href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'category.name', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Category
                            <x-icons.sort-icon :direction="$sortBy === 'category.name' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a
                            href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'department.name', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Department
                            <x-icons.sort-icon :direction="$sortBy === 'department.name' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a
                            href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'asset.depreciation', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Depreciation
                            <x-icons.sort-icon :direction="$sortBy === 'asset.depreciation' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a
                            href="{{ route('assetList', array_merge($queryParams, ['sort_by' => 'asset.status', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Status
                            <x-icons.sort-icon :direction="$sortBy === 'asset.status' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action
                    </th>
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
                            <td colspan='7' style="color: rgb(177, 177, 177)">No List</td>
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
        <!-- Flash notification -->
        @if (session('success'))
            <div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
                {{ session('success') }}
            </div>
        @endif
    </div>

    @vite(['resources/js/flashNotification.js'])

    <script>

        //Delete Modal Script
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
