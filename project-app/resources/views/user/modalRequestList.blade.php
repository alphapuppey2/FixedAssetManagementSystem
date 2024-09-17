<!-- Modal Blade Component: modalRequestList.blade.php -->
<div id="viewModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg mx-auto relative">
        <!-- Close Button (Top-Right) -->
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Modal Content -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Request Details</h2>
        <div class="space-y-4">
            <div>
                <span class="font-semibold text-gray-700">Request ID:</span>
                <span id="modalRequestId" class="text-gray-600"></span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Description:</span>
                <p id="modalDescription" class="text-gray-600"></p>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Asset ID:</span>
                <span id="modalAssetId" class="text-gray-600"></span>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Modal Functionality -->
<script>
    function showModal(requestId, description, assetId) {
        // Set modal content
        document.getElementById('modalRequestId').innerText = requestId;
        document.getElementById('modalDescription').innerText = description;
        document.getElementById('modalAssetId').innerText = assetId;

        // Show modal
        document.getElementById('viewModal').classList.remove('hidden');
        document.getElementById('viewModal').classList.add('flex');  // Flex layout to center it
    }

    function closeModal() {
        // Hide modal
        document.getElementById('viewModal').classList.add('hidden');
        document.getElementById('viewModal').classList.remove('flex');
    }
</script>
