@extends('user.home')
@include('components.icons')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight text-center md:text-left">
    {{ "Request List"}}
</h2>
@endsection

@section('content')
<!-- Container for Refresh and Search Bar -->
<div class="flex flex-col md:flex-row md:items-center mb-4 justify-end md:space-x-2 space-y-2 md:space-y-0">
    <div class="flex items-center w-full max-w-lg space-x-2">
        <!-- Search Bar with Filter Button Inside -->
        <div class="relative w-full">
            <form method="GET" action="{{ route('requests.list') }}" class="relative flex items-center">
                <button type="button" onclick="openModal()" class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <x-icons.filter-icon class="w-5 h-5 text-gray-600" />
                </button>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Search by ID, Description, Status, etc..."
                    class="block w-full pl-10 pr-16 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1 sm:text-sm">
            </form>
        </div>

        <!-- Refresh Button (Placed on the Left in Smaller Screens) -->
        <form action="{{ route('requests.list') }}" method="GET" class="md:order-none order-first">
            <button type="submit" 
                class="flex items-center text-sm hover:bg-gray-300 text-gray-600 font-bold py-1 px-3 rounded-md focus:outline-none">
                <x-icons.refresh-icon class="w-5 h-5" />
            </button>
        </form>
    </div>
</div>

<!-- Request List Table -->
<div class="overflow-x-auto hidden md:block">
    <table class="min-w-full bg-white border rounded-md">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                    <a href="{{ route('requests.list', array_merge(request()->query(), ['sort_by' => 'id', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Request ID
                        <x-icons.sort-icon :direction="request('sort_by') === 'id' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Description</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                    <a href="{{ route('requests.list', array_merge(request()->query(), ['sort_by' => 'type', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Type
                        <x-icons.sort-icon :direction="request('sort_by') === 'type' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                    <a href="{{ route('requests.list', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Created At
                        <x-icons.sort-icon :direction="request('sort_by') === 'created_at' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                    <a href="{{ route('requests.list', array_merge(request()->query(), ['sort_by' => 'status', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Status
                        <x-icons.sort-icon :direction="request('sort_by') === 'status' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                    <a href="{{ route('requests.list', array_merge(request()->query(), ['sort_by' => 'asset_code', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Asset Code
                        <x-icons.sort-icon :direction="request('sort_by') === 'asset_code' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($requests->isEmpty())
            <tr>
                <td colspan="7" class="py-4 text-center text-gray-500">No requests found.</td>
            </tr>
            @else
            @foreach ($requests as $request)
            <tr class="hover:bg-gray-100 transition duration-200">
                <td class="py-2 px-3 text-center text-sm">{{ $request->id }}</td>
                <td class="py-2 px-3 text-center text-sm">{{ $request->description }}</td>
                <td class="py-2 px-3 text-center text-sm">{{ $request->type }}</td>
                <td class="py-2 px-3 text-center text-sm">{{ $request->created_at }}</td>
                <td class="py-2 px-3 text-center">
                    @php
                    $statusClasses = [
                        'approved' => 'bg-green-100 text-green-800',
                        'request' => 'bg-yellow-100 text-yellow-800',
                        'cancelled' => 'bg-gray-200 text-gray-600',
                        'denied' => 'bg-red-100 text-red-800',
                        'in_progress' => 'bg-blue-100 text-blue-800',
                    ];
                    $statusClass = $statusClasses[$request->status] ?? 'bg-purple-100 text-purple-800';
                    @endphp
                    <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </td>
                <td class="py-2 px-3 text-center text-sm">{{ $request->asset_code }}</td>
                <td class="py-2 px-3 flex justify-center space-x-2">
                    <button type="button" onclick="showModal('{{ $request->asset_code }}', ...)"
                        class="w-8 h-8 focus:outline-none">
                        <x-icons.view-icon class="text-blue-900 hover:text-blue-700 w-6 h-6" />
                    </button>
                    @if ($request->status === 'request')
                    <button type="button" onclick="showCancelModal({{ $request->id }})" class="w-8 h-8">
                        <x-icons.cancel-icon class="text-red-500 hover:text-red-600 w-6 h-6" />
                    </button>
                    @endif
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>

<!-- Mobile Card View added for small screens -->
<div class="block md:hidden space-y-4">
    @if ($requests->isEmpty())
    <div class="text-center text-gray-500">No requests found.</div>
    @else
    @foreach ($requests as $request)
    <div class="bg-white shadow-md rounded-md p-3 sm:p-4">
        <h3 class="font-bold text-lg mb-2">Request ID: {{ $request->id }}</h3>
        <p class="break-words"><strong>Description:</strong> {{ $request->description }}</p>
        <p><strong>Type:</strong> {{ $request->type }}</p>
        <p><strong>Created At:</strong> {{ $request->created_at }}</p>
        <p>
            <strong>Status:</strong> 
            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                {{ ucfirst($request->status) }}
            </span>
        </p>
        <p><strong>Asset Code:</strong> {{ $request->asset_code }}</p>
        <div class="flex justify-end space-x-4 mt-4">
            <!-- View Icon Button -->
            <button onclick="showModal('{{ $request->asset_code }}', ...)" class="w-8 h-8" aria-label="View Request">
                <x-icons.view-icon class="w-6 h-6 text-blue-900 hover:text-blue-700" />
            </button>

            @if ($request->status === 'request')
            <!-- Cancel Icon Button -->
            <button onclick="showCancelModal({{ $request->id }})" class="w-8 h-8">
                <x-icons.cancel-icon class="w-6 h-6 text-red-500 hover:text-red-600" />
            </button>
            @endif
        </div>
    </div>
    @endforeach
    @endif
</div>

<!-- Pagination -->
<div class="mt-4 flex justify-center">
    {{ $requests->appends(request()->query())->links('vendor.pagination.tailwind') }}
</div>

@include('user.modalSearchFilter')
@include('user.modalCancel')
@include('user.modalRequestList')

@endsection
