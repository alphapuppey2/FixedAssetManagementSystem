<!-- resources/views/user/profile_password.blade.php -->
@extends('user.home')
@include('components.icons')

@section('content')
<div class="max-w-xl mx-auto"> <!-- Added padding for small screens -->

    <!-- Profile Photo and User Name -->
    <div class="text-center mb-6">
        <img src="{{ auth()->user()->userPicture ? asset('storage/' . auth()->user()->userPicture) : asset('images/default_profile.jpg') }}"
             alt="Profile Image"
             class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-2 border-gray-300 mx-auto transition-all duration-300">
        <!-- Adjusted size for small screens with transition for smooth resizing -->

        <h2 class="text-xl sm:text-3xl font-semibold mt-2">
            {{ auth()->user()->firstname ?? 'Guest' }}
            {{ auth()->user()->middlename ? auth()->user()->middlename . ' ' : '' }}
            {{ auth()->user()->lastname ?? '' }}
        </h2> <!-- Adjusted text size -->
    </div>

    <!-- Change Password Form -->
    <div class="p-4 sm:p-6 mb-6"> <!-- Adjusted padding for small screens -->
        <form method="POST" action="{{ route('user.changePassword') }}">
            @csrf
            @method('PATCH')

            <!-- Old Password -->
            <div class="mb-4">
                <label for="old_password" class="block text-sm sm:text-base font-medium text-gray-700 mb-2">
                    Old Password
                </label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <div class="bg-gray-200 p-2 sm:p-3 rounded-l-md">
                        @yield('passwordIcon')
                    </div>
                    <input type="password" id="old_password" name="old_password"
                           class="w-full px-3 py-2 border-0 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                           required>
                </div>
                @error('old_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div class="mb-4">
                <label for="new_password" class="block text-sm sm:text-base font-medium text-gray-700 mb-2">
                    New Password
                </label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <div class="bg-gray-200 p-2 sm:p-3 rounded-l-md">
                        @yield('passwordIcon')
                    </div>
                    <input type="password" id="new_password" name="new_password"
                           class="w-full px-3 py-2 border-0 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                           required>
                </div>
                @error('new_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm New Password -->
            <div class="mb-4">
                <label for="new_password_confirmation" class="block text-sm sm:text-base font-medium text-gray-700 mb-2">
                    Confirm New Password
                </label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <div class="bg-gray-200 p-2 sm:p-3 rounded-l-md">
                        @yield('passwordIcon')
                    </div>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                           class="w-full px-3 py-2 border-0 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                           required>
                </div>
                @error('new_password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons Section -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4 mt-6">
                <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full sm:w-auto text-sm sm:text-base">
                    Save Changes
                </button>
                <a href="{{ route('user.profile') }}"
                   class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 w-full sm:w-auto text-center text-sm sm:text-base">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
