@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ "User List" }}
    </h2>
@endsection

@section('content')

    <div class="mb-4">
        <form method="GET" action="{{ route('searchUsers') }}" class="flex">
            <input type="text" name="query" placeholder="Search by name or email" class="border border-gray-300 rounded-l px-4 py-2 w-60">
            <button type="submit" class="bg-blue-500 text-white rounded-r px-3 py-1 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>
        </form>
    </div>

    <div class="contents relative flex ">
        <div class="text-center max-w-100 flex justify-center sm:flex-col md:flex-row ">
            <x-table class="table table-striped">
                <x-slot name='header'>
                    <th>ID</th>
                    <th>Employee ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>User Type</th>
                    <th>Status</th>
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
                            <td>{{ $item->department }}</td>
                            <td>{{ $item->usertype }}</td>
                            <td class="items-center space-x-2">
                                @include('components.user-status', ['status' => $item->status])
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
    </div>

    @include('admin.modal.edit-user')

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
                document.getElementById('status').value = btn.getAttribute('data-status');
                document.getElementById('account_created').value = btn.getAttribute('data-account-created');
                document.getElementById('account_updated').value = btn.getAttribute('data-account-updated');
                document.getElementById('usertype').value = btn.getAttribute('data-user-type');

                const photoPath = btn.getAttribute('data-photo');
                document.getElementById('currentProfilePhoto').src = photoPath;

                document.getElementById('editUserModal').classList.remove('hidden');
            });
        });

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




