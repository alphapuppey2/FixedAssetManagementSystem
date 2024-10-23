
<div id="deleteModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl font-semibold mb-4">Confirm Deletion</h2>
        <p>Are you sure you want to delete this asset?</p>

        <div class="flex justify-end gap-2 mt-4">
            <button type="button" id="cancelDeleteBtn" class="px-4 py-2 bg-gray-500 text-white rounded-md">
                Cancel
            </button>

            <form id="deleteForm" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>

            <button type="button" id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-md">
                Delete
            </button>
        </div>
    </div>
</div>
