@extends('user.home')
@include('components.icons')

@section('requestList-content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold text-gray-800">Request List</h1>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('requests.list') }}" class="relative w-full max-w-sm">
            <input type="text" name="search" id="search" value="{{ request()->input('search') }}" placeholder="Search by ID, Description, Status, etc..." class="block w-full pl-10 pr-4 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1 sm:text-sm">
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
            <tr class="bg-gray-50 border-b">
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">Request ID</th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">Description</th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">Status</th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">Asset ID</th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">Reviewed By</th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">Created At</th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">Updated At</th>
                <th class="py-2 px-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wide">Action</th>
            </tr>
        </x-slot>

        <!-- Table Rows -->
        @if ($requests->isEmpty())
            <tr>
                <td colspan="8" class="py-4 text-center text-gray-500">No requests found.</td>
            </tr>
        @else
            @foreach ($requests as $request)
                <tr class="hover:bg-gray-100 transition duration-200 ease-in-out">
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ $request->id }}</td>
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ $request->Description }}</td>
                    <td class="py-2 px-3 text-center">
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                            {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : ($request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($request->status === 'cancelled' ? 'bg-gray-200 text-gray-600' : 'bg-red-100 text-red-800')) }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ $request->asset_id }}</td>
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ $request->approvedBy ?? 'N/A' }}</td>
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ \Carbon\Carbon::parse($request->created_at)->format('M d, Y H:i:s') }}</td>
                    <td class="py-2 px-3 text-center text-sm text-gray-800">{{ \Carbon\Carbon::parse($request->updated_at)->format('M d, Y H:i:s') }}</td>
                    <td class="py-2 px-3 flex justify-center space-x-2">
                        <!-- View Button (Trigger Modal) -->
                        <button type="button" onclick="showModal('{{ $request->id }}', '{{ $request->Description }}', '{{ $request->asset_id }}')" class="bg-blue-900 text-white w-20 h-8 flex items-center justify-center rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-all duration-200 ease-in-out shadow">
                            View
                        </button>

                        <!-- Cancel Button (Disable if status is cancelled) -->
                        @if ($request->status !== 'cancelled')
                            <form action="{{ route('requests.cancel', ['id' => $request->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white w-20 h-8 flex items-center justify-center rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 transition-all duration-200 ease-in-out shadow">
                                    Cancel
                                </button>
                            </form>
                        @else
                            <button class="bg-gray-400 text-white w-20 h-8 flex items-center justify-center rounded-md cursor-not-allowed focus:outline-none shadow" disabled>
                                Cancelled
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
    </x-table>

 <!-- Custom Pagination Links -->
 <div class="mt-4 flex justify-center">
    {{ $requests->onEachSide(1)->links('vendor.pagination.tailwind') }}
</div>

    @include('user.modalRequestList')

@endsection
