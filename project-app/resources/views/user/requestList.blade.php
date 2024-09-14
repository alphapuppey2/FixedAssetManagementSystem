@extends('user.home')
@include('components.icons')

@section('requestList-content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Request List</h1>

        <!-- Search Bar -->
        <div class="relative w-full max-w-sm">
            <input type="text" id="search" placeholder="Search..." class="border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:border-blue-500 w-full">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                @yield('searchIcon')
            </div>
        </div>
    </div>

    <!-- Request List Table -->
    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full bg-white border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 text-left font-semibold text-gray-600 border-b">Request ID</th>
                    <th class="py-3 px-4 text-left font-semibold text-gray-600 border-b">Description</th>
                    <th class="py-3 px-4 text-left font-semibold text-gray-600 border-b">Status</th>
                    <th class="py-3 px-4 text-left font-semibold text-gray-600 border-b">Asset ID</th>
                    <th class="py-3 px-4 text-left font-semibold text-gray-600 border-b">Asset Name</th>
                    <th class="py-3 px-4 text-left font-semibold text-gray-600 border-b">Reviewed by</th>
                    <th class="py-3 px-4 text-left font-semibold text-gray-600 border-b">Created At</th>
                    <th class="py-3 px-4 text-left font-semibold text-gray-600 border-b">Updated At</th>
                    <th class="py-3 px-4 text-left font-semibold text-gray-600 border-b">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @if ($requests->isEmpty())
                    <tr>
                        <td colspan="9" class="py-4 text-center text-gray-500">No requests found.</td>
                    </tr>
                @else
                    @foreach ($requests as $request)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-3 px-4 border-b">{{ $request->id }}</td>
                            <td class="py-3 px-4 border-b">{{ $request->Description }}</td>
                            <td class="py-3 px-4 border-b">
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : ($request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 border-b">{{ $request->asset_id }}</td>
                            <td class="py-3 px-4 border-b">{{ $request->name }}</td>
                            <td class="py-3 px-4 border-b">{{ $request->approvedBy }}</td>
                            <td class="py-3 px-4 border-b">{{ $request->created_at }}</td>
                            <td class="py-3 px-4 border-b">{{ $request->updated_at }}</td>
                            <td class="py-3 px-4 border-b flex space-x-2">
                                <button class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
                                    Edit
                                </button>
                                <button class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 transition">
                                    Cancel
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
