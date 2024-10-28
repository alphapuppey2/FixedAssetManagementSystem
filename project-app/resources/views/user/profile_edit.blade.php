<!-- resources/views/user/profile_edit.blade.php -->
@extends('user.home')
@include('components.icons')

@section('content')
<div class="max-w-xl mx-auto"> <!-- Added padding for small screens -->

    <!-- Profile Edit Form -->
    <div class="p-4 sm:p-6 mb-6"> <!-- Adjusted padding -->
        <form method="POST" action="{{ route('user.profile_update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Profile Photo with Centered Camera Icon -->
            <div class="relative flex justify-center mb-6">
                <!-- Profile Photo -->
                <img id="profilePhotoPreview"
                    src="{{ auth()->user()->userPicture ? asset('storage/' . auth()->user()->userPicture) : asset('images/default_profile.jpg') }}"
                    alt="Profile Image"
                    class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-2 border-gray-300 transition-all duration-300">


                <!-- Centered Camera Icon -->
                <label for="profile_photo"
                       class="absolute inset-0 flex justify-center items-center cursor-pointer">
                    <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" onchange="previewImage(event)" />
                    <div class="bg-gray-200 mt-20 sm:mt-28 p-2 rounded-full shadow-md">
                        @yield('cameraIcon')
                    </div>

                </label> <!-- Keeps the camera icon centered -->
            </div>

            <!-- Centered User Name and Edit Icon -->
            <div class="flex items-center justify-center space-x-2 mb-4 sm:mb-6">
                <h2 class="text-xl sm:text-2xl font-semibold text-center">
                    {{ auth()->user()->firstname ?? 'Guest' }}
                    {{ auth()->user()->middlename ? auth()->user()->middlename . ' ' : '' }}
                    {{ auth()->user()->lastname ?? '' }}
                </h2>
                <a href="{{ route('user.profile') }}" class="text-gray-600 hover:text-blue-500 flex items-center">
                    @yield('editIcon')
                </a>
            </div>

            <!-- Location Field -->
            <div class="mb-4">
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <div class="bg-gray-200 p-2 sm:p-3 rounded-l-md">
                        @yield('locationIcon')
                    </div>
                    <input type="text" id="location" name="location"
                           class="w-full px-3 py-2 border-0 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           value="{{ auth()->user()->address ?? '' }}">
                </div>
            </div>

            <!-- Contact Field -->
            <div class="mb-4">
                <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">Contact</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <div class="bg-gray-200 p-2 sm:p-3 rounded-l-md">
                        @yield('contactIcon')
                    </div>
                    <input type="text" id="contact" name="contact"
                           class="w-full px-3 py-2 border-0 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           value="{{ auth()->user()->contact ?? '' }}">
                </div>
            </div>

            <!-- Buttons Section -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4 mt-6">
                <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full sm:w-auto">
                    Save
                </button>
                <a href="{{ route('user.profile') }}"
                   class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 w-full sm:w-auto text-center">
                    Cancel
                </a> <!-- Added 'text-center' class for small screens -->
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Image Preview -->
<script>
    function previewImage(event) {
        const input = event.target;
        const file = input.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const imgElement = document.getElementById('profilePhotoPreview');
            imgElement.src = e.target.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    document.getElementById('profile_photo').addEventListener('change', previewImage);
</script>

@endsection
