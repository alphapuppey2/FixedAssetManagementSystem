<!-- User Filter Modal -->
<div id="filterModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl font-semibold mb-4">Filter Users</h2>

        <form id="filterForm" method="GET" action="{{ route('userList') }}">
            <!-- User Type Dropdown -->
            <div class="mb-4 relative">
                <button type="button" id="userTypeDropdownBtn"
                    class="w-full bg-gray-100 border px-4 py-2 rounded-md flex justify-between items-center">
                    Select User Type
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="userTypeDropdown"
                    class="absolute hidden bg-white border rounded-md mt-2 w-full z-10 shadow-lg max-h-56 overflow-y-auto">
                    @foreach (['admin' => 'Admin', 'user' => 'User', 'dept_head' => 'Department Head'] as $key => $label)
                        <label class="block px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="usertype[]" value="{{ $key }}"
                                {{ in_array($key, (array) request('usertype', [])) ? 'checked' : '' }}
                                class="mr-2 form-checkbox text-indigo-600 rounded">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Status Dropdown -->
            <div class="mb-4 relative">
                <button type="button" id="statusDropdownBtn"
                    class="w-full bg-gray-100 border px-4 py-2 rounded-md flex justify-between items-center">
                    Select Status
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="statusDropdown"
                    class="absolute hidden bg-white border rounded-md mt-2 w-full z-10 shadow-lg max-h-56 overflow-y-auto">
                    @foreach (['Active', 'Inactive'] as $status)
                        <label class="block px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="status[]" value="{{ $status }}"
                                {{ in_array($status, (array) request('status', [])) ? 'checked' : '' }}
                                class="mr-2 form-checkbox text-indigo-600 rounded">
                            {{ $status }}
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Department Dropdown -->
            <div class="mb-4 relative">
                <button type="button" id="departmentDropdownBtn"
                    class="w-full bg-gray-100 border px-4 py-2 rounded-md flex justify-between items-center">
                    Select Department
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="departmentDropdown"
                    class="absolute hidden bg-white border rounded-md mt-2 w-full shadow-lg z-10 max-h-56 overflow-y-auto">
                    @foreach ($departments as $department)
                        <label class="block px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="department[]" value="{{ $department->id }}"
                                {{ in_array($department->id, (array) request('department', [])) ? 'checked' : '' }}
                                class="mr-2 form-checkbox text-indigo-600 rounded">
                            {{ $department->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-2">
                <button type="button" id="cancelFilterBtn"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Apply
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle Dropdowns
    const toggleDropdown = (btnId, dropdownId) => {
        const button = document.getElementById(btnId);
        const dropdown = document.getElementById(dropdownId);

        button.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });
    };

    // Initialize dropdown toggles
    toggleDropdown('userTypeDropdownBtn', 'userTypeDropdown');
    toggleDropdown('statusDropdownBtn', 'statusDropdown');
    toggleDropdown('departmentDropdownBtn', 'departmentDropdown');

    // Close Modal on Cancel Button
    document.getElementById('cancelFilterBtn').addEventListener('click', () => {
        document.getElementById('filterModal').classList.add('hidden');
    });

    // Close Dropdowns when clicking outside
    document.addEventListener('click', (event) => {
        const dropdowns = [
            { btn: 'userTypeDropdownBtn', menu: 'userTypeDropdown' },
            { btn: 'statusDropdownBtn', menu: 'statusDropdown' },
            { btn: 'departmentDropdownBtn', menu: 'departmentDropdown' },
        ];

        dropdowns.forEach(({ btn, menu }) => {
            const button = document.getElementById(btn);
            const dropdown = document.getElementById(menu);

            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>
