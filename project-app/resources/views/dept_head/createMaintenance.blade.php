@extends('layouts.app')

@section('header')
<h2 class="my-3 font-semibold text-xl text-black-800 leading-tight text-center md:text-left">Create New Maintenance</h2>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6 max-w-full">

    @if(session('status'))
        <div id="toast" class="fixed bottom-5 right-5 px-4 py-2 rounded shadow-lg
                    {{ session('status_type') === 'error' ? 'bg-red-500' : 'bg-green-500' }}
                    text-white">
            {{ session('status') }}
        </div>
    @endif

    <!-- Instructions Section -->
    <div class="mb-6 p-6 bg-blue-100 rounded-md shadow-md">
        <h3 class="text-lg font-semibold mb-2">Instructions</h3>
        <ul class="list-disc ml-5 text-sm text-gray-700">
            <li>Select the <strong>Asset Code</strong> or <strong>Asset Name</strong> from the dropdowns. The details will populate automatically.</li>
            <li>The fields for <strong>Model</strong>, <strong>Category</strong>, <strong>Location</strong>, and <strong>Manufacturer</strong> are read-only.</li>
            <li>Specify the <strong>Cost</strong> and choose a <strong>Frequency</strong> for maintenance.</li>
            <li>If you select <strong>Custom Frequency</strong>, additional recurrence options will appear.</li>
            <li>Click <strong>Save</strong> or <strong>Cancel</strong> to go back to the maintenance schedule.</li>
        </ul>
    </div>

    <form action="{{ route('maintenance.store') }}" method="POST" id="maintenanceForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <!-- Legit nga Image -->
            <div class="col-span-1 grid grid-cols-1 gap-4">
                <div class="col-span-1 flex items-center justify-center">
                    <img id="assetImage"src="{{ asset('/images/no-image.png') }}"alt="Asset Image"class="rounded-md shadow-md"style="width: 200px; height: 200px; object-fit: cover;">
                </div>
            </div>


            <!-- Asset Details -->
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="asset_code" class="block text-sm font-medium text-gray-700">Asset Code</label>
                    <select name="asset_code" id="asset_code"
                        class="select2 block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Asset Code</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}">{{ $asset->code }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="asset_name" class="block text-sm font-medium text-gray-700">Asset Name</label>
                    <select name="asset_name" id="asset_name"
                        class="select2 block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Asset Name</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}">{{ $asset->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                    <input type="text" name="model" id="model"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <input type="text" name="category" id="category"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" name="location" id="location"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>
                </div>

                <div>
                    <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
                    <input type="text" name="manufacturer" id="manufacturer" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-100" readonly>
                </div>
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

        {{-- <div class="grid grid-cols-3 gap-6 mt-6">
            <!-- Cost -->
            <div class="col-span-2 grid grid-cols-1 gap-4">
                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700">Cost</label>
                    <input type="number" step=".01" id="cost" name="cost" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="1">
                </div>
            </div>
        </div> --}}

        <!-- Additional Details Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div>
                <label for="cost" class="block text-sm font-medium text-gray-700">Cost</label>
                <input type="number" step=".01" name="cost" id="cost"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

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

            <div id="repeat_section" class="hidden">
                <label for="repeat" class="block text-sm font-medium text-gray-700">Repeat every</label>
                <div class="flex space-x-2">
                    <input type="number" name="repeat" id="repeat"
                        class="w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <select name="interval" id="interval"
                        class="w-2/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="1">Days</option>
                        <option value="7">Weeks</option>
                        <option value="30">Months</option>
                        <option value="365">Years</option>
                    </select>
                </div>
            </div>

            <div id="ends_section" class="hidden mt-4">
                <label class="block text-sm font-medium text-gray-700">Ends</label>
                <div class="space-y-2">
                    <div>
                        <input type="radio" id="never" name="ends_option" value="never" checked>
                        <label for="never">Never</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="after" name="ends_option" value="after">
                        <label for="after" class="ml-2">After</label>
                        <input type="number" name="occurrence" id="occurrence"
                            class="ml-2 w-16 border-gray-300 rounded-md shadow-sm">
                        <span class="ml-2">occurrences</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save and Cancel Buttons -->
        <div class="flex justify-end mt-8 space-x-4">
            <button type="submit"
                class="px-6 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600">Save</button>
            <a href="{{ route('maintenance_sched') }}"
                class="px-6 py-2 bg-red-500 text-white rounded-md shadow hover:bg-red-600">Cancel</a>
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
                        var assetNameOption = new Option(response.name, response.id, true, true); // Create a new option
                        $('#asset_name').append(assetNameOption).trigger('change.select2'); // Add and trigger Select2 change
                    }

                    if (response.code) {
                        console.log('Setting Asset Code to: ' + response.code);
                        var assetCodeOption = new Option(response.code, response.id, true, true); // Create a new option
                        $('#asset_code').append(assetCodeOption).trigger('change.select2'); // Add and trigger Select2 change
                    }

                    //     $('#asset_name').val(response.name).trigger('change.select2');
                    // $('#asset_code').val(response.code).trigger('change.select2');
                    $('#model').val(response.model ? response.model.name : '').trigger('input');
                    $('#category').val(response.category ? response.category.name : '').trigger('input');
                    $('#location').val(response.location ? response.location.name : '').trigger('input');
                    $('#manufacturer').val(response.manufacturer ? response.manufacturer.name : '').trigger('input');

                    // Populate the other related fields
                    // if (response.model) {
                    //     console.log('Setting Model to: ' + response.model.name);
                    //     $('#model').val(response.model.id).trigger('change.select2'); // Populate model dropdown
                    // }
                    // if (response.category) {
                    //     console.log('Setting Category to: ' + response.category.name);
                    //     $('#category').val(response.category.id).trigger('change.select2'); // Populate category dropdown
                    // }
                    // if (response.location) {
                    //     console.log('Setting Location to: ' + response.location.name);
                    //     $('#location').val(response.location.id).trigger('change.select2'); // Populate location dropdown
                    // }
                    // if (response.manufacturer) {
                    //     console.log('Setting Manufacturer to: ' + response.manufacturer.name);
                    //     $('#manufacturer').val(response.manufacturer.id).trigger('change.select2'); // Populate manufacturer dropdown
                    // }

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
    });
</script>

@endsection
