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
        <div class="searchBox">
            <form action="{{ route('assets.search') }}" method="GET" id="searchForm">
                <x-text-input
                    name="search"
                    id="searchFilt"
                    placeholder="Search"
                    value="{{ request('search') }}"
                    class="mr-2" />
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
                        <a href="{{ route('asset', ['sort' => 'code', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}"
                           class="flex items-center justify-center gap-1">
                            Code
                            <x-icons.sort-icon :direction="request('sort') === 'code' ? request('direction') : null" />
                        </a>
                    </th>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                        <a href="{{ route('asset', ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}"
                           class="flex items-center justify-center gap-1">
                            Name
                            <x-icons.sort-icon :direction="request('sort') === 'name' ? request('direction') : null" />
                        </a>
                    </th>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                        <a href="{{ route('asset', ['sort' => 'category_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}"
                           class="flex items-center justify-center gap-1">
                            Category
                            <x-icons.sort-icon :direction="request('sort') === 'category_name' ? request('direction') : null" />
                        </a>
                    </th>
                    <th class="py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                        <a href="{{ route('asset', ['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}"
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
@endsection
