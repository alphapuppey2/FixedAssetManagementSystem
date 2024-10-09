<!-- resources/views/user/profile_edit.blade.php -->
@extends('user.home')
@include('components.icons')

@section('profile_edit-content')
<div class="max-w-xl mx-auto">

    <!-- Profile Edit Form -->
    <div class="bg-white shadow-lg rounded p-6 mb-6">
        <form method="POST" action="{{ route('user.profile_update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Profile Photo with Camera Icon -->
            <div class="relative flex justify-center mb-6">
                <!-- Profile Photo -->
                <img id="profilePhotoPreview" src="{{ auth()->user()->userPicture ? asset('storage/profile_photos/' . auth()->user()->userPicture) : asset('images/default_profile.jpg') }}" alt="Profile Image" class="w-32 h-32 rounded-full object-cover border-2 border-gray-300">

                <!-- Camera Icon -->
                <label for="profile_photo" class="absolute bottom-3 bg-gray-200 p-2 rounded-full cursor-pointer shadow-md transform translate-x-1/2 translate-y-1/2">
                    <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" onchange="previewImage(event)" />
                    @yield('cameraIcon')
                </label>
            </div>

            <!-- User Name -->
            <div class="text-center mb-4 flex items-center justify-center">
                <h2 class="text-3xl font-semibold mr-2">{{ auth()->user()->firstname ?? 'Guest' }}
                    {{ auth()->user()->middlename ? auth()->user()->middlename . ' ' : '' }}
                    {{ auth()->user()->lastname ?? '' }}
                </h2>
                <a href="{{ route('user.profile') }}" class="text-gray-600 hover:text-blue-500">
                    @yield('editIcon')
                </a>
            </div>

            <!-- Location -->
            <div class="mb-4">
                <label for="location" class="block text-gray-700 text-sm font-medium mb-2">Location</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <div class="bg-gray-200 p-2 rounded-l-md">
                        @yield('locationIcon')
                    </div>
                    <input type="text" id="location" name="location" class="w-full px-3 py-2 border-0 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ auth()->user()->address ?? '' }}">
                </div>
            </div>

            <!-- Contact -->
            <div class="mb-4">
                <label for="contact" class="block text-gray-700 text-sm font-medium mb-2">Contact</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <div class="bg-gray-200 p-2 rounded-l-md">
                        @yield('contactIcon')
                    </div>
                    <input type="text" id="contact" name="contact" class="w-full px-3 py-2 border-0 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ auth()->user()->contact ?? '' }}">
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

<!-- JavaScript for Image Preview -->
<!-- Temp -->
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
