<!-- resources/views/customReport.blade.php -->
@extends('layouts.app')

@section('header')
    <h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Generate Custom Asset Report</h2>
@endsection

@section('content')
<div class="container mx-auto px-8 py-10">
    <div class="bg-white p-10 rounded-lg shadow-md">
        <div class="flex justify-between items-center border-b pb-4 mb-6">
            <h3 class="text-2xl font-semibold">Customize Your Report</h3>
            <button type="button" onclick="submitForm()" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded">
                Generate Report
            </button>
        </div>

        <form id="customReportForm" action="{{ route('custom.report.generate') }}" method="GET" class="space-y-8">
            <!-- Date Range -->
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <label for="start_date" class="block text-sm font-medium mb-2">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium mb-2">End Date:</label>
                    <input type="date" id="end_date" name="end_date" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>

            <!-- Checkbox Fields -->
            <div>
                <h4 class="text-xl font-semibold mb-4">Select Fields to Include:</h4>
                <label class="flex items-center space-x-3 mb-4">
                    <input type="checkbox" id="selectAll" checked 
                        class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="text-sm font-medium">Select All</span>
                </label>

                <div class="grid grid-cols-1 gap-6">
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
                                class="field-checkbox w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="text-sm font-medium">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Generate Report Button -->
            <!-- <div class="flex justify-end mt-6">
                <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded">
                    Generate Report
                </button>
            </div> -->
        </form>
    </div>
</div>

<div id="reportResult" class="mt-8"></div>

<script>
    // JavaScript for Select All functionality
    const selectAll = document.getElementById('selectAll');
    const fieldCheckboxes = document.querySelectorAll('.field-checkbox');

    selectAll.addEventListener('change', (e) => {
        fieldCheckboxes.forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
    });

    fieldCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            // If all checkboxes are checked, check "Select All" checkbox
            if (Array.from(fieldCheckboxes).every(cb => cb.checked)) {
                selectAll.checked = true;
            } else {
                selectAll.checked = false;
            }
        });
    });

    function generateReport() {
        const selectedFields = Array.from(document.querySelectorAll('input[name="fields[]"]:checked'))
            .map(cb => cb.value);

        if (selectedFields.length === 0) {
            showToast('At least one field must be selected.');
            return;
        }

        fetch('{{ route('custom.report.generate') }}', {
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
            alert('Please select both start and end dates.');
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