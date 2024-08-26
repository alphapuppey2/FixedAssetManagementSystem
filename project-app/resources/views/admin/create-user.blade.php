@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ "Create User" }}
    </h2>
@endsection

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex flex-wrap md:flex-nowrap">
            <!-- Form Section -->
            <div class="w-full md:w-2/3 mb-4 md:mb-0 pr-0 md:pr-4">
                <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <div class="w-full md:w-1/3 flex flex-col items-center md:items-center justify-center md:justify-start space-y-4">
                            <!-- Profile Photo Wrapper -->
                            <div class="relative">
                                <div class="w-40 h-40 rounded-full overflow-hidden bg-gray-100 border-2 border-gray-300 shadow-md">
                                    <img id="currentProfilePhoto" src="/images/default_profile.jpg" alt="Current Profile Photo" class="w-full h-full object-cover">
                                </div>
                                <!-- Upload Icon -->
                                <label for="profile_photo" class="absolute bottom-2 right-2 bg-white p-1 rounded-full cursor-pointer shadow-md hover:bg-gray-200 transition duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                    </svg>
                                </label>
                            </div>
                            <!-- Hidden File Input -->
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="hidden">
                        </div>

                        <!-- Name Fields (First, Middle, Last Name) in One Row -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                        <!-- User Type and Department -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                        <!-- Birthdate, Gender, and Contact -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">Create User</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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