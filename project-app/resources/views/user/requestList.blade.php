@extends('user.home')
@include('components.icons')

@section('requestList-content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900">Request List</h1>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('requests.list') }}" class="relative w-full max-w-md">
            <input type="text" name="search" id="search" value="{{ request()->input('search') }}" placeholder="Search by ID, Description, Status, etc..." class="block w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1 sm:text-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11a4 4 0 104-4M2 2l20 20"></path>
                </svg>
            </div>
        </form>
    </div>

    <!-- Request List Table -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 text-left text-sm font-bold text-gray-700">REQUEST ID</th>
                    <th class="py-3 px-4 text-left text-sm font-bold text-gray-700">DESCRIPTION</th>
                    <th class="py-3 px-4 text-left text-sm font-bold text-gray-700">STATUS</th>
                    <th class="py-3 px-4 text-left text-sm font-bold text-gray-700">ASSET ID</th>
                    <th class="py-3 px-4 text-left text-sm font-bold text-gray-700">REVIEWED BY</th>
                    <th class="py-3 px-4 text-left text-sm font-bold text-gray-700">CREATED AT</th>
                    <th class="py-3 px-4 text-left text-sm font-bold text-gray-700">UPDATED AT</th>
                    <th class="py-3 px-4 text-left text-sm font-bold text-gray-700">ACTION</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @if ($requests->isEmpty())
                    <tr>
                        <td colspan="8" class="py-4 text-center text-gray-500">No requests found.</td>
                    </tr>
                @else
                    @foreach ($requests as $request)
                        <tr class="hover:bg-gray-50 transition-all duration-300">
                            <td class="py-3 px-4 border-b text-sm">{{ $request->id }}</td>
                            <td class="py-3 px-4 border-b text-sm">{{ $request->Description }}</td>
                            <td class="py-3 px-4 border-b">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : ($request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 border-b text-sm">{{ $request->asset_id }}</td>
                            <td class="py-3 px-4 border-b text-sm">{{ $request->approvedBy ?? 'N/A' }}</td>
                            <td class="py-3 px-4 border-b text-sm">{{ \Carbon\Carbon::parse($request->created_at)->format('M d, Y H:i:s') }}</td>
                            <td class="py-3 px-4 border-b text-sm">{{ \Carbon\Carbon::parse($request->updated_at)->format('M d, Y H:i:s') }}</td>
                            <td class="py-3 px-4 border-b flex space-x-2">
                                <!-- View Button (Trigger Modal) -->
                                <button type="button" onclick="showModal('{{ $request->id }}', '{{ $request->Description }}', '{{ $request->asset_id }}')" class="bg-indigo-500 text-white px-3 py-1 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-all duration-200 ease-in-out">
                                    View
                                </button>

                                <!-- Cancel Button -->
                                <form action="{{ route('requests.cancel', ['id' => $request->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 transition-all duration-200 ease-in-out">
                                        Cancel
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="mt-4 flex justify-center">
            {{ $requests->links() }}
        </div>
    </div>

    @include('user.modalRequestList')

@endsection
