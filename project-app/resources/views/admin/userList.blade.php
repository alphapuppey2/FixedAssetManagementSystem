<!-- resources/views/admin/home.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ "User List" }}
        </h2>
    </x-slot>

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
                    <th>Created</th>
                    <th>Birth Date</th>
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
                                <td>{{ $item->email_verified_at }}</td>
                                <td>{{ $item->birthdate }}</td>
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
</x-app-layout>



