@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ "User List" }}
    </h2>
@endsection

@section('content')

    <div class="contents relative flex ">
        {{-- Cards --}}
        <div class="text-center max-w-100 flex justify-center sm:flex-col md:flex-row ">
            <x-table class="table table-striped">
                <x-slot name='header'>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Action</th>
                </x-slot>
                <x-slot name='slot'>
                    @if(!$userList->isEmpty())
                        @foreach($userList as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->firstname }}</td>
                                <td>{{ $item->middlename }}</td>
                                <td>{{ $item->lastname }}</td>
                                <td>{{ $item->email }}</td>
                                <td class="items-center space-x-2">
                                    @if($item->status === 'active')
                                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500"></span>
                                        <span>Active</span>
                                    @else
                                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                        <span>Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at }}</td>
                                <td class="text-center flex place-content-center">
                                    <a href="#" class="text-blue-500 editUserBtn" 
                                        data-id="{{ $item->id }}"
                                        data-firstname="{{ $item->firstname }}"
                                        data-middlename="{{ $item->middlename }}"
                                        data-lastname="{{ $item->lastname }}"
                                        data-email="{{ $item->email }}"
                                        data-address="{{ $item->address }}"
                                        data-contact="{{ $item->contact }}"
                                        data-user-type="{{ $item->usertype }}"
                                        data-birthdate="{{ $item->birthdate }}"
                                        data-status="{{ $item->status }}"
                                        data-gender="{{ $item->gender }}"
                                        data-dept-id="{{ $item->dept_id }}"
                                        data-account-created="{{ $item->created_at }}"
                                        data-account-updated="{{ $item->updated_at }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" style="color:#2196f3;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('user.delete', ['id' => $item->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate this user?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else 
                        <tr class="text-center text-gray-800">
                            <td colspan='5' style="color: rgb(177, 177, 177)" >No List</td>
                        </tr>
                    @endif
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

                document.getElementById('editUserModal').classList.remove('hidden');
            });
        });

        document.getElementById('cancelEdit').addEventListener('click', () => {
            document.getElementById('editUserModal').classList.add('hidden');
        });
    </script>
@endsection




