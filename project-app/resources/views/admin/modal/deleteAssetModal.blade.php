<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-3">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Delete Confirmation</h2>
        <p id="deleteMessage" class="text-gray-600 mb-6">Are you sure you want to delete <span id="assetCount">0</span> asset/s?</p>
        <div class="flex justify-end space-x-2">
            <button id="cancelDeleteBtn" class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">Cancel</button>
            <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Delete</button>
        </div>
    </div>
</div>
