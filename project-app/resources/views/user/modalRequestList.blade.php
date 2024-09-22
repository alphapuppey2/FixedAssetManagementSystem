<div id="viewModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl mx-auto relative overflow-y-auto max-h-screen">
        <!-- Close Button (Top-Right) -->
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Modal Content -->
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h2 class="text-3xl font-bold text-gray-800">Asset Details: <span id="modalAssetCode"></span></h2>
        </div>

        <!-- Image and QR Code Section -->
        <div class="flex flex-col lg:flex-row items-start space-y-6 lg:space-y-0 lg:space-x-12 mb-12">
            <div class="w-full lg:w-1/2">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Asset Image & QR Code</h3>
                <div class="flex space-x-8">
                    <!-- Asset Image -->
                    <div class="imagepart relative w-48 h-48 border border-gray-300 rounded-lg overflow-hidden shadow-md">
                        <img id="modalAssetImage" src="" class="w-full h-full object-cover" alt="Asset Image">
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
            <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Additional Information</h3>
                <div class="space-y-4 text-gray-600">
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
                        <span id="modalAssetStatus" class="inline-block px-3 py-1 rounded-full text-xs font-semibold"></span> <!-- Status Badge -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Reason for Request Section -->
        <div class="bg-gray-100 p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Reason for Request</h3>
            <p id="modalRequestReason" class="text-gray-600"></p>
        </div>

        <!-- Request Status Section -->
        <div class="bg-gray-100 p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Request Status</h3>
            <span id="modalRequestStatus" class="inline-block px-2 py-1 rounded-full text-xs font-semibold"></span>
        </div>

        <!-- Cancel Button (Only show if request is pending) -->
        <div id="cancelRequestButton" class="mt-6 text-right">
            <form action="" method="POST" id="cancelRequestForm">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                    Cancel Request
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function showModal(assetCode, assetImage, assetName, assetCost, assetDepreciation, assetSalvage, assetCategory, assetLifespan, assetModel, assetManufacturer, assetLocation, assetStatus, requestReason, requestId, requestStatus) {
        // Set modal content
        document.getElementById('modalAssetCode').innerText = assetCode;
        document.getElementById('modalAssetImage').src = assetImage;
        document.getElementById('modalAssetName').innerText = assetName;
        document.getElementById('modalAssetCost').innerText = assetCost;
        document.getElementById('modalAssetDepreciation').innerText = assetDepreciation + "%";
        document.getElementById('modalAssetSalvage').innerText = assetSalvage;
        document.getElementById('modalAssetCategory').innerText = assetCategory;
        document.getElementById('modalAssetLifespan').innerText = assetLifespan + " years";
        document.getElementById('modalAssetModel').innerText = assetModel;
        document.getElementById('modalAssetManufacturer').innerText = assetManufacturer;
        document.getElementById('modalAssetLocation').innerText = assetLocation;
        document.getElementById('modalRequestReason').innerText = requestReason;

        // Set the request status in the modal
        const requestStatusElement = document.getElementById('modalRequestStatus');
        requestStatusElement.innerText = requestStatus;
        requestStatusElement.className = 'inline-block px-2 py-1 rounded-full text-xs font-semibold'; // Reset classes
        if (requestStatus === 'approved') {
            requestStatusElement.classList.add('bg-green-100', 'text-green-800');
        } else if (requestStatus === 'pending') {
            requestStatusElement.classList.add('bg-yellow-100', 'text-yellow-800');
        } else if (requestStatus === 'cancelled') {
            requestStatusElement.classList.add('bg-gray-200', 'text-gray-600');
        } else if (requestStatus === 'denied') {
            requestStatusElement.classList.add('bg-red-100', 'text-red-800');
        } else if (requestStatus === 'in_progress') {
            requestStatusElement.classList.add('bg-blue-100', 'text-blue-800');
        } else {
            requestStatusElement.classList.add('bg-purple-100', 'text-purple-800');
        }

        // Apply distinct class for the asset status
        const statusElement = document.getElementById('modalAssetStatus');
        statusElement.innerText = assetStatus;
        statusElement.className = 'inline-block px-3 py-1 rounded-full text-xs font-semibold'; // Reset classes
        if (assetStatus === 'active') {
            statusElement.classList.add('bg-green-100', 'text-green-800');
        } else if (assetStatus === 'under maintenance') {
            statusElement.classList.add('bg-yellow-100', 'text-yellow-800');
        } else if (assetStatus === 'deployed') {
            statusElement.classList.add('bg-blue-100', 'text-blue-800');
        } else if (assetStatus === 'dispose') {
            statusElement.classList.add('bg-red-100', 'text-red-800');
        }

        // Check if the request is pending, then show the Cancel button
        if (requestStatus === 'pending') {
            document.getElementById('cancelRequestButton').classList.remove('hidden');
            document.getElementById('cancelRequestForm').action = `/requests/cancel/${requestId}`;  // Set the form action to cancel the request
        } else {
            document.getElementById('cancelRequestButton').classList.add('hidden');
        }

        // Show modal
        document.getElementById('viewModal').classList.remove('hidden');
        document.getElementById('viewModal').classList.add('flex');
    }

    function closeModal() {
        // Hide modal
        document.getElementById('viewModal').classList.add('hidden');
        document.getElementById('viewModal').classList.remove('flex');
    }
</script>
