<!-- resources/views/dept_head/modal/editDenied.blade.php -->
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 z-3">
    <div class="bg-white rounded-lg w-full max-w-3xl md:max-w-2xl shadow-lg overflow-hidden">
        <div class="p-4 md:p-6 max-h-screen md:max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl md:text-2xl font-semibold mb-4 text-center md:text-left">Edit Maintenance (Denied)</h2>

            <form action="{{ route('adminmaintenance.updateDenied', $maintenance->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Asset Details Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">Asset Code</label>
                        <input type="text" value="{{ $maintenance->asset->code }}" class="w-full border px-3 py-2 rounded-md shadow-sm bg-gray-200 text-sm" readonly>

                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <input type="text" value="{{ $maintenance->category->name }}" class="w-full border px-3 py-2 rounded-md shadow-sm bg-gray-200 text-sm" readonly>

                        <label class="block text-sm font-medium text-gray-700">Model</label>
                        <input type="text" value="{{ $maintenance->model->name }}" class="w-full border px-3 py-2 rounded-md shadow-sm bg-gray-200 text-sm" readonly>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">Asset Name</label>
                        <input type="text" value="{{ $maintenance->asset->name ?? 'N/A' }}" class="w-full border px-3 py-2 rounded-md shadow-sm bg-gray-200 text-sm" readonly>

                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" value="{{ $maintenance->location->name }}" class="w-full border px-3 py-2 rounded-md shadow-sm bg-gray-200 text-sm" readonly>

                        <label class="block text-sm font-medium text-gray-700">Manufacturer</label>
                        <input type="text" value="{{ $maintenance->manufacturer->name }}" class="w-full border px-3 py-2 rounded-md shadow-sm bg-gray-200 text-sm" readonly>
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-center">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Asset Image</label>
                            <img id="assetImage" src="https://via.placeholder.com/200" alt="Asset Image" class="w-36 h-36 md:w-48 md:h-48 object-cover rounded-md shadow-md">
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="w-full border px-3 py-2 rounded-md shadow-sm bg-gray-200 text-sm" readonly>{{ $maintenance->description }}</textarea>
                </div>

                <!-- Maintenance Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Requested At</label>
                        <input type="text" value="{{ \Carbon\Carbon::parse($maintenance->requested_at)->format('F d, Y - h:i A') }}" class="w-full border px-3 py-2 rounded-md shadow-sm bg-gray-200 text-sm" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Denied At</label>
                        <input type="text" value="{{ \Carbon\Carbon::parse($maintenance->authorized_at)->format('F d, Y - h:i A') }}" class="w-full border px-3 py-2 rounded-md shadow-sm bg-gray-200 text-sm" readonly>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Reason</label>
                    <textarea name="reason" rows="3" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">{{ $maintenance->reason }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status of Request</label>
                    <select name="status" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="approved" {{ $maintenance->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="denied" {{ $maintenance->status == 'denied' ? 'selected' : '' }}>Denied</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Save</button>
                    <button type="button" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600" onclick="closeEditModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {
        var assetId = "{{ $maintenance->asset->code }}"; // Retrieve the asset ID dynamically
        fetchAssetDetails(assetId); // Fetch asset details, including the image

        function fetchAssetDetails(assetId) {
            $.ajax({
                url: `{{ url('admin/asset-details') }}/${assetId}`, // Correct route to fetch asset details
                method: 'GET',
                success: function (response) {
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
