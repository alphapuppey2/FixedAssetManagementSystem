@extends('user.home')

@section('section')
    <div class="container mx-auto p-8">
        <!-- Title Section -->
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h2 class="text-3xl font-bold text-gray-800">Asset Details: {{ $retrieveData->code }}</h2>
        </div>

        <!-- Image and QR Code Section -->
        <div class="flex flex-col lg:flex-row items-start space-y-6 lg:space-y-0 lg:space-x-12 mb-12">
            <div class="w-full lg:w-1/2">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Asset Image & QR Code</h3>
                <div class="flex space-x-8">
                    <!-- Asset Image -->
                    <div class="imagepart relative w-48 h-48 border border-gray-300 rounded-lg overflow-hidden shadow-md">
                        <img src="{{ asset('storage/' . $retrieveData->image ?? 'images/defaultICON.png') }}"
                             class="w-full h-full object-cover" alt="Asset Image">
                    </div>
                    <!-- QR Code -->
                    <div class="qrContainer flex flex-col items-center">
                        <div class="QRBOX w-32 h-32 bg-gray-200 rounded-lg shadow-md flex items-center justify-center">
                            <!-- Placeholder for QR code -->
                            <span class="text-gray-500">QR Code</span>
                        </div>
                        <a href="#" target="_blank" class="text-blue-600 mt-4 hover:underline">
                            Print QR Code
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Information Section -->
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
                        <span>${{ number_format($retrieveData->cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Depreciation:</span>
                        <span>{{ $retrieveData->depreciation }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Salvage Value:</span>
                        <span>${{ number_format($retrieveData->salvageVal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Category:</span>
                        <span>{{ $retrieveData->category }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Lifespan:</span>
                        <span>{{ $retrieveData->usage_Lifespan }} years</span>
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
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
                        <span class="capitalize">{{ $retrieveData->status }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Last Used:</span>
                        <span>NONE</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Repair Request Section -->
        <div class="flex justify-end mb-12">
            <button id="requestRepairButton" class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                Request Repair
            </button>
        </div>

        <!-- Repair Request Form (Initially Hidden) -->
        <div id="repairRequestForm" class="bg-gray-50 shadow-md p-8 rounded-lg hidden">
            <h3 class="text-xl font-semibold mb-6 text-gray-800">Reason for Repair</h3>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Display Success Message -->
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('repair.request') }}" method="POST">
                @csrf
                <input type="hidden" name="asset_id" value="{{ $retrieveData->id }}">

                <div class="mb-6">
                    <label for="issue_description" class="block text-sm font-medium text-gray-700 mb-2">Describe the issue</label>
                    <textarea id="issue_description" name="issue_description" rows="4" class="w-full p-4 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" placeholder="Enter the issue details" required>{{ old('issue_description') }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>

        <!-- JavaScript to Toggle Repair Form -->
        <script>
            document.getElementById('requestRepairButton').addEventListener('click', function () {
                var repairForm = document.getElementById('repairRequestForm');
                repairForm.classList.toggle('hidden');
            });
        </script>
    </div>
@endsection
