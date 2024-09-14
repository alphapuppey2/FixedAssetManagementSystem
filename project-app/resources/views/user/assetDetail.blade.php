@extends('user.home')

@section('section')
    <div class="container mx-auto p-6 bg-gray-100 rounded-lg">
        <!-- Title Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-semibold text-gray-900">Asset   &gt {{ $retrieveData->code }}</h2>
            </div>
        </div>

        <!-- Image and QR Code Section -->
        <div class="flex items-start space-x-12 mb-8">
            <div class="w-1/2">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Asset Image & QR Code</h3>
                <div class="flex space-x-6">
                    <!-- Asset Image -->
                    <div class="imagepart relative w-32 h-32">
                        <label class="block w-full h-full">
                            <img src="{{ asset('storage/' . $retrieveData->image ?? 'images/defaultICON.png') }}"
                                 class="w-full h-full object-cover rounded-md border-2 border-gray-300" alt="Asset Image">
                        </label>
                    </div>
                    <!-- QR Code -->
                    <div class="qrContainer flex flex-col items-center">
                        <div class="QRBOX w-24 h-24 bg-red-300"></div>
                        <a href="#" target="_blank" class="text-blue-600 mt-2">Print QR Code</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Information Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-10">
            <div>
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">General Information</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-3">Name:</span>
                        <span class="font-semibold">{{ $retrieveData->name }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-3">Cost:</span>
                        <span class="font-semibold">${{ number_format($retrieveData->cost, 2) }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-3">Depreciation:</span>
                        <span class="font-semibold">{{ $retrieveData->depreciation }}%</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-3">Salvage Value:</span>
                        <span class="font-semibold">${{ number_format($retrieveData->salvageVal, 2) }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-3">Category:</span>
                        <span class="font-semibold">{{ $retrieveData->category }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-3">Lifespan:</span>
                        <span class="font-semibold">{{ $retrieveData->usage_Lifespan }} years</span>
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
            <div>
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Additional Information</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-3">Model:</span>
                        <span class="font-semibold">{{ $retrieveData->model }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-3">Manufacturer:</span>
                        <span class="font-semibold">{{ $retrieveData->manufacturer }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-3">Location:</span>
                        <span class="font-semibold">{{ $retrieveData->location }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-3">Status:</span>
                        <span class="font-semibold capitalize">{{ $retrieveData->status }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-3">Last Used:</span>
                        <span class="font-semibold">NONE</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Repair Request Section -->
        <div class="mt-10 text-left">
            <button id="requestRepairButton" class="bg-blue-700 text-white px-6 py-3 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all">
                Request Repair
            </button>
        </div>

        <!-- Repair Request Form (Initially Hidden) -->
        <div id="repairRequestForm" class="bg-white shadow-md p-8 rounded-lg mt-10 hidden">
            <h3 class="text-2xl font-semibold mb-6 text-gray-800">Reason for Repair</h3>
            <form action="{{ route('repair.request') }}" method="POST">
                @csrf
                <input type="hidden" name="asset_id" value="{{ $retrieveData->id }}">

                <div class="mb-6">
                    <label for="issue_description" class="block text-sm font-medium text-gray-700 mb-2">Describe the issue</label>
                    <textarea id="issue_description" name="issue_description" rows="4" class="w-full p-4 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" placeholder="Enter the issue details"></textarea>
                </div>

                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-400 transition-all">
                    Submit Request
                </button>
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
