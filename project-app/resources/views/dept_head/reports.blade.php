<!-- resources/views/dept_head/reports.blade.php -->
@extends('layouts.app')

@section('header')
    <h2 class="my-3 font-semibold text-2xl text-gray-800 leading-tight">Reports</h2>
@endsection

@section('content')
    <div class="px-6 py-4">
        <!-- Top Section -->
        <div class="flex justify-between items-center mb-6">
            <!-- Search Bar -->
            <div class="flex items-center w-1/2">
                <form action="" method="GET" class="w-full">
                    <input type="hidden" name="tab" value=""> <!-- Include the current tab -->
                    <input type="text" name="query" placeholder="Search..." value="" class="w-1/2 px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </form>
            </div>

            <!-- Right Section: Download Icon and Customize Report Button -->
            <div class="flex items-center space-x-4">
                <form action="{{ route(Route::currentRouteName()) }}" method="GET">
                    {{-- <input type="hidden" name="query" value="{{ $searchQuery }}"> <!-- Preserve the current search query --> --}}
                    <button id="refreshButton" class="p-2 text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </form>
                <a href="#" class="p-2 text-gray-700 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                    </svg>
                </a>
                <button onclick="openModal()" class="px-3 py-1 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 focus:outline-none flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Customize Report
                </button>
            </div>
        </div>

        <!-- Rows per page and Pagination Section -->
        <div class="flex justify-between items-center mb-4">
            <!-- Rows per page dropdown (on the left) -->
            <div class="flex items-center">
                <label for="rows_per_page" class="mr-2 text-gray-700">Rows per page:</label>
                <form action="" method="GET" id="rowsPerPageForm" class="flex items-center">
                    <input type="hidden" name="query" value="{{ request('query') }}"> <!-- Retain other query parameters -->
                    <select name="rows_per_page" id="rows_per_page" class="border rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="document.getElementById('rowsPerPageForm').submit()">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </form>
            </div>

            <!-- Pagination (on the right) -->
            <div class="ml-auto">
                {{ $assetData->appends(['rows_per_page' => $perPage, 'query' => request('query')])->links() }} <!-- Pagination Links -->
            </div>
        </div>

<!-- Table Section -->
<div class="overflow-x-auto">
    <table class="min-w-full bg-white border rounded-md">
        <thead class="bg-gray-100 border-b">
            <tr>
                @foreach($selectedColumns as $column)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ ucfirst(str_replace('_', ' ', $column)) }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($assetData as $row)
                <tr class="hover:bg-gray-50">
                    @foreach($selectedColumns as $column)
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($column == 'ctg_ID')
                                {{ $row->category_name ?? 'N/A' }}
                            @elseif($column == 'dept_ID')
                                {{ $row->department_name ?? 'N/A' }}
                            @elseif($column == 'manufacturer_key')
                                {{ $row->manufacturer_name ?? 'N/A' }}
                            @elseif($column == 'model_key')
                                {{ $row->model_name ?? 'N/A' }}
                            @elseif($column == 'loc_key')
                                {{ $row->location_name ?? 'N/A' }}
                            @elseif($column == 'status')
                                {{ ucfirst($row->status) }}
                            @else
                                {{ $row->$column }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


    </div>

<!-- Modal for Custom Report -->
<div id="customReportModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900 bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white w-full max-w-5xl rounded-lg shadow-lg p-8"> <!-- Increased max-width to 5xl and added padding -->
            <h3 class="text-xl font-semibold mb-4">Customize Report</h3>
            <p class="mb-4 text-gray-600">Select the columns you want to include in the report:</p>

            <!-- Columns Selection -->
            <form id="customReportForm">
                <div class="grid grid-cols-2 gap-x-8 gap-y-4 mb-6"> <!-- Reduced the horizontal gap to 8 -->
                    <div>
                        <!-- First Column: General columns -->
                        <input type="checkbox" id="selectAllColumns" class="mr-2" onclick="toggleSelectAll(this)">
                        <label for="selectAllColumns" class="text-gray-700 font-semibold">Select All</label>

                        <div class="mt-2">
                            @foreach(['id', 'name', 'image', 'code', 'cost', 'depreciation', 'salvageVal', 'usage_Lifespan', 'created_at', 'updated_at', 'qr', 'purchase_date', 'custom_fields'] as $column)
                                <div>
                                    <input type="checkbox" id="{{ $column }}" name="columns[]" value="{{ $column }}" class="mr-2 column-checkbox">
                                    <label for="{{ $column }}" class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $column)) }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <!-- Second Column: Dropdowns for key-related columns -->
                        <div class="grid grid-cols-1 gap-2"> <!-- Reduced the vertical gap -->
                            <!-- Status -->
                            <div class="flex items-center">
                                <label class="text-gray-700 w-1/3">Status</label>
                                <select name="status[]" class="form-control status-select w-2/3" multiple="multiple">
                                    <option value="all">All</option>
                                    @foreach(['active', 'deployed', 'need_repair', 'under_maintenance', 'disposed'] as $status)
                                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Category -->
                            <div class="flex items-center">
                                <label class="text-gray-700 w-1/3">Category</label>
                                <select name="ctg_ID[]" class="form-control category-select w-2/3" multiple="multiple">
                                    <option value="all">All</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Manufacturer -->
                            <div class="flex items-center">
                                <label class="text-gray-700 w-1/3">Manufacturer</label>
                                <select name="manufacturer_key[]" class="form-control manufacturer-select w-2/3" multiple="multiple">
                                    <option value="all">All</option>
                                    @foreach($manufacturers as $manufacturer)
                                        <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Model -->
                            <div class="flex items-center">
                                <label class="text-gray-700 w-1/3">Model</label>
                                <select name="model_key[]" class="form-control model-select w-2/3" multiple="multiple">
                                    <option value="all">All</option>
                                    @foreach($models as $model)
                                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Location -->
                            <div class="flex items-center">
                                <label class="text-gray-700 w-1/3">Location</label>
                                <select name="loc_key[]" class="form-control location-select w-2/3" multiple="multiple">
                                    <option value="all">All</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100">Cancel</button>
                    <button type="button" onclick="saveColumns()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Include Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />

<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>


<script>
    function openModal() {
        document.getElementById('customReportModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('customReportModal').classList.add('hidden');
    }

    function saveColumns() {
    const formData = new FormData(document.getElementById('customReportForm'));

    // Check if at least one checkbox is selected
    const selectedCheckboxes = document.querySelectorAll('.column-checkbox:checked');
    if (selectedCheckboxes.length === 0) {
        alert('No columns selected.');
        return;
    }

    // Add the selected dropdown values to formData as columns if they are not empty
    const dropdowns = ['status', 'ctg_ID', 'dept_ID', 'manufacturer_key', 'model_key', 'loc_key'];
    dropdowns.forEach(dropdown => {
        const selectedOptions = Array.from(document.querySelectorAll(`select[name="${dropdown}[]"] option:checked`))
            .map(option => option.value)
            .filter(value => value !== 'all'); // Ignore "All" option
        if (selectedOptions.length > 0) {
            formData.append('columns[]', dropdown); // Add the field as a column if selected
        }
    });

    fetch('/save-report-columns', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the table based on the selected columns
            updateTable(data.columns);
            closeModal();
        } else {
            alert('Failed to save columns.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving columns.');
    });
}


    function updateTable(columns) {
        // Clear the current table headers and rows
        const tableHead = document.querySelector('table thead tr');
        tableHead.innerHTML = '';

        // Add new headers based on the selected columns
        columns.forEach(column => {
            const th = document.createElement('th');
            th.className = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider';
            th.textContent = column.replace('_', ' ').toUpperCase();
            tableHead.appendChild(th);
        });

        // You may also want to clear and update the table body based on the new columns
        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = '';
        // You can add logic here to load the data for the selected columns
    }

    function toggleSelectAll(selectAllCheckbox) {
        const checkboxes = document.querySelectorAll('.column-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }
</script>

<script>
    $(document).ready(function() {
        // Initialize Select2 for multi-select dropdowns
        $('.status-select, .category-select, .department-select, .manufacturer-select, .model-select, .location-select').select2({
            placeholder: "Select an option",
            allowClear: true
        });

        // Handle "All" selection
        $('.status-select, .category-select, .department-select, .manufacturer-select, .model-select, .location-select').on('change', function() {
            const selectAllValue = "all";
            if ($(this).val().includes(selectAllValue)) {
                $(this).val($(this).find('option').map((i, option) => $(option).val()).get()).trigger('change');
            }
        });
    });
</script>



@endsection
