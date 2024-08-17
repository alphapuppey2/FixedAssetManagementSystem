<!-- resources/views/user/profile_password.blade.php -->
@extends('user.home')

@section('profile_password-content')
<div class="max-w-xl mx-auto">
    <!-- Profile Photo and User Name -->
    <div class="text-center mb-6">
        <img src="" alt="" class="w-32 h-32 rounded-full object-cover border-2 border-gray-300 mx-auto">
        <h2 class="text-3xl font-semibold mt-4">Name</h2>
    </div>

    <!-- Change Password Form -->
    <div class="bg-white shadow-lg rounded p-6 mb-6">
        <form method="POST" action="">
            @csrf
            @method('PATCH')

            <!-- Old Password -->
            <div class="mb-4">
                <label for="old_password" class="block text-gray-700 text-sm font-medium mb-2">Old Password</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <div class="bg-gray-200 p-2 rounded-l-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                        </svg>
                    </div>
                    <input type="password" id="old_password" name="old_password" class="w-full px-3 py-2 border-0 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <!-- @error('old_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>

            <!-- New Password -->
            <div class="mb-4">
                <label for="new_password" class="block text-gray-700 text-sm font-medium mb-2">New Password</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <div class="bg-gray-200 p-2 rounded-l-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                        </svg>
                    </div>
                    <input type="password" id="new_password" name="new_password" class="w-full px-3 py-2 border-0 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <!-- @error('new_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>

            <!-- Confirm New Password -->
            <div class="mb-4">
                <label for="new_password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">Confirm New Password</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <div class="bg-gray-200 p-2 rounded-l-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                        </svg>
                    </div>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="w-full px-3 py-2 border-0 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <!-- @error('new_password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Changes</button>
                <a href="{{ route('user.profile') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
