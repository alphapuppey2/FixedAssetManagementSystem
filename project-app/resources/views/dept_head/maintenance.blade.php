<!-- resources/views/dept_head/maintenance.blade.php -->
@extends('layouts.app')

@section('header')
    <h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Maintenance</h2>
@endsection

@section('content')
    <div class="px-6 py-4">
        <!-- Top Section -->
        <div class="flex justify-between items-center mb-4">
            <!-- Search Bar -->
            <div class="flex items-center w-1/2">
                <input type="text" placeholder="Search..." class="w-1/2 px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Right Section: Download Icon and Create Button -->
            <div class="flex items-center space-x-4">
                <button class="p-2 text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                    </svg>
                </button>
                <button class="px-3 py-1 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 focus:outline-none flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Create Maintenance
                </button>
            </div>
        </div>

        <!-- Pagination Section -->
        <div class="flex justify-between items-center mb-4">
            <!-- Number of Items Loaded -->
            <div class="text-gray-600">
                Showing <span class="font-semibold">1-10</span> of <span class="font-semibold">50</span> items
            </div>

            <!-- Pagination Buttons -->
            <div class="flex items-center space-x-1">
                <button class="px-3 py-1 border rounded-md hover:bg-gray-200">1</button>
                <button class="px-3 py-1 border rounded-md hover:bg-gray-200">2</button>
                <button class="px-3 py-1 border rounded-md hover:bg-gray-200">3</button>
                <button class="px-3 py-1 border rounded-md hover:bg-gray-200">...</button>
                <button class="px-3 py-1 border rounded-md hover:bg-gray-200">Next</button>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="mb-4 flex justify-end">
            <ul class="flex border-b">
                <li class="mr-4">
                    <a href="{{ route('maintenance') }}" class="inline-block px-4 py-2 {{ $tab === 'requests' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Requests</a>
                </li>
                <li class="mr-4">
                    <a href="{{ route('maintenance.approved') }}" class="inline-block px-4 py-2 {{ $tab === 'approved' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Approved</a>
                </li>
                <li class="mr-4">
                    <a href="{{ route('maintenance.denied') }}" class="inline-block px-4 py-2 {{ $tab === 'denied' ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Denied</a>
                </li>
            </ul>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded-md">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requestor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested At</th>
                        @if($tab === 'approved')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved At</th>
                        @elseif($tab === 'denied')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denied By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denied At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        @else
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($requests as $maintenance)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->id?? 'N/A'}}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->requestor ?? 'N/A'}}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->asset_key ?? 'N/A'}}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->description ?? 'N/A'}}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->category ?? 'N/A'}}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($maintenance->requested_at)->format('Y-m-d h:i A') ?? 'N/A' }}</td>
                        @if($tab === 'approved')
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->approved_by ?? 'N/A'}}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->approved_at ?? 'N/A'}}</td>
                        @elseif($tab === 'denied')
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->denied_by ?? 'N/A'}}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->denied_at ?? 'N/A'}}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->reason ?? 'N/A'}}</td>
                        @else
                        <td class="px-6 py-4 text-sm text-gray-900">
                                <form action="{{ route('maintenance.approve', $maintenance->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="px-2 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">Approve</button>
                                </form>
                                <form action="{{ route('maintenance.deny', $maintenance->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="reason" value="N/A"> <!-- Default or dynamic reason input -->
                                    <button type="submit" class="px-2 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Deny</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-sm text-gray-900 text-center">No records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
