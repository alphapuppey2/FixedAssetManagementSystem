<!-- resources/views/user/profile.blade.php -->
@extends('user.home')
@include('components.icons')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ "Profile" }}
    </h2>
@endsection
@section('content')
    <div class="max-w-xl mx-auto">
        <!-- Profile Photo -->
        <div class="flex justify-center mb-6">
            <img src="{{ auth()->user()->userPicture ? asset('storage/profile_photos/' . auth()->user()->userPicture) : asset('images/default_profile.jpg') }}" alt="Profile Image" class="w-32 h-32 rounded-full object-cover border-2 border-gray-300">
        </div>

        <!-- User Name -->
        <div class="text-center mb-4 flex items-center justify-center">
            <h2 class="text-3xl font-semibold mr-2">{{ auth()->user()->firstname ?? 'Guest' }}
                {{ auth()->user()->middlename ? auth()->user()->middlename . ' ' : '' }}
                {{ auth()->user()->lastname ?? '' }}
            </h2>
            <a href="{{ route('user.profile_edit') }}" class="text-gray-600 hover:text-blue-500">
                @yield('editIcon')
            </a>
        </div>

        <!-- User Details -->
        <div class="bg-white shadow-lg rounded p-6 mb-6">
            <div class="mb-4 flex items-center">
                <div class ="mr-3">
                    @yield('locationIcon')
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-700">Location</h3>
                    <p class="text-gray-600">{{ auth()->user()->address ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="mb-4 flex items-center">
                <div class ="mr-3">
                    @yield('emailIcon')
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-700">Email</h3>
                <p class="text-gray-600">{{ auth()->user()->email ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="mb-4 flex items-center">
                <div class ="mr-3">
                    @yield('contactIcon')
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-700">Contact</h3>
                    <p class="text-gray-600">{{ formatContactNumber(auth()->user()->contact) ?? 'N/A' }}</p>
                </div>

            </div>
            <div class="mb-4 flex items-center">
                <div class ="mr-3">
                    @yield('idNumberIcon')
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-700">ID Number</h3>
                    <p class="text-gray-600">{{ auth()->user()->id ?? 'N/A' }}</p>
                </div>

            </div>
        </div>

        <!-- Change Password Button -->
        <div class="flex justify-center">
            <a href="{{ route('user.profile_password') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Change Password</a>
        </div>
    </div>
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
