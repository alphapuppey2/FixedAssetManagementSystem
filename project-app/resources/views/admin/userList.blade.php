@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ "User List" }}
</h2>
@endsection

@section('content')
<div class="relative w-full max-w-md">
    <!-- Search Input with Filter Icon Inside -->
    <form method="GET" action="{{ route('userList') }}" class="relative flex items-center">
        <!-- Filter Button -->
        <button
            type="button"
            id="openFilterModalBtn"
            class="absolute inset-y-0 left-0 flex items-center pl-3 focus:outline-none">
            <x-icons.filter-icon class="w-5 h-5 text-gray-600" />
        </button>

        <!-- Search Input Field -->
        <input
            type="text"
            name="query"
            id="searchFilt"
            placeholder="Search by name or email"
            value="{{ request('query') }}"
            class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1 sm:text-sm"
        />

        <!-- Hidden Fields to Retain Filters -->
        <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'id') }}">
        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">
        @foreach (request('usertype', []) as $userType)
            <input type="hidden" name="usertype[]" value="{{ $userType }}">
        @endforeach
        @foreach (request('department', []) as $department)
            <input type="hidden" name="department[]" value="{{ $department }}">
        @endforeach
        @foreach (request('status', []) as $status)
            <input type="hidden" name="status[]" value="{{ $status }}">
        @endforeach
    </form>
</div>

<!-- Rows per page dropdown and pagination links -->
<div class="flex justify-between items-center mb-4">
    <form method="GET" action="{{ route('userList') }}" class="flex items-center space-x-2">
        <input type="hidden" name="query" value="{{ request('query') }}">
        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'id') }}">
        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">

        @foreach (request('usertype', []) as $userType)
            <input type="hidden" name="usertype[]" value="{{ $userType }}">
        @endforeach
        @foreach (request('department', []) as $department)
            <input type="hidden" name="department[]" value="{{ $department }}">
        @endforeach
        @foreach (request('status', []) as $status)
            <input type="hidden" name="status[]" value="{{ $status }}">
        @endforeach

        <label for="perPage">Rows per page: </label>
        <select name="perPage" id="perPage" class="border border-gray-300 rounded px-2 py-1 w-16" onchange="this.form.submit()">
            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
        </select>
    </form>

    @if($userList->hasPages())
    <div class="flex items-center space-x-4">
        <span class="text-gray-600">
            Showing {{ $userList->firstItem() }} to {{ $userList->lastItem() }} of {{ $userList->total() }} items
        </span>
        <div>
            {{ $userList->appends(request()->all())->links('vendor.pagination.tailwind') }}
        </div>
    </div>
    @endif
</div>

<!-- Table Layout -->
<div class="contents relative flex mt-6">
    <div class="text-center max-w-100 flex justify-center sm:flex-col md:flex-row w-full">
        <div class="hidden md:block w-full">
            <x-table class="table table-striped">
                <x-slot name='header'>
                    <th>
                        <a href="{{ route('userList', array_merge(request()->all(), ['sort_by' => 'id', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            ID <x-icons.sort-icon :direction="$sortBy === 'id' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', array_merge(request()->all(), ['sort_by' => 'employee_id', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Employee ID <x-icons.sort-icon :direction="$sortBy === 'employee_id' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', array_merge(request()->all(), ['sort_by' => 'firstname', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            First Name <x-icons.sort-icon :direction="$sortBy === 'firstname' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', array_merge(request()->all(), ['sort_by' => 'lastname', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Last Name <x-icons.sort-icon :direction="$sortBy === 'lastname' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', array_merge(request()->all(), ['sort_by' => 'email', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Email <x-icons.sort-icon :direction="$sortBy === 'email' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', array_merge(request()->all(), ['sort_by' => 'department_name', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Department <x-icons.sort-icon :direction="$sortBy === 'department_name' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', array_merge(request()->all(), ['sort_by' => 'usertype', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            User Type <x-icons.sort-icon :direction="$sortBy === 'usertype' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', array_merge(request()->all(), ['sort_by' => 'is_deleted', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc'])) }}">
                            Status <x-icons.sort-icon :direction="$sortBy === 'is_deleted' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>Action</th>
                </x-slot>

                <x-slot name='slot'>
                    @forelse($userList as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->employee_id }}</td>
                        <td>{{ $item->firstname }}</td>
                        <td>{{ $item->lastname }}</td>
                        <td>{{ $item->email }}</td>
                        <td><x-department :deptId="$item->dept_id" /></td>
                        <td>{{ $item->usertype }}</td>
                        <td>@include('components.user-status', ['is_deleted' => $item->is_deleted])</td>
                        <td>@include('components.user-list-actions', ['item' => $item])</td>
                    </tr>
                    @empty
                    <tr class="text-center text-gray-800">
                        <td colspan='9' class="text-gray-500">No List</td>
                    </tr>
                    @endforelse
                </x-slot>
            </x-table>
        </div>
    </div>
</div>

@include('admin.modal.editUser')
@include('admin.modal.userFilterModal')

<!-- SCRIPT TO OPEN AND CLOSE MODAL -->
<script>
    document.querySelectorAll('.editUserBtn').forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const btn = event.currentTarget;
            document.getElementById('id').value = btn.getAttribute('data-id');
            document.getElementById('employee_id').value = btn.getAttribute('data-employee_id');
            document.getElementById('firstname').value = btn.getAttribute('data-firstname');
            document.getElementById('middlename').value = btn.getAttribute('data-middlename');
            document.getElementById('lastname').value = btn.getAttribute('data-lastname');
            document.getElementById('email').value = btn.getAttribute('data-email');
            document.getElementById('contact').value = btn.getAttribute('data-contact');
            document.getElementById('address').value = btn.getAttribute('data-address');
            document.getElementById('gender').value = btn.getAttribute('data-gender');
            document.getElementById('department').value = btn.getAttribute('data-dept-id');
            document.getElementById('birthdate').value = btn.getAttribute('data-birthdate');
            document.getElementById('usertype').value = btn.getAttribute('data-user-type');

            // Determine status based on is_deleted value
            const isDeleted = btn.getAttribute('data-is_deleted');
            const status = isDeleted == '0' ? 'Active' : 'Inactive';
            document.getElementById('status').value = status;

            showReactivateButton(isDeleted);

            // Set created_at and updated_at fields
            const createdAt = btn.getAttribute('data-account-created');
            const updatedAt = btn.getAttribute('data-account-updated');
            document.getElementById('account_created').value = formatDate(createdAt);
            document.getElementById('account_updated').value = formatDate(updatedAt);

            const photoPath = btn.getAttribute('data-photo');
            document.getElementById('currentProfilePhoto').src = photoPath;

            document.getElementById('editUserModal').classList.remove('hidden');
        });
    });

    // Helper function to format the date
    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }

    document.getElementById('cancelEdit').addEventListener('click', () => {
        document.getElementById('editUserModal').classList.add('hidden');
    });

    // Preview the selected profile photo
    document.getElementById('profile_photo').addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('currentProfilePhoto').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('openFilterModalBtn').addEventListener('click', () => {
        document.getElementById('filterModal').classList.remove('hidden');
    });

    document.getElementById('cancelFilterBtn').addEventListener('click', () => {
        document.getElementById('filterModal').classList.add('hidden');
    });

    window.addEventListener('click', (event) => {
        const modal = document.getElementById('filterModal');
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });

</script>

@endsection
