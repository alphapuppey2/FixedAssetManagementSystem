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
                @foreach ($requests as $request)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $request->asset_key }}</td>
                        <td class="py-2 px-4 border-b">{{ $request->description }}</td>
                        <td class="py-2 px-4 border-b">{{ $request->requested_at }}</td>
                        <td class="py-2 px-4 border-b">{{ $request->completion ? 'Completed' : 'Pending' }}</td>
                        <td class="py-2 px-4 border-b">
                            <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Edit</button>
                            <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-3/4 md:w-1/2">
            <h2 class="text-xl font-semibold mb-4">Edit Request</h2>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PATCH') <!-- or POST based on your route -->
                <input type="hidden" id="requestId" name="request_id">

                <div class="mb-4">
                    <label for="asset_key" class="block text-gray-700">Asset ID</label>
                    <input type="text" id="asset_key" name="asset_key" class="border border-gray-300 rounded-lg px-4 py-2 w-full bg-gray-200 cursor-not-allowed" readonly>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Reason</label>
                    <input type="text" id="description" name="description" class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                </div>
                <div class="flex justify-end">
                    <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('button.bg-blue-500');
            const modal = document.getElementById('editModal');
            const closeModalButton = document.getElementById('closeModal');
            const form = document.getElementById('editForm');
            
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const assetKey = row.querySelector('td:nth-child(1)').textContent;
                    const description = row.querySelector('td:nth-child(2)').textContent;
                    const requestId = row.getAttribute('data-id'); // Ensure you set this in your Blade template

                    // Populate the form fields
                    document.getElementById('asset_key').value = assetKey;
                    document.getElementById('description').value = description;
                    document.getElementById('requestId').value = requestId;

                    // Show the modal
                    modal.classList.remove('hidden');
                });
            });

            closeModalButton.addEventListener('click', function() {
                modal.classList.add('hidden');
            });

            // Optionally close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>

@endsection
