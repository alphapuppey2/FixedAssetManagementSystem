@extends('user.home')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Asset Details: {{ $retrieveData->code }}
    </h2>
@endsection

@section('content')
    <div class="container mx-auto p-8">

        <div class="flex flex-col lg:flex-row items-start space-y-6 lg:space-y-0 lg:space-x-12 mb-12">
            <div class="w-full lg:w-1/2">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Asset Image & QR Code</h3>
                <div class="flex space-x-8">
                    <div class="imagepart relative w-48 h-48 border border-gray-300 rounded-lg overflow-hidden shadow-md">
                        <img src="{{ asset($retrieveData->image ? 'storage/' . $retrieveData->image : 'images/no-image.png') }}"
                             class="w-full h-full object-cover" alt="Asset Image">
                    </div>
                    <div class="qrContainer flex flex-col items-center">
                        <div class="QRBOX w-32 h-32 bg-gray-200 rounded-lg shadow-md flex items-center justify-center">
                            <img src="{{ asset('storage/' . $retrieveData->qr) }}" class="w-full h-full object-cover" alt="QR Code">
                        </div>
                        <a href="{{ asset('storage/' . $retrieveData->qr) }}" target="_blank" class="text-blue-600 mt-4 hover:underline">
                            Print QR Code
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
            <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">General Information</h3>
                <div class="space-y-4 text-gray-600">
                    <div class="flex justify-between">
                        <span class="font-medium">Name:</span>
                        <span>{{ $retrieveData->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Cost:</span>
                        <span>₱{{ number_format($retrieveData->cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Depreciation:</span>
                        <span>₱{{ $retrieveData->depreciation }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Salvage Value:</span>
                        <span>₱{{ number_format($retrieveData->salvageVal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Category:</span>
                        <span>{{ $retrieveData->category }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Lifespan:</span>
                        <span>{{ $retrieveData->usage_Lifespan }} years</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Overall Maintenance Cost:</span>
                        @if ($totalMaintenanceCost > 0)
                            ₱{{ number_format($totalMaintenanceCost, 2) }}
                        @else
                            No maintenance history
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Additional Information</h3>
                <div class="space-y-4 text-gray-600">
                    <div class="flex justify-between">
                        <span class="font-medium">Model:</span>
                        <span>{{ $retrieveData->model }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Manufacturer:</span>
                        <span>{{ $retrieveData->manufacturer }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Location:</span>
                        <span>{{ $retrieveData->location }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Status:</span>
                        <span class="capitalize">
                            {{ $retrieveData->status === 'under_maintenance' ? 'Under Maintenance' : ($retrieveData->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Last Used:</span>
                        <span>{{ isset($retrieveData->lub_firstname) && $retrieveData->lub_middlename && $retrieveData->lub_lastname ? $retrieveData->lub_firstname." ".$retrieveData->lub_middlename." ".$retrieveData->lub_lastname :  'N/A'  }}</span>
                    </div>
                    <div class="flex flex-col justify-between">
                        <span class="font-medium">Custom Fields </span>
                        <div id="modalAdditionalInfo" class="space-y-2 text-gray-600">
                            @if (!empty($updatedCustomFields))
                                {{-- {{ $fields }} --}}
                                @foreach ($updatedCustomFields as $field)
                                    <div class="flex justify-between">
                                        <span class="font-medium">{{ $field['name'] }}:</span>
                                        <span>{{ $field['value'] }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p>No additional information available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($retrieveData->status !== 'disposed')
        <div class="flex justify-end mb-12">
            <button id="requestRepairButton" class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                Request Maintenance
            </button>
        </div>
         @endif
        <div id="repairRequestModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden justify-center items-center z-50" role="dialog" aria-hidden="true">
            <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg mx-auto relative sm:max-w-md sm:p-6">
                <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h3 class="text-xl font-semibold mb-6 text-gray-800 text-center sm:text-left">Reason for Request</h3>

                <div id="errorMessages" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"></div>

                <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"></div>

                <form id="repairRequestForm" action="{{ route('maintenance.create') }}" method="POST">
                    @csrf
                    <input type="hidden" name="asset_id" value="{{ $retrieveData->id }}">
                    <div class="mb-6">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type of Request</label>
                        <select id="type" name="type" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                            <option value="">Select Request Type</option>
                            <option value="repair">Repair</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="upgrade">Upgrade</option>
                            <option value="inspection">Inspection</option>
                            <option value="replacement">Replacement</option>
                            <option value="calibration">Calibration</option>
                        </select>
                    </div>

                    <!-- Issue Description -->
                    <div class="mb-6">
                        <label for="issue_description" class="block text-sm font-medium text-gray-700 mb-2">Describe the issue</label>
                        <textarea id="issue_description" name="issue_description" rows="4" class="w-full p-4 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" placeholder="Enter the issue details" required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.getElementById('requestRepairButton').addEventListener('click', function () {
                document.getElementById('repairRequestModal').classList.remove('hidden');
                document.getElementById('repairRequestModal').classList.add('flex');
            });

            function closeModal() {
                document.getElementById('repairRequestModal').classList.add('hidden');
                document.getElementById('repairRequestModal').classList.remove('flex');
            }

        document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('repairRequestForm');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    let errorList = '<ul class="list-disc pl-5 text-red-700">';
                    Object.values(data.errors).forEach(error => {
                        errorList += `<li>${error}</li>`;
                    });
                    errorList += '</ul>';
                    displayMessage(errorList, 'error');
                } else {
                    displayMessage('Maintenance request submitted successfully.', 'success');
                    form.reset();
                }
            })
            .catch(error => console.error('Error:', error));
        });

        function displayMessage(message, type) {
            const messageContainer = document.createElement('div');
            messageContainer.className = type === 'error'
                ? 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'
                : 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
            messageContainer.innerHTML = message;

            const existingMessages = form.querySelectorAll('.bg-red-100, .bg-green-100');
            existingMessages.forEach(msg => msg.remove());

            form.insertAdjacentElement('afterbegin', messageContainer);
        }
    });
        </script>
    </div>
@endsection

