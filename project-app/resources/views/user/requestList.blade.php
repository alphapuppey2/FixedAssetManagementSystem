@extends('user.home')
@include('components.icons')

@section('requestList-content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Request List</h1>

        <!-- Search Bar -->
        <div class="relative">
            <input type="text" id="search" placeholder="Search..." class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 w-full max-w-xs">
            @yield('searchIcon')
        </div>
    </div>

    <!-- Request List Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left">Request ID</th>
                    <th class="py-2 px-4 border-b text-left">Description</th>
                    <th class="py-2 px-4 border-b text-left">Status</th>
                    <th class="py-2 px-4 border-b text-left">Asset ID</th>
                    <th class="py-2 px-4 border-b text-left">Asset Name</th>
                    <th class="py-2 px-4 border-b text-left">Reviewed by</th>
                    <th class="py-2 px-4 border-b text-left">Created At</th>
                    <th class="py-2 px-4 border-b text-left">Updated At</th>
                    <th class="py-2 px-4 border-b text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($requests->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No requests found.</td>
                    </tr>
                @else
                    @foreach ($requests as $request)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $request->id }}</td>
                            <td class="py-2 px-4 border-b">{{ $request->Description }}</td>
                            <td class="py-2 px-4 border-b">{{ $request->status }}</td>
                            <td class="py-2 px-4 border-b">{{ $request->asset_id }}</td>
                            <td class="py-2 px-4 border-b">{{ $request->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $request->approvedBy }}</td>
                            <td class="py-2 px-4 border-b">{{ $request->created_at }}</td>
                            <td class="py-2 px-4 border-b">{{ $request->updated_at }}</td>
                            <td class="py-2 px-4 border-b">
                                <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Edit</button>
                                <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Cancel</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
