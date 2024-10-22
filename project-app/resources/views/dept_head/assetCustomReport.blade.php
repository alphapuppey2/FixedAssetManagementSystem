<!-- resources/views/customReport.blade.php -->
@extends('layouts.app')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery (Select2 depends on jQuery) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


@section('header')
{{-- <h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Generate Custom Asset Report</h2> --}}
<h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight text-center md:text-left"> <!-- Responsive update -->
    Generate Custom Asset Report
</h2>
@endsection

@section('content')
{{-- <div class="container mx-auto px-8 py-10">
    <div class="bg-white p-10 rounded-lg shadow-md"> --}}
<div class="container mx-auto px-4 md:px-8 py-6 md:py-10"> <!-- Responsive update -->
    <div class="bg-white p-6 md:p-10 rounded-lg shadow-md"> <!-- Responsive update -->
        <!-- Toast Notification -->
        <div id="toast"
            class="hidden fixed bottom-5 right-5 bg-red-500 text-white px-4 py-2 rounded-md shadow-md transition-opacity duration-300 z-50">
            <span id="toast-message"></span>
        </div>

        {{-- <div class="flex justify-between items-center border-b pb-4 mb-6">
            <h3 class="text-2xl font-semibold">Customize Your Report</h3> --}}
        <div class="flex flex-col md:flex-row justify-between items-center md:items-center border-b pb-4 mb-6"> <!-- Responsive update -->
            <h3 class="text-xl md:text-2xl font-semibold mb-4 md:mb-0"> <!-- Responsive update -->
                Customize Your Report
            </h3>
            <button type="button" onclick="submitForm()"
                {{-- class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded"> --}}
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 md:px-5 py-2 rounded"> <!-- Responsive update -->
                Generate Report
            </button>
        </div>

        <form id="customReportForm" action="{{ route('asset.report.generate') }}" method="GET" class="space-y-6 md:space-y-8">
            <!-- Date Range -->
            {{-- <div class="grid grid-cols-2 gap-8"> --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">
                <div>
                    <label for="start_date" class="block text-sm font-medium mb-2">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" required
                        {{-- class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" /> --}}
                        class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium mb-2">End Date:</label>
                    <input type="date" id="end_date" name="end_date" required
                        {{-- class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" /> --}}
                        class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>

            {{-- <div class="grid grid-cols-2 gap-8"> --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">
                <!-- Checkbox Fields Section -->
                <div>
                    {{-- <h4 class="text-xl font-semibold mb-4">Select Fields to Include:</h4> --}}
                    <h4 class="text-lg md:text-xl font-semibold mb-4">Select Fields to Include:</h4>
                    <label class="flex items-center space-x-3 mb-4">
                        <input type="checkbox" id="selectAll" checked
                            {{-- class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"> --}}
                            class="w-4 md:w-5 h-4 md:h-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"> <!-- Responsive update -->
                        <span class="text-sm font-medium">Select All</span>
                    </label>
                    {{-- <div class="grid grid-cols-1 gap-6"> --}}
                    <div class="grid grid-cols-1 gap-3 md:gap-6">
                        @foreach([
                        'id' => 'ID',
                        'name' => 'Name',
                        'asst_img' => 'Asset Image',
                        'qr_img' => 'QR Image',
                        'code' => 'Code',
                        'purchase_date' => 'Purchase Date',
                        'usage_lifespan' => 'Usage Lifespan',
                        'salvage_value' => 'Salvage Value',
                        'purchase_cost' => 'Purchase Cost',
                        'depreciation' => 'Depreciation',
                        'status' => 'Status',
                        'custom_fields' => 'Custom Fields',
                        'ctg_ID' => 'Category',
                        'dept_ID' => 'Department',
                        'manufacturer_key' => 'Manufacturer',
                        'model_key' => 'Model',
                        'loc_key' => 'Location',
                        'last_used_by' => 'Last Used By',
                        'created_at' => 'Created At',
                        'updated_at' => 'Updated At',
                        'isDeleted' => 'Is Deleted'
                        ] as $field => $label)
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="fields[]" value="{{ $field }}" checked
                                {{-- class="field-checkbox w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"> --}}
                                class="field-checkbox w-4 md:w-5 h-4 md:h-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"> <!-- Responsive update -->
                            <span class="text-sm font-medium">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Filters Section -->
                {{-- <div class="border rounded-lg p-6 bg-gray-50"> --}}
                <div class="border rounded-lg p-4 md:p-6 bg-gray-50"> <!-- Responsive update -->
                    {{-- <h4 class="text-xl font-semibold mb-4">Apply Filters</h4> --}}
                    <h4 class="text-lg md:text-xl font-semibold mb-4">Apply Filters</h4>

                    <div class="space-y-4">
                        <!-- Status Filter -->
                        <label for="status" class="block text-sm font-medium">Status:</label>
                        <select id="status" name="status[]" class="select2" multiple="multiple" style="width: 100%;">
                            <option value="active">Active</option>
                            <option value="deployed">Deployed</option>
                            <option value="under_maintenance">Under Maintenance</option>
                            <option value="disposed">Disposed</option>
                        </select>

                        <!-- Category Filter -->
                        <label for="category" class="block text-sm font-medium">Category:</label>
                        <select id="category" name="category[]" class="select2" multiple="multiple" style="width: 100%;">
                            @foreach($categoryOptions as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <!-- Manufacturer Filter -->
                        <label for="manufacturer" class="block text-sm font-medium">Manufacturer:</label>
                        <select id="manufacturer" name="manufacturer[]" class="select2" multiple="multiple" style="width: 100%;">
                            @foreach($manufacturerOptions as $manufacturer)
                            <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                            @endforeach
                        </select>

                        <!-- Model Filter -->
                        <label for="model" class="block text-sm font-medium">Model:</label>
                        <select id="model" name="model[]" class="select2" multiple="multiple" style="width: 100%;">
                            @foreach($modelOptions as $model)
                            <option value="{{ $model->id }}">{{ $model->name }}</option>
                            @endforeach
                        </select>

                        <!-- Location Filter -->
                        <label for="location" class="block text-sm font-medium">Location:</label>
                        <select id="location" name="location[]" class="select2" multiple="multiple" style="width: 100%;">
                            @foreach($locationOptions as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });

        const selectAll = document.getElementById('selectAll');
        const fieldCheckboxes = document.querySelectorAll('.field-checkbox');

        selectAll.addEventListener('change', (e) => {
            fieldCheckboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });

        fieldCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                selectAll.checked = Array.from(fieldCheckboxes).every(cb => cb.checked);
            });
        });

        @if(session('error'))
        showToast("{{ session('error') }}");
        @endif
    });

    function showToast(message) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        toastMessage.textContent = message;
        toast.classList.remove('hidden');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }

    function generateReport() {
        const selectedFields = Array.from(document.querySelectorAll('input[name="fields[]"]:checked'))
            .map(cb => cb.value);

        if (selectedFields.length === 0) {
            showToast('At least one field must be selected.');
            return;
        }

        fetch('{{ route('asset.report.generate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        fields: selectedFields
                    })
                })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    populateTable(data.data);
                    showToast(data.message);
                } else {
                    showToast(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to generate report.');
            });
    }

    function submitForm() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        if (!startDate || !endDate) {
            showToast('Please select both start and end dates.');
            return;
        }

        document.getElementById('customReportForm').submit();
    }

    function populateTable(data) {
        const resultDiv = document.getElementById('reportResult');
        resultDiv.innerHTML = ''; // Clear previous results

        const table = document.createElement('table');
        table.classList.add('min-w-full', 'bg-white', 'border', 'rounded-md', 'mt-4');

        const thead = document.createElement('thead');
        thead.classList.add('bg-gray-100', 'border-b');
        const headerRow = document.createElement('tr');

        if (data.length > 0) {
            Object.keys(data[0]).forEach(key => {
                const th = document.createElement('th');
                th.classList.add('px-6', 'py-3', 'text-left');
                th.textContent = key.replace('_', ' ').toUpperCase();
                headerRow.appendChild(th);
            });
        }

        thead.appendChild(headerRow);
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        tbody.classList.add('divide-y', 'divide-gray-200');

        data.forEach(asset => {
            const row = document.createElement('tr');
            Object.values(asset).forEach(value => {
                const td = document.createElement('td');
                td.classList.add('px-6', 'py-4');
                td.textContent = value || 'N/A';
                row.appendChild(td);
            });
            tbody.appendChild(row);
        });

        table.appendChild(tbody);
        resultDiv.appendChild(table);
    }
</script>
@endsection