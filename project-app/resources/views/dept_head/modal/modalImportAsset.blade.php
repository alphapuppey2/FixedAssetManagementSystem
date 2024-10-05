<!-- Modal Structure -->
<div id="importModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-[300px] shadow-lg">
        <h3 class="text-lg font-semibold mb-4">Import Asset</h3>

        <!-- Upload CSV Button -->
        <div class="mb-4">
            <label for="csvUpload" class="block mb-2 text-sm font-medium text-gray-700">Upload CSV File:</label>
            <input type="file" id="csvUpload" accept=".csv" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" />
        </div>

        <!-- Download Template Button -->
        <div class="mb-4">
            <a href="{{ route('download.csv.template') }}" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md text-center">
                Download CSV Template
            </a>
        </div>

        <!-- Close Button -->
        <div class="flex justify-end">
            <button id="closeModalBtn" class="text-gray-600 bg-gray-200 px-4 py-2 rounded-md hover:bg-gray-300">
                Close
            </button>
        </div>
    </div>
</div>
