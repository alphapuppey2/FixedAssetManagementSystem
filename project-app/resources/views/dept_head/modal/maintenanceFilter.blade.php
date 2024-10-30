<!-- Filter Modal -->
<div id="filterModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 hidden">
    <div class="bg-white rounded-lg w-full max-w-lg p-8 shadow-xl space-y-6">
        <h2 class="text-xl font-bold text-gray-700">Filter Maintenance Requests</h2>
        <form action="{{ route('maintenance.filter') }}" method="GET" class="space-y-4">
            @csrf

            <!-- Requestor Filter -->
            <div>
                <label for="requestor" class="block text-sm font-medium text-gray-700">Requestor</label>
                <select name="requestor[]" id="requestor" class="select2 w-full mt-2" style="width: 100%;" multiple="multiple">
                    @if(isset($users) && count($users) > 0)
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                    @endforeach
                    @else
                    <option disabled>No users found</option>
                    @endif

                </select>
            </div>
            <!-- Maintenance Type Filter -->
            <div>
                <label for="mtcType" class="block text-sm font-medium text-gray-700">Maintenance Type</label>
                <select name="type[]" id="mtcType" class="select2 w-full mt-2" style="width: 100%;" multiple="multiple">
                    <option value="repair">Repair</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="upgrade">Upgrade</option>
                    <option value="inspection">Inspection</option>
                    <option value="replacement">Replacement</option>
                    <option value="calibration">Calibration</option>
                </select>
            </div>

            <!-- Department Head Users Filter -->
            <div>
                <label for="deptHead" class="block text-sm font-medium text-gray-700">Department Head</label>
                <select name="dept_head" id="deptHead" class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Select a Department Head</option>
                    @if (isset($deptHeads) && count($deptHeads) > 0)
                        @foreach ($deptHeads as $head)
                        <option value="{{ $head->id }}">{{ $head->firstname }} {{ $head->lastname }}</option>
                        @endforeach
                    @else
                        <option disabled>No department heads found</option>
                    @endif
                </select>
            </div>

            <!-- Date Range Filters -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Requested At (Range)</label>
                <div class="grid grid-cols-2 gap-4 mt-2">
                    <input type="date" name="start_date"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <input type="date" name="end_date"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" id="cancelFilterBtn"
                    class="px-5 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 focus:ring focus:ring-blue-300">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>
</div>
