{{--
    THIS WILL DISPLAY THE LIST OF USERS IN A TABLE
--}}

@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ "User List" }}
</h2>
@endsection

@section('content')
<div>
    <form method="GET" action="{{ route('userList') }}" class="flex flex-col space-y-4">
        <!-- Search Input and Button -->
        <div class="relative search-container">
            <x-search-input
                placeholder="Search by name or email"
                class="w-80" />
        </div>
        <!-- Rows per page dropdown -->
        <div class="flex justify-between items-center mb-4">
            <!-- Rows per page dropdown (Left) -->
            <div class="flex items-center space-x-2">
                <label for="perPage">Rows per page: </label>
                <select name="perPage" id="perPage" class="border border-gray-300 rounded px-2 py-1 w-16" onchange="this.form.submit()">
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            <!-- Pagination Links and Showing Results (Right) -->
            @if($userList->hasPages())
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">Showing {{ $userList->firstItem() }} to {{ $userList->lastItem() }} of {{ $userList->total() }} items</span>
                <div>
                    {{ $userList->links('vendor.pagination.tailwind') }}
                </div>
            </div>
            @endif
        </div>
    </form>
</div>

<div class="contents relative flex mt-6">
    {{-- <div class="text-center max-w-100 flex justify-center sm:flex-col md:flex-row "> --}}
    <div class="text-center max-w-100 flex justify-center sm:flex-col md:flex-row w-full">
        <div class="hidden md:block w-full">
            <x-table class="table table-striped">
                <x-slot name='header'>
                    <th>
                        <a href="{{ route('userList', [
                            'sort_by' => 'id',
                            'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc',
                            'perPage' => $perPage,
                            'page' => $userList->currentPage(),
                            'query' => $query
                        ]) }}">
                            ID <x-icons.sort-icon :direction="$sortBy === 'id' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', [
                            'sort_by' => 'employee_id',
                            'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc',
                            'perPage' => $perPage,
                            'page' => $userList->currentPage(),
                            'query' => $query
                        ]) }}">
                            Employee ID <x-icons.sort-icon :direction="$sortBy === 'employee_id' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', [
                            'sort_by' => 'firstname',
                            'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc',
                            'perPage' => $perPage,
                            'page' => $userList->currentPage(),
                            'query' => $query
                        ]) }}">
                            First Name <x-icons.sort-icon :direction="$sortBy === 'firstname' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', [
                            'sort_by' => 'middlename',
                            'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc',
                            'perPage' => $perPage,
                            'page' => $userList->currentPage(),
                            'query' => $query
                        ]) }}">
                            Middle Name <x-icons.sort-icon :direction="$sortBy === 'middlename' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', [
                            'sort_by' => 'lastname',
                            'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc',
                            'perPage' => $perPage,
                            'page' => $userList->currentPage(),
                            'query' => $query
                        ]) }}">
                            Last Name <x-icons.sort-icon :direction="$sortBy === 'lastname' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', [
                            'sort_by' => 'email',
                            'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc',
                            'perPage' => $perPage,
                            'page' => $userList->currentPage(),
                            'query' => $query
                        ]) }}">
                            Email <x-icons.sort-icon :direction="$sortBy === 'email' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', [
                            'sort_by' => 'department_name',
                            'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc',
                            'perPage' => $perPage,
                            'page' => $userList->currentPage(),
                            'query' => $query
                        ]) }}">
                            Department <x-icons.sort-icon :direction="$sortBy === 'department_name' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', [
                            'sort_by' => 'usertype',
                            'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc',
                            'perPage' => $perPage,
                            'page' => $userList->currentPage(),
                            'query' => $query
                        ]) }}">
                            User Type <x-icons.sort-icon :direction="$sortBy === 'usertype' ? $sortOrder : null" />
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('userList', [
                            'sort_by' => 'is_deleted',
                            'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc',
                            'perPage' => $perPage,
                            'page' => $userList->currentPage(),
                            'query' => $query
                        ]) }}">
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
                        <td>{{ $item->middlename }}</td>
                        <td>{{ $item->lastname }}</td>
                        <td>{{ $item->email }}</td>
                        <td>
                            <x-department :deptId="$item->dept_id" />
                        </td>
                        <td>{{ $item->usertype }}</td>
                        <td class="items-center space-x-2">
                            @include('components.user-status', ['is_deleted' => $item->is_deleted])
                        </td>
                        <td class="text-center flex place-content-center">
                            @include('components.user-list-actions', ['item' => $item])
                        </td>
                    </tr>
                    @empty
                    <tr class="text-center text-gray-800">
                        <td colspan='10' style="color: rgb(177, 177, 177)">No List</td>
                    </tr>
                    @endforelse
                </x-slot>
            </x-table>
        </div>
        <!-- Card Layout for small screens -->
        <div class="block md:hidden w-full">
            @forelse($userList as $item)
            <div class="bg-white shadow rounded-lg p-4 mb-4 flex flex-col">
                {{-- User Details (Left Aligned) --}}
                <div class="flex-grow">
                    <p class="text-left"><strong>ID:</strong> {{ $item->id }}</p>
                    <p class="text-left"><strong>Employee ID:</strong> {{ $item->employee_id }}</p>
                    <p class="text-left"><strong>First Name:</strong> {{ $item->firstname }}</p>
                    <p class="text-left"><strong>Middle Name:</strong> {{ $item->middlename }}</p>
                    <p class="text-left"><strong>Last Name:</strong> {{ $item->lastname }}</p>
                    <p class="text-left"><strong>Email:</strong> {{ $item->email }}</p>
                    <p class="text-left"><strong>Department:</strong> <x-department :deptId="$item->dept_id" /></p>
                    <p class="text-left"><strong>User Type:</strong> {{ $item->usertype }}</p>
                    <p class="text-left"><strong>Status:</strong>
                        @include('components.user-status', ['is_deleted' => $item->is_deleted])
                    </p>
                </div>

                {{-- Action Buttons (Lower Right) --}}
                <div class="flex justify-end">
                    <div class="flex space-x-2">
                        @include('components.user-list-actions', ['item' => $item])
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-800">No List</p>
            @endforelse
        </div>
    </div>
</div>

@include('admin.modal.editUser')

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
</script>

@endsection
