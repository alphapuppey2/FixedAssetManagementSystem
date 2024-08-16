<!-- resources/views/user/requestList.blade.php -->
@extends('user.home')

@section('requestList-content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Request List</h1>
        
        <!-- Search Bar -->
        <div class="relative">
            <input type="text" id="search" placeholder="Search..." class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 w-full max-w-xs">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-1/2 right-3 transform -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
        </div>
    </div>

    <!-- Request List Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left">Asset ID</th>
                    <th class="py-2 px-4 border-b text-left">Reason</th>
                    <th class="py-2 px-4 border-b text-left">Date</th>
                    <th class="py-2 px-4 border-b text-left">Status</th>
                    <th class="py-2 px-4 border-b text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Row 1 -->
                <tr>
                    <td class="py-2 px-4 border-b">001</td>
                    <td class="py-2 px-4 border-b">Request for Maintenance</td>
                    <td class="py-2 px-4 border-b">2024-08-16</td>
                    <td class="py-2 px-4 border-b">Pending</td>
                    <td class="py-2 px-4 border-b">
                        <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">View</button>
                        <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Cancel</button>
                    </td>
                </tr>

                <!-- Example Row 2 -->
                <tr>
                    <td class="py-2 px-4 border-b">002</td>
                    <td class="py-2 px-4 border-b">Request for Upgrade</td>
                    <td class="py-2 px-4 border-b">2024-08-15</td>
                    <td class="py-2 px-4 border-b">Approved</td>
                    <td class="py-2 px-4 border-b">
                        <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">View</button>
                        <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Cancel</button>
                    </td>
                </tr>

                <!-- More rows as needed -->
            </tbody>
        </table>
    </div>
@endsection
