<!-- resources/views/dept_head/profile.blade.php -->
@include('components.icons')
@extends('layouts.app')

@section('header')
<h2 class="font-medium text-lg sm:text-xl text-gray-800 leading-tight text-center sm:text-left">
    {{ "Profile" }}
</h2>
@endsection

@section('content')

<div class="relative">
    <div class="flex justify-end flex-col sm:flex-row sm:space-x-2">
        <div id="profile-buttons" class="flex flex-col sm:flex-row sm:space-x-2">
            <a href="#" onclick="editProfile(event)"
               class="mb-1 sm:mb-0 px-2 sm:px-4 py-2 sm:py-2 text-xs sm:text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 w-full sm:w-auto text-center">
                Edit Profile
            </a>
            <a href="{{ route('dept_head.profile_password') }}"
               class="mb-1 sm:mb-0 px-2 sm:px-4 py-2 sm:py-2 text-xs sm:text-sm bg-blue-200 dark:bg-blue-700 text-blue-800 dark:text-blue-200 rounded hover:bg-blue-300 dark:hover:bg-blue-600 w-full sm:w-auto text-center">
                Change Password
            </a>
        </div>
        <div id="edit-buttons" class="hidden flex flex-col sm:flex-row sm:space-x-2">
            <a href="#" onclick="saveProfile(event)"
               class="mb-1 sm:mb-0 px-2 sm:px-4 py-2 sm:py-2 text-xs sm:text-sm bg-green-200 dark:bg-green-700 text-green-800 dark:text-green-200 rounded hover:bg-green-300 dark:hover:bg-green-600 w-full sm:w-auto text-center">
                Save
            </a>
            <a href="#" onclick="cancelEdit(event)"
               class="mb-1 sm:mb-0 px-2 sm:px-4 py-2 sm:py-2 text-xs sm:text-sm bg-red-200 dark:bg-red-700 text-red-800 dark:text-red-200 rounded hover:bg-red-300 dark:hover:bg-red-600 w-full sm:w-auto text-center">
                Cancel
            </a>
        </div>
    </div>
</div>

<div class="">
    <div class="max-w-4xl mx-auto px-3 sm:px-5 lg:px-6">
        <form id="profile-form" method="POST" enctype="multipart/form-data" action="{{ route(Auth::user()->usertype . '.profile_update') }}">
            @csrf
            @method('PATCH')
            <div class="p-4">
                <div class="text-center pb-4 mb-4">
                    <div class="relative flex justify-center mb-3">
                        <img id="profilePhoto"
                             src="{{ auth()->user()->userPicture ? asset('storage/' . auth()->user()->userPicture) : asset('images/default_profile.jpg') }}"
                             alt="Profile Picture"
                             class="w-20 h-20 sm:w-32 sm:h-32 rounded-full object-cover border-2 border-gray-300">
                        <label id="cameraIcon" for="profile_photo"
                               class="hidden absolute bottom-0 right-21 bg-gray-200 p-1 sm:p-2 rounded-full cursor-pointer shadow-md">
                            <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" onchange="previewImage(event)" />
                            @yield('cameraIcon')
                        </label>
                    </div>
                    <h3 class="text-lg sm:text-xl font-medium">
                        {{ auth()->user()->firstname ?? 'Guest' }}
                        {{ auth()->user()->middlename ? auth()->user()->middlename . ' ' : '' }}
                        {{ auth()->user()->lastname ?? '' }}
                    </h3>
                </div>

                <div class="border-b-2 border-t-2 border-gray-100 pb-8 pt-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm sm:text-base">
                        <div class="flex items-center">
                            @yield('locationIcon')
                            <div class="ml-1 flex-1">
                                <span id="address-display">{{ Auth::user()->address ?? 'N/A' }}</span>
                                <input id="address-edit" name="location" type="text"
                                       class="hidden w-full border-gray-300 rounded-md" value="{{ Auth::user()->address }}">
                            </div>
                        </div>
                        <div class="flex items-center">
                            @yield('emailIcon')
                            <div class="ml-1 flex-1">
                                <span>{{ Auth::user()->email ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            @yield('contactIcon')
                            <div class="ml-1 flex-1">
                                <span id="contact-display">{{ formatContactNumber(Auth::user()->contact) ?? 'N/A' }}</span>
                                <input id="contact-edit" name="contact" type="text"
                                       class="hidden w-full border-gray-300 rounded-md" value="{{ Auth::user()->contact }}">
                            </div>
                        </div>
                        <div class="flex items-center">
                            @yield('idNumberIcon')
                            <div class="ml-1 flex-1">
                                <span>{{ Auth::user()->employee_id ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/profile.js') }}"></script>

@if(session('status'))
<div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-2 sm:px-3 py-1 sm:py-2 rounded shadow-lg text-xs sm:text-sm">
    {{ session('status') }}
</div>
@endif

@endsection

@php
function formatContactNumber($number) {
    $cleaned = preg_replace('/\D/', '', $number);
    if (substr($cleaned, 0, 1) === '0') {
        $cleaned = substr($cleaned, 1);
    }
    return '+63 ' . $cleaned;
}
@endphp
