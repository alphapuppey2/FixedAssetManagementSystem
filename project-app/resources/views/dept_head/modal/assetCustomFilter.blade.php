<div id="assetFilterModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-lg">
        <h2 class="text-2xl font-semibold mb-6 text-center">Create Custom Report</h2>

        <!-- Asset Filters -->
        <div class="space-y-4 mb-6">
            <p class="text-sm font-medium text-gray-700">Select Fields to Include:</p>
            <div class="grid grid-cols-2 gap-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="name" class="text-blue-500">
                    <span>Name</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="code" class="text-blue-500">
                    <span>Code</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="purchase_date" class="text-blue-500">
                    <span>Purchase Date</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="usage_lifespan" class="text-blue-500">
                    <span>Usage Lifespan</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="salvage_value" class="text-blue-500">
                    <span>Salvage Value</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="purchase_cost" class="text-blue-500">
                    <span>Purchase Cost</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="depreciation" class="text-blue-500">
                    <span>Depreciation</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="status" class="text-blue-500">
                    <span>Status</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="ctg_ID" class="text-blue-500">
                    <span>Category</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="dept_ID" class="text-blue-500">
                    <span>Department</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="manufacturer_key" class="text-blue-500">
                    <span>Manufacturer</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="model_key" class="text-blue-500">
                    <span>Model</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="filters[]" value="loc_key" class="text-blue-500">
                    <span>Location</span>
                </label>
            </div>
        </div>

        <!-- Date Range Filters -->
        <div class="space-y-4">
            <p class="text-sm font-medium text-gray-700">Select Date Range:</p>
            <select id="dateRange" class="w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>

            <div class="grid grid-cols-2 gap-4 mt-2">
                <div>
                    <label for="startDate" class="block text-sm font-medium">Start Date:</label>
                    <input type="date" id="startDate" class="w-full border border-gray-300 rounded-md px-3 py-2" />
                </div>
                <div>
                    <label for="endDate" class="block text-sm font-medium">End Date:</label>
                    <input type="date" id="endDate" class="w-full border border-gray-300 rounded-md px-3 py-2" />
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-6 space-x-3">
            <button onclick="applyFilters()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Apply
            </button>
            <button onclick="hideAssetFilterModal()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function showAssetFilterModal() {
        document.getElementById('assetFilterModal').classList.remove('hidden');
        preCheckDefaultFields(); // Pre-check fields based on the view
    }

    function hideAssetFilterModal() {
        document.getElementById('assetFilterModal').classList.add('hidden');
    }

    function preCheckDefaultFields() {
        const defaultFields = [
            'name', 'code', 'purchase_date', 'usage_lifespan', 'salvage_value',
            'purchase_cost', 'depreciation', 'status', 'ctg_ID', 'manufacturer_key', 'loc_key'
        ];

        defaultFields.forEach(field => {
            const checkbox = document.querySelector(`input[value="${field}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }

    function applyFilters() {
        const selectedFields = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
            .map(cb => cb.value);
        const dateRange = document.getElementById('dateRange').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (selectedFields.length === 0) {
            alert('Please select at least one field.');
            return;
        }

        fetch('{{ route('generate.custom.report') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                fields: selectedFields,
                dateRange: dateRange,
                startDate: startDate,
                endDate: endDate
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                populateTable(data.data);
                alert(data.message);
            } else {
                alert(data.message);
            }
            hideAssetFilterModal();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to generate report.');
        });
    }

    function populateTable(data) {
        const tableBody = document.querySelector('tbody');
        tableBody.innerHTML = ''; // Clear existing rows

        data.forEach(asset => {
            const row = document.createElement('tr');
            Object.values(asset).forEach(value => {
                const cell = document.createElement('td');
                cell.textContent = value || 'N/A';
                row.appendChild(cell);
            });
            tableBody.appendChild(row);
        });
    }
</script>
