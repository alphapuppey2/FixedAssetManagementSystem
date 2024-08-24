<!-- resources/views/dept_head/profile.blade.php -->
@include('components.icons')

<x-app-layout>
    <x-slot name="header">
        <h2 class="pb-3 mr-3 font-semibold text-2xl text-black-800 leading-tight border-b-2 border-gray-200">
            Profile
        </h2>
    </x-slot>

    <div class="relative">
        <div class="absolute top-0 right-0 mt-3 mr-3 flex flex-col">
            <!-- Container for buttons -->
            <div id="profile-buttons" class="flex flex-col">
                <!-- Edit Profile and Change Password buttons -->
                <a href="#" onclick="editProfile(event)" class="mb-2 inline-block px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-center w-44">
                    Edit Profile
                </a>
                <a href="{{ route('dept_head.profile_password') }}" class="inline-block px-4 py-2 text-sm bg-blue-500 text-white rounded hover:bg-blue-600 text-center w-44">
                    Change Password
                </a>
            </div>
            <div id="edit-buttons" class="hidden flex flex-col">
                <!-- Save and Cancel buttons -->
                <a href="#" onclick="saveProfile(event)" class="mb-2 inline-block px-4 py-2 text-sm bg-green-500 text-white rounded hover:bg-green-600 text-center w-44">
                    Save
                </a>
                <a href="#" onclick="cancelEdit(event)" class="inline-block px-4 py-2 text-sm bg-red-500 text-white rounded hover:bg-red-600 text-center w-44">
                    Cancel
                </a>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form id="profile-form" method="POST" enctype="multipart/form-data" action="{{ route(Auth::user()->usertype . '.profile_update') }}">
                @csrf
                @method('PATCH')
                <div class="p-6">
                    <!-- Profile Header -->
                    <div class="text-center pb-6 mb-6">
                        <div class="relative flex justify-center mb-4">
                            <!-- Profile Photo with Camera Icon (Initially Hidden) -->
                            <img id="profilePhoto" src="{{ auth()->user()->userPicture ? asset('storage/profile_photos/' . auth()->user()->userPicture) : asset('images/default_profile.jpg') }}" alt="Profile Picture" class="w-32 h-32 rounded-full object-cover border-2 border-gray-300">
                            <label id="cameraIcon" for="profile_photo" class="hidden absolute bottom-3 bg-gray-200 p-2 rounded-full cursor-pointer shadow-md transform translate-x-1/2 translate-y-1/2">
                                <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" onchange="previewImage(event)" />
                                @yield('cameraIcon')
                            </label>
                        </div>
                        <h3 class="text-3xl font-semibold">{{ auth()->user()->firstname ?? 'Guest' }} 
                            {{ auth()->user()->middlename ? auth()->user()->middlename . ' ' : '' }} 
                            {{ auth()->user()->lastname ?? '' }}
                        </h3>
                    </div>

                    <!-- Profile Details -->
                    <div class="border-b-2 border-t-2 border-gray-100 pb-10 pt-10">
                        <div class="grid grid-cols-2 text-black text-l ml-24">
                            <div class="flex items-center">
                                @yield('locationIcon')
                                <div class="ml-2 flex-1">
                                    <span id="address-display">{{ Auth::user()->address ?? 'N/A'}}</span>
                                    <input id="address-edit" name="location" type="text" class="hidden w-full border-gray-300 rounded-md" value="{{ Auth::user()->address }}">
                                </div>
                            </div>
                            <div class="flex items-center ml-6">
                                @yield('emailIcon')
                                <div class="ml-2 flex-1">
                                    <span>{{ Auth::user()->email ?? 'N/A'}}</span>
                                </div>
                            </div>
                            <div class="flex items-center mt-4">
                                @yield('contactIcon')
                                <div class="ml-2 flex-1">
                                <span id="contact-display">{{ formatContactNumber(Auth::user()->contact) ?? 'N/A' }}</span>
                                    <input id="contact-edit" name="contact" type="text" class="hidden w-full border-gray-300 rounded-md" value="{{ Auth::user()->contact }}">
                                </div>
                            </div>
                            <div class="flex items-center mt-4 ml-6">
                                @yield('idNumberIcon')
                                <div class="ml-2 flex-1">
                                    <span>{{ Auth::user()->id ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/profile.js') }}"></script>

    <!-- Toast Notification -->
    @if(session('status'))
        <div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('status') }}
        </div>
    @endif

</x-app-layout>

@php
function formatContactNumber($number) {
    $cleaned = preg_replace('/\D/', '', $number);

    if (substr($cleaned, 0, 1) === '0') {
        $cleaned = substr($cleaned, 1);
    }

    return '+63 ' . $cleaned;
}
@endphp
