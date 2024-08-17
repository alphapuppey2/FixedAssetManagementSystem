<!-- resources/views/user/profile_edit.blade.php -->
@extends('user.home')

@section('profile_edit-content')
<div class="max-w-xl mx-auto">
    <!-- Profile Photo with Camera Icon -->
    <div class="relative flex justify-center mb-6">
        <!-- Profile Photo -->
        <img src="" alt="" class="w-32 h-32 rounded-full object-cover border-2 border-gray-300">

        <!-- Camera Icon -->
        <label for="profile_photo" class="absolute bottom-3 bg-gray-200 p-2 rounded-full cursor-pointer shadow-md transform translate-x-1/2 translate-y-1/2">
            <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" />
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
            </svg>
        </label>
    </div>

    <!-- User Name -->
    <div class="text-center mb-4 flex items-center justify-center">
        <h2 class="text-3xl font-semibold mr-2">Name</h2>
        <a href="{{ route('user.profile') }}" class="text-gray-600 hover:text-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
            </svg>
        </a>
    </div>

    <!-- Profile Edit Form -->
    <div class="bg-white shadow-lg rounded p-6 mb-6">
        <form method="POST" action="">
            @csrf
            @method('PATCH')

            <!-- Location -->
            <div class="mb-4">
                <label for="location" class="block text-gray-700 text-sm font-medium mb-2">Location</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <div class="bg-gray-200 p-2 rounded-l-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </div>
                    <input type="text" id="location" name="location" class="w-full px-3 py-2 border-0 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="123 Main Street, City, Country">
                </div>
            </div>

            <!-- Contact -->
            <div class="mb-4">
                <label for="contact" class="block text-gray-700 text-sm font-medium mb-2">Contact</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <div class="bg-gray-200 p-2 rounded-l-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <input type="text" id="contact" name="contact" class="w-full px-3 py-2 border-0 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="+123 456 7890">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
                <a href="{{ route('user.profile') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
