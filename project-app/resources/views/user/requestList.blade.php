@extends('user.home')
@include('components.icons')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ "Request List"}}
    </h2>
@endsection

@section('content')
<div class="flex items-center mb-4 justify-end">
    <div class="flex items-center space-x-2 w-full max-w-lg">
        <!-- Refresh Button -->
        <form action="{{ route('requests.list') }}" method="GET">
            <button type="submit" class="flex items-center text-sm hover:bg-gray-300 text-gray-600 font-bold py-1 px-3 rounded-md focus:outline-none">
                <x-icons.refresh-icon class="w-5 h-5" />
            </button>
        </form>

        <!-- Search Bar with Filter Button Inside -->
        <div class="relative w-full">
            <form method="GET" action="{{ route('requests.list') }}" class="relative w-full flex items-center">
                <!-- Filter Button Inside Search Input (on the left) -->
                <button type="button" onclick="openModal()" class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <x-icons.filter-icon class="w-5 h-5 text-gray-600" /> <!-- Adjusted size -->
                </button>
                <!-- Search Input Field -->
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Search by ID, Description, Status, etc..."
                    class="block w-full pl-10 pr-16 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1 sm:text-sm">
            </form>
        </div>
    </div>
</div>

<!-- Desktop View: Request List Table -->
<div class="hidden md:block overflow-x-auto"> <!-- Added hidden on small screens -->
    <!-- Request List Table -->
    <table class="min-w-full bg-white border rounded-md">
        <!-- Header -->
        <thead class="bg-gray-100 border-b">
            <tr class="bg-gray-50 ">
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <a href="{{ route('requests.list', array_merge(request()->query(), ['sort_by' => 'id', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Request ID
                        <x-icons.sort-icon :direction="request('sort_by') === 'id' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Description
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <a href="{{ route('requests.list', array_merge(request()->query(), ['sort_by' => 'type', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Type
                        <x-icons.sort-icon :direction="request('sort_by') === 'type' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <a href="{{ route('requests.list', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Created At
                        <x-icons.sort-icon :direction="request('sort_by') === 'created_at' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <a href="{{ route('requests.list', array_merge(request()->query(), ['sort_by' => 'status', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Status
                        <x-icons.sort-icon :direction="request('sort_by') === 'status' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <a href="{{ route('requests.list', array_merge(request()->query(), ['sort_by' => 'asset_code', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Asset Code
                        <x-icons.sort-icon :direction="request('sort_by') === 'asset_code' ? request('sort_direction') : null" />
                    </a>
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
        </thead>

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
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ $request->created_at }}</td>
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
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ $request->asset_code }}</td>

                    <!-- Action (View and Cancel Buttons) -->
                    <td class="py-2 px-3 flex justify-center items-center space-x-2">
                        <button type="button"  onclick="showModal({{ json_encode($request) }})"
                             class="inline-flex items-center justify-center w-8 h-8 focus:outline-none focus:ring-0 transition-all duration-200 ease-in-out">
                            <x-icons.view-icon class="text-blue-900 hover:text-blue-700 w-6 h-6" />
                        </button>
                        {{-- modal here --}}

                        {{-- @include('user.modalRequestList' ,['request' =>$request]) --}}

                        @if ($request->status === 'request')
                        <button type="button" onclick="showCancelModal({{ $request->id }})" class="inline-flex items-center justify-center w-8 h-8 focus:outline-none focus:ring-0 transition-all duration-200 ease-in-out">
                            <x-icons.cancel-icon class="text-red-500 hover:text-red-600 w-6 h-6" />
                        </button>
                    @endif

                    </td>
                </tr>
            @endforeach
        @endif
    </table>
</div>

<!-- Mobile View: Cards -->
<div class="block md:hidden space-y-4"> <!-- Display only on small screens -->
    @if ($requests->isEmpty())
        <div class="text-center text-gray-500">No requests found.</div>
    @else
        @foreach ($requests as $request)
            <div class="bg-white shadow-md rounded-md p-4">
                <h3 class="font-bold text-lg">Request ID: {{ $request->id }}</h3>
                <p><strong>Description:</strong> {{ $request->description }}</p>
                <p><strong>Type:</strong> {{ $request->type }}</p>
                <p><strong>Created At:</strong> {{ $request->created_at }}</p>
                <p>
                    @php
                    $statusClass = $statusClasses[$request->status] ?? 'bg-purple-100 text-purple-800';
                @endphp
                    <strong>Status:</strong>
                    <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </p>
                <p><strong>Asset Code:</strong> {{ $request->asset_code }}</p>
                <!-- Action Button  s -->
                <div class="flex justify-end space-x-2 mt-4">
                    <!-- View Button -->
                    <button
                        class="w-8 h-8" onclick="showModal({{ json_encode($request) }})">
                        <x-icons.view-icon class="text-blue-900 hover:text-blue-700 w-6 h-6" />
                    </button>


                    <!-- Cancel Button -->
                    @if ($request->status === 'request')
                        <button
                            onclick="showCancelModal({{ $request->id }})"
                            class="w-8 h-8">
                            <x-icons.cancel-icon class="text-red-500 hover:text-red-600 w-6 h-6" />
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- Pagination Links -->
<div class="mt-4 flex justify-center">
    {{ $requests->appends(request()->query())->links('vendor.pagination.tailwind') }}
</div>

<script>
    // function showModal(
    // ) {
    //     // Example: Use console to ensure the data is passed correctly
    //     // console.log({ assetCode, assetName, cost });

    //     // // Inject the data into the modal (or display it as needed)
    //     // document.getElementById('modalTitle').textContent = `Asset Code: ${assetCode}`;
    //     // document.getElementById('modalDescription').textContent = description;
    //     // document.getElementById('modalImage').src = assetImage;

    //     // Show the modal
    //     document.getElementById('viewModal').classList.remove('hidden');
    // }

</script>

<!-- Include the modal for the search filter -->
@include('user.modalSearchFilter') <!-- Search Modal -->
@include('user.modalCancel')
@include('user.modalRequestList')

@endsection
