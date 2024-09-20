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
                <form action="{{ route('maintenance.search') }}" method="GET" class="w-full">
                    <input type="hidden" name="tab" value="{{ $tab }}"> <!-- Include the current tab -->
                    <input type="text" name="query" placeholder="Search..." value="{{ $searchQuery }}" class="w-1/2 px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </form>
            </div>

            <!-- Right Section: Download Icon and Create Button -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('maintenance.download', ['tab' => $tab, 'query' => $searchQuery]) }}" class="p-2 text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                    </svg>
                </a>
                <button class="px-3 py-1 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 focus:outline-none flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Create Maintenance
                </button>
            </div>

        </div>

        <!-- Pagination Section -->
        @if($requests instanceof \Illuminate\Pagination\LengthAwarePaginator || $requests instanceof \Illuminate\Pagination\Paginator)
            <div class="flex justify-between items-center mb-4">
                <!-- Number of Items Loaded -->
                <div class="text-gray-600">
                    Showing <span class="font-semibold">{{ $requests->firstItem() }}</span> to <span class="font-semibold">{{ $requests->lastItem() }}</span> of <span class="font-semibold">{{ $requests->total() }}</span> items
                </div>

                <!-- Pagination Buttons -->
                <div class="flex items-center space-x-2">
                    <div class="mr-2 text-gray-500">
                        {{ $requests->appends(['query' => request()->query('query')])->links() }}
                    </div>
                </div>
            </div>
        @endif


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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        @if($tab === 'approved')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved At</th>
                        @elseif($tab === 'denied')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denied By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denied At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        @else
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($requests as $maintenance)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->id ?? 'N/A'}}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->requestor ? $maintenance->requestor_name : 'System-generated' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->asset_code ?? 'N/A'}}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->description ?? 'N/A'}}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->category_name ?? 'N/A'}}</td>
                            @if($tab === 'approved')
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->type ?? 'N/A'}}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->authorized_by ? $maintenance->authorized_by_name : 'System-generated' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($maintenance->authorized_at)->format('Y-m-d h:i A') ?? 'N/A' }}</td>
                            @elseif($tab === 'denied')
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->denied_by_name ?? 'N/A'}}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($maintenance->authorized_at)->format('Y-m-d h:i A') ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->reason ?? 'N/A'}}</td>
                            @else
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $maintenance->location_name ?? 'N/A'}}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($maintenance->requested_at)->format('Y-m-d h:i A') ?? 'N/A' }}</td>
                            <td class="text-sm text-gray-900">
                                    <form action="{{ route('maintenance.approve', $maintenance->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="px-2 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">Approve</button>
                                    </form>
                                    <form id="denyForm_{{ $maintenance->id }}" data-id="{{ $maintenance->id }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="reason" value="N/A"> <!-- Default or dynamic reason input -->
                                        <button type="button" class="denyButton px-2 py-2 bg-red-500 text-white rounded-md hover:bg-red-600" data-action="{{ route('maintenance.deny', $maintenance->id) }}">Deny</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-sm text-gray-500">No maintenance requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="denyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg w-1/3 p-6">
            <h2 class="text-lg font-semibold mb-4">Deny Maintenance Request</h2>
            <form id="denyForm" action="" method="POST">
                @csrf
                <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                <input type="text" name="reason" id="reason" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <div class="mt-4 flex justify-end">
                    <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 mr-2">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const denyButtons = document.querySelectorAll('.denyButton');
            const denyModal = document.getElementById('denyModal');
            const denyForm = document.getElementById('denyForm');
            const cancelBtn = document.getElementById('cancelBtn');

            denyButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const formAction = this.dataset.action; // Get form action URL from data attribute
                    denyForm.action = formAction; // Set form action URL
                    denyModal.classList.remove('hidden'); // Show modal
                });
            });

            cancelBtn.addEventListener('click', function () {
                denyModal.classList.add('hidden'); // Hide modal
            });
        });
    </script>

@endsection
