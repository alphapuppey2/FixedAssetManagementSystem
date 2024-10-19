<!-- resources/views/user/profile.blade.php -->
@extends('user.home')
@include('components.icons')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center sm:text-left">
        {{ "Profile" }}
    </h2> <!-- Made header centered on small screens -->
@endsection

@section('content')
    <div class="max-w-xl mx-auto sm:px-0"> <!-- Added padding for small screens -->

        <!-- Profile Photo -->
        <div class="flex justify-center mb-6">
            <img src="{{ auth()->user()->userPicture ? asset('storage/profile_photos/' . auth()->user()->userPicture) : asset('images/default_profile.jpg') }}"
                 alt="Profile Image"
                 class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-2 border-gray-300">
                 <!-- Adjusted size for small screens -->
        </div>

        <!-- User Name -->
        <div class="text-center mb-4 flex flex-col sm:flex-row items-center justify-center">
            <!-- Changed to column on small screens -->
            <h2 class="text-2xl sm:text-3xl font-semibold mb-2 sm:mb-0 sm:mr-2">
                {{ auth()->user()->firstname ?? 'Guest' }}
                {{ auth()->user()->middlename ? auth()->user()->middlename . ' ' : '' }}
                {{ auth()->user()->lastname ?? '' }}
            </h2>
            <a href="{{ route('user.profile_edit') }}" class="text-gray-600 hover:text-blue-500">
                @yield('editIcon')
            </a>
        </div>

<!-- User Details -->
<div class="p-3 sm:p-4 mb-4">
    <!-- Adjusted padding for small screens -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6"> <!-- New grid layout -->
        @foreach([
            ['Location', auth()->user()->address, 'locationIcon'],
            ['Email', auth()->user()->email, 'emailIcon'],
            ['Contact', formatContactNumber(auth()->user()->contact), 'contactIcon'],
            ['ID Number', auth()->user()->employee_id, 'idNumberIcon']
        ] as [$label, $value, $icon])

        <!-- Card Item -->
        <div class="flex items-center space-x-4 bg-gray-50 p-4 rounded-lg shadow-sm">
            <div class="flex-shrink-0"> <!-- Icon container -->
                @yield($icon)
            </div>
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-700">{{ $label }}</h3>
                <!-- Adjusted text size for smaller screens -->
                <p class="text-sm sm:text-base text-gray-600">{{ $value ?? 'N/A' }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>


        <!-- Change Password Button -->
        <div class="flex justify-center">
            <a href="{{ route('user.profile_password') }}"
               class="bg-blue-500 text-white text-sm sm:text-base px-3 py-2 sm:px-4 sm:py-2 rounded hover:bg-blue-600">
                Change Password
            </a> <!-- Adjusted button size for small screens -->
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
