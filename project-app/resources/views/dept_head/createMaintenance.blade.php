@extends('layouts.app')

@section('header')
    <h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Create New Maintenance</h2>
@endsection

@section('content')
    <div class="px-6 py-4">

        <form action="{{ route('maintenance.store') }}" method="POST" id="maintenanceForm">
            @csrf
            <div class="grid grid-cols-3 gap-6">
                <!-- Asset Code, Asset Name, Model -->
                <div class="col-span-1 grid grid-cols-1 gap-4">
                    <div>
                        <label for="asset_code" class="block text-sm font-medium text-gray-700">Asset Code</label>
                        <select name="asset_code" id="asset_code" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 select2">
                            <option value="">Select Asset Code</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="asset_name" class="block text-sm font-medium text-gray-700">Asset Name</label>
                        <select name="asset_name" id="asset_name" class="select2 block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Asset Name</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                        <select name="model" id="model" class="select2 block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Model</option>
                            @foreach($models as $model)
                                <option value="{{ $model->id }}">{{ $model->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Category, Location, Manufacturer -->
                <div class="col-span-1 grid grid-cols-1 gap-4">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category" class="select2 block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <select name="location" id="location" class="select2 block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
                        <select name="manufacturer" id="manufacturer" class="select2 block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Manufacturer</option>
                            @foreach($manufacturers as $manufacturer)
                                <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Image -->
                <div class="col-span-1 grid grid-cols-1 gap-4">
                    <div class="col-span-1 flex items-center justify-center">
                        <img id="assetImage" src="https://via.placeholder.com/200x200" alt="Asset Image" class="rounded-md shadow-md" style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-3 gap-6 mt-6">
                <!-- Cost -->
                <div class="col-span-2 grid grid-cols-1 gap-4">
                    <div>
                        <label for="cost" class="block text-sm font-medium text-gray-700">Cost</label>
                        <input type="number" step=".01" id="cost" name="cost" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="1">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 mt-6">
                <!-- Frequency -->
                <div class="col-span-1">
                    <label for="frequency" class="block text-sm font-medium text-gray-700">Frequency</label>
                    <select name="frequency" id="frequency" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="every_day">Every Day</option>
                        <option value="every_week">Every Week</option>
                        <option value="every_month">Every Month</option>
                        <option value="every_year">Every Year</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>

                <!-- Repeat every (hidden by default) -->
                <div class="col-span-1" id="repeat_section" style="display: none;">
                    <label for="repeat" class="block text-sm font-medium text-gray-700">Repeat every</label>
                    <div class="flex items-center space-x-2">
                        <input type="number" id="repeat" name="repeat" class="block w-1/3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="1">
                        <select name="interval" id="interval" class="block w-2/3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1">Days</option>
                            <option value="7">Weeks</option>
                            <option value="30">Months</option>
                            <option value="365">Years</option>
                        </select>
                    </div>
                </div>

                <!-- Ends (hidden by default) -->
                <div class="col-span-3" id="ends_section" style="display: none;">
                    <label for="ends_option" class="block text-sm font-medium text-gray-700">Ends</label>
                    <div class="py-3">
                        <div>
                            <input type="radio" id="never" name="ends_option" value="never" class="mr-2" checked>
                            <label for="never" class="text-gray-700">Never</label>
                        </div>
                        <div>
                            <input type="radio" id="after" name="ends_option" value="after" class="mr-2">
                            <label for="after" class="text-gray-700">After</label>
                            <input type="number" id="occurrence" name="occurrence" class="ml-2 w-16 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="1">
                            <span class="text-gray-700">occurrences</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Save and Cancel buttons -->
            <div class="flex justify-end mt-6 space-x-4">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600">Save</button>
                <a href="{{ route('maintenance_sched') }}" class="px-4 py-2 bg-red-500 text-white rounded-md shadow hover:bg-red-600">Cancel</a>
            </div>

        </form>
    </div>

        <!-- Toast Notification -->
    @if(session('status'))
        <div id="toast" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('status') }}
         </div>
    @endif

    <!-- Load jQuery only once -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include Select2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Custom Select2 Styling -->
    <style>
        /* Customize Select2 to look more like the original select elements */
        .select2-container--default .select2-selection--single {
            height: 43px; /* Adjust height */
            border: 1px solid #d1d5db; /* Match border style with input */
            border-radius: 6px; /* Rounded corners */
            padding: 6px; /* Padding to match the input field */
            /* background-color: #f9fafb; Light background color for better visibility */
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px; /* Match height to the input */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px; /* Center the text vertically */
        }
    </style>

<script>
    $(document).ready(function() {

        // Initialize Select2 on all select elements with the class "select2"
        $('.select2').select2({
            placeholder: "Select an option",  // Placeholder text
            allowClear: true                  // Allow clearing the selection
        });

        var isUpdating = false; // Flag to prevent circular events

        // Function to fetch asset details via AJAX
        function fetchAssetDetails(assetId) {
            if (isUpdating) return; // Prevent circular event triggering
            isUpdating = true; // Set flag to indicate that updates are in progress

            console.log('Fetching asset details for ID: ' + assetId);
            $.ajax({
                url: '/assets/details/' + assetId,
                method: 'GET',
                success: function(response) {
                    console.log('AJAX Success:', response);

                    // Temporarily remove change event handlers to avoid circular triggering
                    $('#asset_name').off('change');
                    $('#asset_code').off('change');

                    // Populate the asset name and code
                    if (response.name) {
                        console.log('Setting Asset Name to: ' + response.name);
                        $('#asset_name').val(response.id).trigger('change.select2'); // Set the value to the ID of the asset in the asset name select
                        $('#asset_name option[value="' + response.id + '"]').text(response.name); // Set the display text for the selected option
                    }

                    if (response.code) {
                        console.log('Setting Asset Code to: ' + response.code);
                        $('#asset_code').val(response.id).trigger('change.select2'); // Set the value to the ID of the asset in the asset code select
                        $('#asset_code option[value="' + response.id + '"]').text(response.code); // Set the display text for the selected option
                    }

                    // Populate the other related fields
                    if (response.model) {
                        console.log('Setting Model to: ' + response.model.name);
                        $('#model').val(response.model.id).trigger('change.select2'); // Populate model dropdown
                    }
                    if (response.category) {
                        console.log('Setting Category to: ' + response.category.name);
                        $('#category').val(response.category.id).trigger('change.select2'); // Populate category dropdown
                    }
                    if (response.location) {
                        console.log('Setting Location to: ' + response.location.name);
                        $('#location').val(response.location.id).trigger('change.select2'); // Populate location dropdown
                    }
                    if (response.manufacturer) {
                        console.log('Setting Manufacturer to: ' + response.manufacturer.name);
                        $('#manufacturer').val(response.manufacturer.id).trigger('change.select2'); // Populate manufacturer dropdown
                    }

                    // Update the asset image
                    var assetImage = response.image_url ? response.image_url : '/images/no-image.png';
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
            });
</script>






@endsection
