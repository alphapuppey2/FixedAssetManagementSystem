<!-- resources/views/dept_head/modal/editDenied.blade.php -->
<div>
    <h2 class="text-xl font-semibold mb-4">Edit Maintenance (Denied)</h2>

    <form action="{{ route('maintenance.updateDenied', $maintenance->id) }}" method="POST">
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
                <label class="block text-sm font-medium text-gray-700">Denied At</label>
                <input type="text" value="{{ \Carbon\Carbon::parse($maintenance->authorized_at)->format('F d, Y - h:i A') }}" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm" readonly />
            </div>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">Reason</label>
            <textarea name="description" rows="3" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm" readonly>{{ $maintenance->reason }}</textarea>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">Status of Request</label>
            <select name="status" class="block w-full px-2 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                <option value="approved" {{ $maintenance->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="denied" {{ $maintenance->status == 'denied' ? 'selected' : '' }}>Denied</option>
            </select>
        </div>

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
