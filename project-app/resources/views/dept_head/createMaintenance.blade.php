<!-- resources/views/dept_head/createMaintenance.blade.php -->
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
                        <select name="asset_code" id="asset_code" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Asset Code</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="asset_name" class="block text-sm font-medium text-gray-700">Asset Name</label>
                        <select name="asset_name" id="asset_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Asset Name</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                        <select name="model" id="model" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
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
                        <select name="category" id="category" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <select name="location" id="location" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
                        <select name="manufacturer" id="manufacturer" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
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
                        <img src="https://via.placeholder.com/150" alt="Asset Image" class="rounded-md shadow-md">
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {

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
                var endsValue = 0;  // Default to 0 (never)

                if (selectedFrequency === 'custom') {
                    if ($('#after').is(':checked')) {
                        endsValue = $('#occurrence').val();  // Set ends to the number of occurrences
                    }
                }

                $('<input>').attr({
                    type: 'hidden',
                    name: 'ends',
                    value: endsValue
                }).appendTo('#maintenanceForm');
            });

            // Trigger when asset code changes
            $('#asset_code').change(function() {
                var assetId = $(this).val();
                if (assetId) {
                    fetchAssetDetails(assetId);
                }
            });

            // Trigger when asset name changes
            $('#asset_name').change(function() {
                var assetId = $(this).val();
                if (assetId) {
                    fetchAssetDetails(assetId);
                }
            });

            // Function to fetch asset details via AJAX
            function fetchAssetDetails(assetId) {
                $.ajax({
                    url: '/assets/details/' + assetId,
                    method: 'GET',
                    success: function(response) {
                        console.log(response); // Add this to check the response

                        // Populate asset fields
                        $('#asset_name').val(response.id); // Select the asset name based on the asset code
                        $('#asset_code').val(response.id); // Select the asset code based on the asset name

                        // Check if category, model, manufacturer, location exist in the response
                        if (response.category) {
                            $('#category').val(response.category.id); // Populate the category dropdown
                        }
                        if (response.model) {
                            $('#model').val(response.model.id); // Populate the model dropdown
                        }
                        if (response.manufacturer) {
                            $('#manufacturer').val(response.manufacturer.id); // Populate the manufacturer dropdown
                        }
                        if (response.location) {
                            $('#location').val(response.location.id); // Populate the location dropdown
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error); // Error handling
                    }
                });
            }
        });
    </script>
@endsection
