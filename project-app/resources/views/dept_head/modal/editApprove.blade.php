<!-- resources/views/dept_head/modal/editApprove.blade.php -->
<div>
    <h2 class="text-xl font-semibold mb-4">Edit Maintenance (Approved)</h2>

    <form action="{{ route('maintenance.updateApproved', $maintenance->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Asset Details Section -->
        <div class="grid grid-cols-3 gap-3 mb-3">
            <div class="col-span-1 grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Asset Code</label>
                    <input type="text" value="{{ $maintenance->asset->code }}" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm" readonly />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <input type="text" value="{{ $maintenance->category->name }}" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm" readonly />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Model</label>
                    <input type="text" value="{{ $maintenance->model->name }}" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm" readonly />
                </div>
            </div>
            <div class="col-span-1 grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Asset Name</label>
                    <input type="text" value="{{ $maintenance->asset->name ?? 'N/A' }}" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm" readonly />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" value="{{ $maintenance->location->name }}" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm" readonly />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Manufacturer</label>
                    <input type="text" value="{{ $maintenance->manufacturer->name }}" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm" readonly />
                </div>
            </div>

            <div class="col-span-1 grid grid-cols-1 gap-3">
                <div class="flex flex-col items-center justify-start"> <!-- Center image at the top -->
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asset Image</label>
                    <img id="assetImage" src="https://via.placeholder.com/200" alt="Asset Image" class="w-36 h-36 object-cover"> <!-- Placeholder image -->
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm" readonly>{{ $maintenance->description }}</textarea>
        </div>

        <!-- Maintenance Details -->
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Requested At</label>
                <input type="text" value="{{ \Carbon\Carbon::parse($maintenance->requested_at)->format('F d, Y - h:i A') }}" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm" readonly />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Approved At</label>
                <input type="text" value="{{ \Carbon\Carbon::parse($maintenance->authorized_at)->format('F d, Y - h:i A') }}" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm" readonly />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Maintenance Type</label>
                <select name="type" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="repair" {{ $maintenance->type == 'repair' ? 'selected' : '' }}>Repair</option>
                    <option value="maintenance" {{ $maintenance->type == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="upgrade" {{ $maintenance->type == 'upgrade' ? 'selected' : '' }}>Upgrade</option>
                    <option value="inspection" {{ $maintenance->type == 'inspection' ? 'selected' : '' }}>Inspection</option>
                    <option value="replacement" {{ $maintenance->type == 'replacement' ? 'selected' : '' }}>Replacement</option>
                    <option value="calibration" {{ $maintenance->type == 'calibration' ? 'selected' : '' }}>Calibration</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" value="{{ \Carbon\Carbon::parse($maintenance->start_date)->format('Y-m-d') }}" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Cost</label>
                <input type="number" step="0.01" name="cost" value="{{ $maintenance->cost }}" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" />

            </div>
        </div>

        <!-- Set as Completed -->
        <div class="flex items-center mb-3">
            <input type="checkbox" name="set_as_completed" id="set_as_completed" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ $maintenance->is_completed ? 'checked' : '' }}>
            <label for="set_as_completed" class="ml-2 block text-sm font-medium text-gray-700">Set As Completed</label>
        </div>

        <!-- Completion Date -->
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">Completion Date</label>
            <input type="date" name="completion_date" value="{{ $maintenance->completion_date ? \Carbon\Carbon::parse($maintenance->completion_date)->format('Y-m-d') : '' }}" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" />
        </div>

        <!-- Buttons -->
        <div class="flex justify-end">
            <button type="submit" class="px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 mr-2">Save</button>
            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600" onclick="closeEditModal()">Cancel</button>
        </div>
    </form>
</div>

<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {
        var assetId = "{{ $maintenance->asset->id }}"; // Retrieve the asset ID dynamically
        fetchAssetDetails(assetId); // Call the function to fetch asset details, including the image

        function fetchAssetDetails(assetId) {
            $.ajax({
                url: '/assets/details/' + assetId, // Update with the correct route to fetch asset details
                method: 'GET',
                success: function (response) {
                    // Set the asset image URL from the response
                    var assetImage = response.image_url ? response.image_url : '/images/no-image.png';
                    $('#assetImage').attr('src', assetImage); // Update the image source
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching asset details:', status, error);
                }
            });
        }
    });

</script>
