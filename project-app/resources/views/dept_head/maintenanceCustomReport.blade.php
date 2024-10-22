<!-- resources/views/dept_head/maintenanceCustomReport.blade.php -->
@extends('layouts.app')

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery (Select2 depends on jQuery) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@section('header')
<h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight text-center md:text-left">
    Generate Custom Maintenance Report
</h2>
@endsection

@section('content')
<div class="container mx-auto px-4 md:px-8 py-6 md:py-10">
    <!-- Page Instructions Section -->
    <div class="mb-6 p-6 bg-blue-100 rounded-md shadow-md">
        <h3 class="text-lg font-semibold mb-2">Instructions</h3>
        <ul class="list-disc ml-5 text-sm text-gray-700">
            <li>Fill out the <strong>required fields (*)</strong> to generate the report.</li>
            <li>Select the <strong>Date Range</strong> to filter maintenance records by their creation dates.</li>
            <li>Use the <strong>Filters</strong> on the right to refine the search results.</li>
            <li>Select the <strong>fields to display</strong> by checking the relevant boxes.</li>
            <li>Click <strong>Generate Report</strong> to view the results.</li>
        </ul>
    </div>

    <div id="toast"
        class="hidden fixed bottom-5 right-5 bg-red-500 text-white px-4 py-2 rounded-md shadow-md transition-opacity duration-300 z-50">
        <span id="toast-message"></span>
    </div>

    <div class="bg-white p-6 md:p-10 rounded-lg shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-center md:items-center border-b pb-4 mb-6">
            <h3 class="text-xl md:text-2xl font-semibold mb-4 md:mb-0">Customize Your Report</h3>
            <button type="button" onclick="submitForm()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded">
                Generate Report
            </button>
        </div>

        <form id="maintenanceReportForm" action="{{ route('maintenance.report.generate') }}" method="GET" class="space-y-6 md:space-y-8">
            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">
                <div>
                    <label for="start_date" class="block text-sm font-medium mb-2">Start Date: <span class="text-red-500">*</span></label>
                    <input type="date" id="start_date" name="start_date" required
                        class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium mb-2">End Date: <span class="text-red-500">*</span></label>
                    <input type="date" id="end_date" name="end_date" required
                        class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-md">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Checkbox Fields Section -->
                <div>
                    <h4 class="text-lg md:text-xl font-semibold mb-4">Select Fields to Include:</h4>
                    <label class="flex items-center space-x-3 mb-4">
                        <input type="checkbox" id="selectAll" class="w-5 h-5 text-blue-600 border-gray-300 rounded" checked>
                        <span class="text-sm font-medium">Select All</span>
                    </label>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach([
                        'id' => 'ID',
                        'description' => 'Description',
                        'type' => 'Type',
                        'cost' => 'Cost',
                        'requested_at' => 'Requested At',
                        'authorized_at' => 'Authorized At',
                        'start_date' => 'Start Date',
                        'completion_date' => 'Completion Date',
                        'is_completed' => 'Is Completed',
                        'reason' => 'Reason',
                        'status' => 'Status',
                        'authorized_by' => 'Authorized By',
                        'requestor' => 'Requestor',
                        'asset_key' => 'Asset'
                        ] as $field => $label)
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="fields[]" value="{{ $field }}" class="field-checkbox w-5 h-5 text-blue-600 border-gray-300 rounded" checked>
                            <span class="text-sm font-medium">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="border rounded-lg p-4 md:p-6 bg-gray-50">
                    <h4 class="text-lg md:text-xl font-semibold mb-4">Apply Filters</h4>

                    <div class="space-y-4">
                        <!-- Type Filter -->
                        <label for="mntc_type" class="block text-sm font-medium">Maintenance Type:</label>
                        <select id="mntc_type" name="mntc_type[]" class="select2" multiple="multiple" style="width: 100%;">
                            <option value="repair">Repair</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="upgrade">Upgrade</option>
                            <option value="inspection">Inspection</option>
                            <option value="replacement">Replacement</option>
                            <option value="calibration">Calibration</option>
                        </select>

                        <!-- Status Filter -->
                        <label for="status" class="block text-sm font-medium">Status:</label>
                        <select id="status" name="status[]" class="select2" multiple="multiple" style="width: 100%;">
                            <option value="request">Request</option>
                            <option value="approved">Approved</option>
                            <option value="denied">Denied</option>
                            <option value="cancelled">Cancelled</option>
                        </select>

                        <!-- Completion Status Filter -->
                        <label for="is_completed" class="block text-sm font-medium">Is Completed:</label>
                        <select id="is_completed" name="is_completed" class="w-full border border-gray-300 rounded-md">
                            <option value="">Select</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>

                        <!-- Cost Range Filter -->
                        <div class="space-y-2 mb-4">
                            <h4 class="text-sm font-medium mb-1">Cost Range Instructions:</h4>
                            <ul class="list-disc ml-5 text-sm text-gray-600">
                                <li>Enter the <strong>minimum</strong> and <strong>maximum</strong> cost values.</li>
                                <li>The <strong>minimum cost</strong> must be at least 1.</li>
                                <li>The <strong>maximum cost</strong> must be greater than the minimum cost.</li>
                            </ul>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="cost_min" class="block text-sm font-medium">Min Cost:</label>
                                <input type="number" id="cost_min" name="cost_min" min="1"
                                    class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-md"
                                    placeholder="Enter minimum cost">
                            </div>
                            <div>
                                <label for="cost_max" class="block text-sm font-medium">Max Cost:</label>
                                <input type="number" id="cost_max" name="cost_max" min="1"
                                    class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-md"
                                    placeholder="Enter maximum cost">
                            </div>
                        </div>

                        <!-- Authorized By Filter -->
                        <label for="authorized_by" class="block text-sm font-medium">Authorized By:</label>
                        <select id="authorized_by" name="authorized_by[]" class="select2" multiple="multiple" style="width: 100%;">
                            @foreach($authorizedByOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>

                        <!-- Requestor Filter -->
                        <label for="requestor" class="block text-sm font-medium">Requestor:</label>
                        <select id="requestor" name="requestor[]" class="select2" multiple="multiple" style="width: 100%;">
                            @foreach($requestorOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>

                        <!-- Asset Filter -->
                        <label for="asset_key" class="block text-sm font-medium">Asset:</label>
                        <select id="asset_key" name="asset_key[]" class="select2" multiple="multiple" style="width: 100%;">
                            @foreach($assetOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
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

        // Trigger toast if an error exists in the session
        @if(session('error'))
        showToast("{{ session('error') }}");
        @endif
    });

    function showToast(message) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        toastMessage.textContent = message;
        toast.classList.remove('hidden');

        // Hide toast after 3 seconds
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }

    function submitForm() {
        document.getElementById('maintenanceReportForm').submit();
    }
</script>
@endsection