@extends('layouts.app')

@section('header')
    {{-- <h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Create New Maintenance</h2> --}}
    <h2 class="my-3 font-semibold text-xl text-black-800 leading-tight text-center md:text-left">Create New Maintenance</h2>
@endsection

@section('content')
    {{-- <div class="px-6 py-4"> --}}
    <div class="container mx-auto px-4 py-6 max-w-full">

        @if (session('status'))
            <div id="toast"
                class="fixed bottom-5 right-5 px-4 py-2 rounded shadow-lg
                    {{ session('status_type') === 'error' ? 'bg-red-500' : 'bg-green-500' }}
                    text-white">
                {{ session('status') }}
            </div>
        @endif

        <!-- Instructions Section -->
        <div class="mb-6 p-6 bg-blue-100 rounded-md shadow-md">
            <h3 class="text-lg font-semibold mb-2">Instructions</h3>
            <ul class="list-disc ml-5 text-sm text-gray-700">
                <li>Select the <strong>Asset Code</strong> or <strong>Asset Name</strong> from the dropdowns. The details
                    such as <strong>Model</strong>, <strong>Category</strong>, <strong>Location</strong>, and
                    <strong>Manufacturer</strong> will automatically populate based on your selection.</li>
                <li>The fields for <strong>Model</strong>, <strong>Category</strong>, <strong>Location</strong>, and
                    <strong>Manufacturer</strong> are read-only and cannot be changed manually.</li>
                <li>Specify the <strong>Cost</strong> of the maintenance and choose a <strong>Frequency</strong> for how
                    often it should occur.</li>
                <li>If you select <strong>Custom Frequency</strong>, additional options will appear for setting up a custom
                    recurrence pattern.</li>
                <li>Click <strong>Save</strong> to submit the form or <strong>Cancel</strong> to go back to the maintenance
                    schedule without saving.</li>
            </ul>
        </div>

        <form action="{{ route('adminMaintenance.store') }}" method="POST" id="maintenanceForm">
            @csrf

            {{-- <div class="grid grid-cols-3 gap-6"> --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">

                <!-- Legit nga Image -->
                <div class="col-span-1 grid grid-cols-1 gap-4">
                    <div class="col-span-1 flex items-center justify-center">
                        <img id="assetImage"src="{{ asset('/images/no-image.png') }}" alt="Asset Image"
                            class="rounded-md shadow-md" style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                </div>

                <!-- Asset Code, Asset Name, Model -->
                {{-- <div class="col-span-1 grid grid-cols-1 gap-4"> --}}
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="asset_code" class="block text-sm font-medium text-gray-700">Asset Code</label>
                        <select name="asset_code" id="asset_code"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 select2">
                            <option value="">Select Asset Code</option>
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="asset_name" class="block text-sm font-medium text-gray-700">Asset Name</label>
                        <select name="asset_name" id="asset_name"
                            class="select2 block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Asset Name</option>
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                        <input type="text" name="model" id="model"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-100"
                            readonly>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <input type="text" name="category" id="category"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-100"
                            readonly>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" id="location"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-100"
                            readonly>
                    </div>

                    <div>
                        <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
                        <input type="text" name="manufacturer" id="manufacturer"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-100"
                            readonly>
                    </div>

                    <!-- Image -->
                    {{-- <div class="col-span-1 grid grid-cols-1 gap-4">
                    <div class="col-span-1 flex items-center justify-center">
                        <img
                            id="assetImage"
                            src="{{ asset('/images/no-image.png') }}"
                            alt="Asset Image"
                            class="rounded-md shadow-md"
                            style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                </div> --}}
                </div>
            </div>

            {{-- <div class="grid grid-cols-3 gap-6 mt-6"> --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <!-- Cost -->
                {{-- <div class="col-span-2 grid grid-cols-1 gap-4"> --}}
                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700">Cost</label>
                    <input type="number" step=".01" id="cost" name="cost"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        value="1">
                </div>
                {{-- </div> --}}
                {{-- </div> --}}

                {{-- <div class="grid grid-cols-3 gap-6 mt-6"> --}}
                <!-- Frequency -->
                <div>
                    <label for="frequency" class="block text-sm font-medium text-gray-700">Frequency</label>
                    <select name="frequency" id="frequency"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="every_day">Every Day</option>
                        <option value="every_week">Every Week</option>
                        <option value="every_month">Every Month</option>
                        <option value="every_year">Every Year</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>

                <!-- Repeat every (hidden by default) -->
                {{-- <div class="col-span-1" id="repeat_section" style="display: none;"> --}}
                <div id="repeat_section" class="hidden">
                    <label for="repeat" class="block text-sm font-medium text-gray-700">Repeat every</label>
                    <div class="flex space-x-2">
                        <input type="number" id="repeat" name="repeat"
                            class="block w-1/3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            value="1">
                        <select name="interval" id="interval"
                            class="block w-2/3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1">Days</option>
                            <option value="7">Weeks</option>
                            <option value="30">Months</option>
                            <option value="365">Years</option>
                        </select>
                    </div>
                </div>

                <!-- Ends (hidden by default) -->
                <div id="ends_section" class="hidden mt-4">
                    <label for="ends_option" class="block text-sm font-medium text-gray-700">Ends</label>
                    {{-- <label class="block text-sm font-medium text-gray-700">Ends</label> --}}
                    <div class="space-y-2">
                        <div>
                            <input type="radio" id="never" name="ends_option" value="never" checked>
                            <label for="never" class="text-gray-700">Never</label>
                        </div>
                        {{-- <div> --}}
                        <div class="flex items-center">
                            <input type="radio" id="after" name="ends_option" value="after">
                            <label for="after" class="ml-2 text-gray-700">After</label>
                            <input type="number" id="occurrence" name="occurrence"
                                class="ml-2 w-16 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                value="1">
                            <span class="ml-2 text-gray-700">occurrences</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Save and Cancel buttons -->
            <div class="flex justify-end mt-6 space-x-4">
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600">Save</button>
                <a href="{{ route('adminMaintenance_sched') }}"
                    class="px-4 py-2 bg-red-500 text-white rounded-md shadow hover:bg-red-600">Cancel</a>
            </div>

        </form>
    </div>

    {{-- <!-- Toast Notification -->
@if (session('status'))
<div id="toast" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
    {{ session('status') }}
</div>
@endif --}}

    <!-- Load jQuery only once -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include Select2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Custom Select2 Styling -->
    <style>
        /* Customize Select2 to look more like the original select elements */
        .select2-container--default .select2-selection--single {
            height: 43px;
            /* Adjust height */
            border: 1px solid #d1d5db;
            /* Match border style with input */
            border-radius: 6px;
            /* Rounded corners */
            padding: 6px;
            /* Padding to match the input field */
            /* background-color: #f9fafb; Light background color for better visibility */
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
            /* Match height to the input */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            /* Center the text vertically */
        }
    </style>

    <script>
        // Toast Notification fade-out
        setTimeout(function() {
            var toast = document.getElementById('toast');
            if (toast) {
                toast.style.transition = 'opacity 1s ease';
                toast.style.opacity = '0';
                setTimeout(function() {
                    toast.remove();
                }, 1000); // Remove it after fading out
            }
        }, 3000); // 3 seconds delay
    </script>

    <script>
        $(document).ready(function() {

            // Initialize Select2 on all select elements with the class "select2"
            $('.select2').select2({
                placeholder: "Select an option", // Placeholder text
                allowClear: true // Allow clearing the selection
            });

            var isUpdating = false; // Flag to prevent circular events

            // Function to fetch asset details via AJAX
            function fetchAssetDetails(assetId) {
                if (isUpdating) return; // Prevent circular event triggering
                isUpdating = true; // Set flag to indicate that updates are in progress

                console.log('Fetching asset details for ID: ' + assetId);
                $.ajax({
                    url: '/admin/mntc-asset-details/' + assetId,
                    method: 'GET',
                    success: function(response) {
                        console.log('AJAX Success:', response);

                        // Temporarily remove change event handlers to avoid circular triggering
                        $('#asset_name').off('change');
                        $('#asset_code').off('change');

                        // Populate the asset name and code
                        if (response.name) {
                            console.log('Setting Asset Name to: ' + response.name);
                            var assetNameOption = new Option(response.name, response.id, true,
                            true); // Create a new option
                            $('#asset_name').append(assetNameOption).trigger(
                            'change.select2'); // Add and trigger Select2 change
                        }

                        if (response.code) {
                            console.log('Setting Asset Code to: ' + response.code);
                            var assetCodeOption = new Option(response.code, response.id, true,
                            true); // Create a new option
                            $('#asset_code').append(assetCodeOption).trigger(
                            'change.select2'); // Add and trigger Select2 change
                        }

                        $('#model').val(response.model ? response.model.name : '').trigger('input');
                        $('#category').val(response.category ? response.category.name : '').trigger(
                            'input');
                        $('#location').val(response.location ? response.location.name : '').trigger(
                            'input');
                        $('#manufacturer').val(response.manufacturer ? response.manufacturer.name : '')
                            .trigger('input');

                        // Update the asset image
                        var assetImage = response.image_url ? response.image_url :
                            '/images/no-image.png';
                        $('#assetImage').attr('src', assetImage);

                        // Reattach event handlers after updates
                        $('#asset_code').on('change', assetCodeChanged);
                        $('#asset_name').on('change', assetNameChanged);

                        isUpdating = false; // Reset flag
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error); // Handle errors
                        isUpdating = false; // Reset flag on error
                    }
                });
            }

            // Event handler for asset code change
            function assetCodeChanged() {
                if (!isUpdating) {
                    var assetId = $(this).val();
                    if (assetId) {
                        fetchAssetDetails(assetId);
                    }
                }
            }

            // Event handler for asset name change
            function assetNameChanged() {
                if (!isUpdating) {
                    var assetId = $(this).val();
                    if (assetId) {
                        fetchAssetDetails(assetId);
                    }
                }
            }

            // Attach event handlers to the asset code and asset name fields
            $('#asset_code').on('change', assetCodeChanged);
            $('#asset_name').on('change', assetNameChanged);


            // Show or hide custom frequency options based on the selection
            $('#frequency').change(function() {
                var selectedFrequency = $(this).val();

                if (selectedFrequency === 'custom') {
                    $('#repeat_section').show();
                    $('#ends_section').show();
                } else {
                    $('#repeat_section').hide();
                    $('#ends_section').hide();
                }
            });

            // When the form is submitted, set the ends field value based on the custom selection
            $('#maintenanceForm').on('submit', function(event) {
                var selectedFrequency = $('#frequency').val();
                var endsValue = 0; // Default to 0 (never)

                if (selectedFrequency === 'custom') {
                    if ($('#after').is(':checked')) {
                        endsValue = $('#occurrence').val(); // Set ends to the number of occurrences
                    }
                }

                $('<input>').attr({
                    type: 'hidden',
                    name: 'ends',
                    value: endsValue
                }).appendTo('#maintenanceForm');
            });

             // Error message handling for the cost input
        function createErrorElement(input) {
                let errorElement = input.nextElementSibling;
                if (!errorElement || !errorElement.classList.contains('error-message')) {
                    errorElement = document.createElement('div');
                    errorElement.className = 'error-message text-red-500 text-sm mt-1';
                    errorElement.style.display = 'none'; // Initially hidden
                    input.parentNode.appendChild(errorElement);
                }
                return errorElement;
            }

            const costInput = document.getElementById('cost');
            const costError = createErrorElement(costInput);

            // Display and clear error functions
            function displayError(input, errorElement, message) {
                input.classList.add('border-red-500');
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }

            function clearError(input, errorElement) {
                input.classList.remove('border-red-500');
                errorElement.style.display = 'none';
            }

            // Validate cost input to block negative values
            function validateCost() {
                const value = parseFloat(costInput.value);

                if (value < 0) {
                    costInput.value = '';
                    displayError(costInput, costError, 'Negative values are not allowed.');
                } else {
                    clearError(costInput, costError);
                }
            }

            // Event listeners to prevent negative input
            costInput.addEventListener('keypress', function(event) {
                if (event.key === '-' || event.key === '+') {
                    event.preventDefault();
                    displayError(costInput, costError, 'Negative values are not allowed.');
                }
            });

            costInput.addEventListener('input', validateCost);

            costInput.addEventListener('paste', function(event) {
                const clipboardData = event.clipboardData || window.clipboardData;
                const pastedData = clipboardData.getData('text');

                if (pastedData.includes('-') || parseFloat(pastedData) < 0) {
                    event.preventDefault();
                    displayError(costInput, costError, 'Negative values are not allowed.');
                }
            });

        });
    </script>
@endsection
