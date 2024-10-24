<div id="filterModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl font-semibold mb-4">Filter Assets</h2>
        <form id="filterForm" action="{{ route('asset') }}" method="GET">
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="w-full mt-1 p-2 border rounded-md">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="under_maintenance" {{ request('status') == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    <option value="deployed" {{ request('status') == 'deployed' ? 'selected' : '' }}>Deployed</option>
                    <option value="disposed" {{ request('status') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category" id="category" class="w-full mt-1 p-2 border rounded-md">
                    <option value="">All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->name }}"
                            {{ request('category') == $category->name ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" id="closeFilterModalBtn" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Apply</button>
            </div>
        </form>
    </div>
</div>


