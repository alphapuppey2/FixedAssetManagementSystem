@extends('user.home')
@include('components.icons')

@section('requestList-content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold text-gray-800">Request List</h1>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('requests.list') }}" class="relative w-full max-w-sm">
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by ID, Description, Status, etc..." class="block w-full pl-10 pr-4 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1 sm:text-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11a4 4 0 104-4M2 2l20 20"></path>
                </svg>
            </div>
        </form>
    </div>

    <!-- Request List Table -->
    <x-table class="table-auto w-full">
        <!-- Header -->
        <x-slot name="header">
            <tr class="bg-gray-50 ">
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">
                    <a href="{{ route('requests.list', ['search' => request('search'), 'sort_by' => 'id', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Request ID
                        <x-icons.sort-icon :direction="request('sort_by') == 'id' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">
                    Description
                </th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">
                    <a href="{{ route('requests.list', ['search' => request('search'), 'sort_by' => 'type', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Type
                        <x-icons.sort-icon :direction="request('sort_by') == 'type' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">
                    <a href="{{ route('requests.list', ['search' => request('search'), 'sort_by' => 'requested_at', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Requested At
                        <x-icons.sort-icon :direction="request('sort_by') == 'requested_at' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">
                    <a href="{{ route('requests.list', ['search' => request('search'), 'sort_by' => 'status', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Status
                        <x-icons.sort-icon :direction="request('sort_by') == 'status' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">
                    <a href="{{ route('requests.list', ['search' => request('search'), 'sort_by' => 'asset_code', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Asset Code
                        <x-icons.sort-icon :direction="request('sort_by') == 'asset_code' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">Action</th>
            </tr>
        </x-slot>

        <!-- Table Rows -->
        @if ($requests->isEmpty())
            <tr>
                <td colspan="7" class="py-4 text-center text-gray-500">No requests found.</td>
            </tr>
        @else
            @foreach ($requests as $request)
                <tr class="hover:bg-gray-100 transition duration-200 ease-in-out">
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ $request->id }}</td>
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ $request->description }}</td>
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ $request->type }}</td>
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ \Carbon\Carbon::parse($request->requested_at)->format('M d, Y H:i:s') }}</td>
                    <td class="py-2 px-3 text-center">
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                            {{
                                $request->status === 'approved' ? 'bg-green-100 text-green-800' :
                                ($request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                ($request->status === 'cancelled' ? 'bg-gray-200 text-gray-600' :
                                ($request->status === 'denied' ? 'bg-red-100 text-red-800' :
                                ($request->status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                'bg-purple-100 text-purple-800'))))
                            }} ">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ $request->asset_code }}</td>

                    <!-- Action (View and Cancel Buttons) -->
                    <td class="py-2 px-3 flex justify-center items-center space-x-2">
                        <!-- View Icon Button with showModal functionality -->
                        <button type="button" onclick="showModal('{{ $request->asset_code }}', '{{ $request->asset_image }}', '{{ $request->asset_name }}', '{{ $request->cost }}', '{{ $request->depreciation }}', '{{ $request->salvageVal }}', '{{ $request->category }}', '{{ $request->usage_Lifespan }}', '{{ $request->model }}', '{{ $request->manufacturer }}', '{{ $request->location }}', '{{ $request->asset_status }}', '{{ $request->description }}', '{{ $request->id }}', '{{ $request->status }}')" class="inline-flex items-center justify-center w-8 h-8 focus:outline-none focus:ring-0 transition-all duration-200 ease-in-out">
                            <x-icons.view-icon class="text-blue-900 hover:text-blue-700 w-6 h-6" />
                        </button>

                        <!-- Cancel Icon Button (Triggers modal) -->
                        @if ($request->status === 'pending')
                            <button type="button" onclick="showCancelModal({{ $request->id }})" class="inline-flex items-center justify-center w-8 h-8 focus:outline-none focus:ring-0 transition-all duration-200 ease-in-out">
                                <x-icons.cancel-icon class="text-red-500 hover:text-red-600 w-6 h-6" />
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
    </x-table>

    <!-- Pagination Links -->
    <div class="mt-4 flex justify-center">
        {{ $requests->appends(request()->query())->links('vendor.pagination.tailwind') }}
    </div>

    <!-- Include the view and cancel modals -->
    @include('user.modalRequestList') <!-- View modal -->
    @include('user.modalCancel') <!-- Cancel modal -->

@endsection
