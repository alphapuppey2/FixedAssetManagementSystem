@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ "Create User" }}
    </h2>
@endsection

@section('content')
<div class="container mx-auto p-4">
    <div class="flex">
        <!-- Form Section -->
        <div class="w-2/3 pr-4">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 gap-4">
                    <!-- Name Fields (First, Middle, Last Name) in One Row -->
                    <div class="grid grid-cols-3 gap-4">
                        <!-- First Name -->
                        <div>
                            <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label for="middlename" class="block text-sm font-medium text-gray-700">Middle Name</label>
                            <input type="text" id="middlename" name="middlename" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                    </div>

                    {{--
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" readonly>
                        </div>
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="text" id="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" readonly>
                        </div>
                    </div>
                    --}}

                    <div class="grid grid-cols-3 gap-4">
                        <!-- User Type -->
                        <div>
                            <label for="usertype" class="block text-sm font-medium text-gray-700">User Type</label>
                            <select id="usertype" name="usertype" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                                <option value="dept_head">Department Head</option>
                            </select>
                        </div>
                        <!-- Department -->
                        <div>
                            <label for="dept_id" class="block text-sm font-medium text-gray-700">Department</label>
                            <select id="dept_id" name="dept_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="" disabled selected>Select Department</option>
                                <option value="1">IT</option>
                                <option value="2">Sales</option>
                                <option value="3">Fleet</option>
                                <option value="4">Production</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <!-- Birthdate -->
                        <div>
                            <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <!-- Contact Number -->
                        <div>
                            <label for="contact" class="block text-sm font-medium text-gray-700">Contact Number</label>
                            <input type="text" id="contact" name="contact" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea id="address" name="address" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    {{--
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <input type="text" id="status" name="status" value="active" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" readonly>
                    </div>
                    --}}

                    <div class="flex justify-end mt-4">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create User</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Profile Picture -->
        <div class="w-1/3">
            <div class="flex items-center justify-center h-full">
                <img src="path_to_default_profile_picture" alt="Profile Picture" class="w-48 h-48 object-cover border-2 border-gray-300 rounded-full">
            </div>
        </div>
    </div>
</div>
@endsection
