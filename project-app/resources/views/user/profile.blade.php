<!-- resources/views/user/profile.blade.php -->
@extends('user.home')

@section('profile-content')
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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                </svg>
            </a>
        </div>

        <!-- User Details -->
        <div class="bg-white shadow-lg rounded p-6 mb-6">
            <div class="mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                <div>
                    <h3 class="text-xl font-semibold text-gray-700">Location</h3>
                    <p class="text-gray-600">{{ auth()->user()->address ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                </svg>
                <div>
                    <h3 class="text-xl font-semibold text-gray-700">Email</h3>
                <p class="text-gray-600">{{ auth()->user()->email ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                </svg>
                <div>
                    <h3 class="text-xl font-semibold text-gray-700">Contact</h3>
                    <p class="text-gray-600">{{ formatContactNumber(auth()->user()->contact) ?? 'N/A' }}</p>
                </div>
                    
            </div>
            <div class="mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                </svg>
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
