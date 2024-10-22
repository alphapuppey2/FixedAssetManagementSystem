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
    <div>
        <form method="GET" action="{{ route('searchAssets') }}" class="flex flex-col space-y-4">
            <div class="relative search-container">
                <!-- Add hidden field for department ID -->
                <input type="hidden" name="dept" value="{{ request()->dept }}">

                <!-- Search Input and Button -->
                <x-search-input 
                    placeholder="Search by name or code"
                    class="w-72" />
            </div>

            <!-- Rows Per Page Dropdown and Pagination Controls -->
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center space-x-2">
                    <label for="perPage">Rows per page: </label>
                    <select name="perPage" id="perPage" class="border border-gray-300 rounded px-2 py-1 w-16" onchange="this.form.submit()">
                        <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                @if($assets->hasPages())
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Showing {{ $assets->firstItem() }} to {{ $assets->lastItem() }} of {{ $assets->total() }} assets</span>
                    <div>
                        {{ $assets->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </form>
    </div>
    <div>
        <table class="table table-hover">
            <thead class="p-5 bg-gray-100 border-b">
                <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Code</th>
                <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name</th>
                <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Category</th>
                <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Department</th>
                <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Depreciation</th>
                <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status</th>
                <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Action</th>
            </thead>
            <tbody id="table-body">
                @if (!$assets->isEmpty())
                @foreach ($assets as $asset)
                <tr>
                    <th class="align-middle text-center text-sm text-gray-900" scope="col">{{ $asset->code ? $asset->code : 'NONE' }}</th>
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
                                <form action="{{ route('asset.delete', $asset->code) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center w-8 h-8 focus:outline-none focus:ring-0 transition-all duration-200 ease-in-out"
                                        onclick="return confirm('Are you sure you want to delete this asset?');">
                                        <x-icons.cancel-icon class="text-red-500 hover:text-red-600 w-6 h-6" />
                                    </button>
                                </form>
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

    <!-- Flash notification -->
    @if (session('success'))
    <div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
        {{ session('success') }}
    </div>
    @endif
</div>

@vite(['resources/js/flashNotification.js'])
@endsection
