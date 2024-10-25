<!-- resources/views/dept_head/modal/editApprove.blade.php -->
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="bg-white rounded-md w-full max-w-3xl md:max-w-2xl shadow-md overflow-hidden">
        <div class="p-4 md:p-6 max-h-screen md:max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl md:text-2xl font-semibold mb-4 text-center md:text-left">Edit Maintenance (Approved)</h2>

            <form action="{{ route('adminmaintenance.updateApproved', $maintenance->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Asset Details Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">Asset Code</label>
                        <input type="text" value="{{ $maintenance->asset->code }}"
                            class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm"
                            readonly>

                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <input type="text" value="{{ $maintenance->category->name }}"
                            class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm"
                            readonly>

                        <label class="block text-sm font-medium text-gray-700">Model</label>
                        <input type="text" value="{{ $maintenance->model->name }}"
                            class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm"
                            readonly>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">Asset Name</label>
                        <input type="text" value="{{ $maintenance->asset->name ?? 'N/A' }}"
                            class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm"
                            readonly>

                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" value="{{ $maintenance->location->name }}"
                            class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm"
                            readonly>

                        <label class="block text-sm font-medium text-gray-700">Manufacturer</label>
                        <input type="text" value="{{ $maintenance->manufacturer->name }}"
                            class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm"
                            readonly>
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-center">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Asset Image</label>
                            <img id="assetImage" src="https://via.placeholder.com/200" alt="Asset Image"
                                class="w-36 h-36 md:w-48 md:h-48 object-cover rounded-md shadow-md">
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm"
                        readonly>{{ $maintenance->description }}</textarea>
                </div>

                <!-- Maintenance Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Requested At</label>
                        <input type="text"
                            value="{{ \Carbon\Carbon::parse($maintenance->requested_at)->format('F d, Y - h:i A') }}"
                            class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm"
                            readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Approved At</label>
                        <input type="text"
                            value="{{ \Carbon\Carbon::parse($maintenance->authorized_at)->format('F d, Y - h:i A') }}"
                            class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-200 text-sm"
                            readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Maintenance Type</label>
                        <select name="type"
                            class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="repair" {{ $maintenance->type == 'repair' ? 'selected' : '' }}>Repair
                            </option>
                            <option value="maintenance" {{ $maintenance->type == 'maintenance' ? 'selected' : '' }}>
                                Maintenance</option>
                            <option value="upgrade" {{ $maintenance->type == 'upgrade' ? 'selected' : '' }}>Upgrade
                            </option>
                            <option value="inspection" {{ $maintenance->type == 'inspection' ? 'selected' : '' }}>
                                Inspection</option>
                            <option value="replacement" {{ $maintenance->type == 'replacement' ? 'selected' : '' }}>
                                Replacement</option>
                            <option value="calibration" {{ $maintenance->type == 'calibration' ? 'selected' : '' }}>
                                Calibration</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date"
                            value="{{ \Carbon\Carbon::parse($maintenance->start_date)->format('Y-m-d') }}"
                            class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cost</label>
                        <input type="number" step="0.01" name="cost" value="{{ $maintenance->cost }}"
                            class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>

                <!-- Set as Completed -->
                {{-- <div class="flex items-center mb-4">
                    <input type="checkbox" name="set_as_completed" id="set_as_completed"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        {{ $maintenance->is_completed ? 'checked' : '' }}>
                    <label for="set_as_completed" class="ml-2 text-sm font-medium text-gray-700">Set As
                        Completed</label>
                </div> --}}

                <!-- Set as Completed and Set as Cancelled -->
                <div class="flex items-center mb-4 space-x-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="set_as_completed" id="set_as_completed" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ $maintenance->is_completed ? 'checked' : '' }}>
                        <label for="set_as_completed" class="ml-2 text-sm font-medium text-gray-700">Set As Completed</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="set_as_cancelled" id="set_as_cancelled" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded" {{ $maintenance->is_cancelled ? 'checked' : '' }}>
                        <label for="set_as_cancelled" class="ml-2 text-sm font-medium text-gray-700">Set As Cancelled</label>
                    </div>
                </div>

                <!-- Completion Date -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Completion Date</label>
                    <input type="date" name="completion_date"
                        value="{{ $maintenance->completion_date ? \Carbon\Carbon::parse($maintenance->completion_date)->format('Y-m-d') : '' }}"
                        class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Save</button>
                    <button type="button" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600"
                        onclick="closeEditModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        var assetId = "{{ $maintenance->asset->code }}";
        fetchAssetDetails(assetId);
        function fetchAssetDetails(assetId) {
            console.log("asset::" + assetId);
            const assetDetailsUrl = "{{ url('/admin/asset-details') }}";
            $.ajax({
                url: `${assetDetailsUrl}/${assetId}`,
                method: 'GET',
                success: function(response) {
                    var assetImage = response.image_url ? response.image_url :
                        '/images/no-image.png';
                    $('#assetImage').attr('src', assetImage);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error Details:');
                    console.log('Status:', status); // Logs status (e.g., 404)
                    console.log('Error:', error); // Logs error message
                    console.error('Response Text:', xhr.responseText); // Logs server response
                    // console.log('Requested URL:', xhr); // Logs the actual URL requested
                }
            });
        }
    });
</script>
