<div id="viewModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden justify-center items-center z-[999]">
    <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-4xl mx-auto relative overflow-y-auto max-h-[80vh]">
        <!-- Close Button (Top-Right) -->
        <button onclick="closeModal()"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Modal Content -->
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h2 class="text-2xl font-bold text-gray-800">Asset Details: <span id="modalAssetCode"></span></h2>
        </div>

        <!-- Image and QR Code Section -->
        <div class="flex flex-col lg:flex-row items-start space-y-4 lg:space-y-0 lg:space-x-8 mb-6">
            <div class="w-full lg:w-1/2">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Asset Image & QR Code</h3>
                <div class="flex space-x-4">
                    <!-- Asset Image -->
                    <div
                        class="imagepart relative w-36 h-36 border border-gray-300 rounded-lg overflow-hidden shadow-sm">
                        <img id="modalAssetImage"
                            class="w-full h-full object-cover" alt="Asset Image">
                    </div>
                    <!-- QR Code -->
                    <div class="qrContainer flex flex-col items-center">
                        <div class="QRBOX w-32 h-32 bg-gray-200 rounded-lg shadow-md flex items-center justify-center">
                            <img id="modalAssetQr"
                                alt="QR Code" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Information Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">General Information</h3>
                <div class="space-y-2 text-gray-600">
                    <div class="flex justify-between">
                        <span class="font-medium">Name:</span>
                        <span id="modalAssetName"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Cost:</span>
                        <span id="modalAssetCost"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Depreciation:</span>
                        <span id="modalAssetDepreciation"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Salvage Value:</span>
                        <span id="modalAssetSalvage"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Category:</span>
                        <span id="modalAssetCategory"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Lifespan:</span>
                        <span id="modalAssetLifespan"></span>
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
            <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Additional Information</h3>
                <div class="space-y-2 text-gray-600">
                    <div class="flex justify-between">
                        <span class="font-medium">Model:</span>
                        <span id="modalAssetModel"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Manufacturer:</span>
                        <span id="modalAssetManufacturer"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Location:</span>
                        <span id="modalAssetLocation"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Status:</span>
                        <span id="modalAssetStatus"
                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold"></span>
                    </div>
                    <div class="flex flex-col justify-between">
                        <span class="font-medium">Custom Fields </span>
                        <div id="modalAdditionalInfo" class="flex flex-col" class="space-y-2 grid grid-rows-2  bg-blue-500 text-gray-600">
                            @if (!empty($request->custom_fields_array))
                                @foreach ($request->custom_fields_array as $field)
                                   <div class="fieldItem grid grid-cols-[20%_auto]">
                                    <span> {{ ucfirst($field['name']) }}</span>
                                   <span>{{ $field['value'] }}</span>
                                   </div>
                                @endforeach

                            @else
                                <p>No custom fields available.</p>
                            @endif

                        </div>
                    </div>


                </div>
            </div>
        </div>

        <!-- Reason for Request Section -->
        <div class="bg-gray-100 p-4 rounded-lg shadow-sm mb-4">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Reason for Request:</h3>
            <p id="modalRequestReason" class="text-gray-600">{{ $request->description }}</p>
        </div>

        <!-- Request Status Section -->
        <div class="bg-gray-100 p-4 rounded-lg shadow-sm mb-4">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Request Status:</h3>
            <div class="space-y-2">
                <div>
                    <span class="font-medium">Status:</span>
                    <span id="modalRequestStatus"
                        class="inline-block px-2 py-1 rounded-full text-xs font-semibold">{{ $request->status }}</span>
                </div>
                <div id="authorizedByContainer" class="hidden">
                    <span class="font-medium">Authorized by:</span>
                    <span id="modalAuthorizedBy">{{ $request->authorized_at }}</span>
                </div>
                <div id="denialReasonContainer" class="hidden">
                    <span class="font-medium">Reason Denied:</span>
                    <span id="modalDenialReason"></span>
                </div>
            </div>
        </div>

        <!-- Cancel Button (Only show if request is pending) -->
        <div id="cancelRequestButton" class="mt-4 text-right hidden">
            <button type="button" onclick="showDaCancelModal()"
                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                Cancel Request
            </button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function showModal(request) {
        console.log("you clicked the modal" , request);

        // Set modal content
        document.getElementById('modalAssetCode').innerText = request.asset_code;
        document.getElementById('modalAssetImage').src = request.asset_image ? `/storage/${request.asset_image}` :
            '/images/defaultICON.png';
        document.getElementById('modalAssetQr').src = request.qr_code ? `/storage/${request.qr_code}` : '/images/defaultQR.png';
        document.getElementById('modalAssetName').innerText = request.name;
        document.getElementById('modalAssetCost').innerText = request.cost;
        document.getElementById('modalAssetDepreciation').innerText = request.depreciation;
        document.getElementById('modalAssetSalvage').innerText = request.salvageVal;
        document.getElementById('modalAssetStatus').innerText = request.asset_status;
        document.getElementById('modalAssetCategory').innerText = request.category;
        document.getElementById('modalAssetLifespan').innerText = request.usage_Lifespan + " years";
        document.getElementById('modalAssetModel').innerText = request.model;
        document.getElementById('modalAssetManufacturer').innerText = request.manufacturer;
        document.getElementById('modalAssetLocation').innerText = request.location;


        // // Set reason for the request
        document.getElementById('modalRequestReason').innerText = request.description;

        // // Set request status
        document.getElementById('modalRequestStatus').innerText = request.status;

        // // Show or hide sections based on request status
        if (request.status === 'approved') {
            document.getElementById('authorizedByContainer').classList.remove('hidden');
            document.getElementById('modalAuthorizedBy').innerText = request.authorized_by ?? 'N/A';
            document.getElementById('denialReasonContainer').classList.add('hidden');
        } else if (request.status === 'denied') {
            document.getElementById('authorizedByContainer').classList.remove('hidden');
            document.getElementById('modalAuthorizedBy').innerText = request.reason ?? 'N/A';
            document.getElementById('denialReasonContainer').classList.remove('hidden');
            document.getElementById('modalDenialReason').innerText = request.authorized_by ??  'N/A';
        } else {
            document.getElementById('authorizedByContainer').classList.add('hidden');
            document.getElementById('denialReasonContainer').classList.add('hidden');
        }

        // // Show the cancel button only if the request status is "request"
        if (request.status === 'request') {
            document.getElementById('cancelRequestButton').classList.remove('hidden');
            document.getElementById('cancelForm').action = `/requests/cancel/${request.id}`;
        } else {
            document.getElementById('cancelRequestButton').classList.add('hidden');
        }

        // Show the view modal
        document.getElementById('viewModal').classList.remove('hidden');
        document.getElementById('viewModal').classList.add('flex');
        document.addEventListener('keydown', handleEscKey);
    }


    function closeModal() {
        // Hide the view modal
        document.getElementById('viewModal').classList.add('hidden');
        document.getElementById('viewModal').classList.remove('flex');

        document.removeEventListener('keydown', handleEscKey);

    }

    function handleEscKey(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    }

    function showDaCancelModal() {
        // Show the cancel modal
        document.getElementById('cancelRequestModal').classList.remove('hidden');
    }

    function hideDaCancelModal() {
        // Hide the cancel modal
        document.getElementById('cancelRequestModal').classList.add('hidden');
    }
</script>
