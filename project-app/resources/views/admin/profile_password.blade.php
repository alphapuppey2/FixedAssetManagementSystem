{{-- 
    THIS IS THE CHANGE PASSWORD PAGE.
    LOGGED IN USER CAN CHANGE THEIR PASSWORD HERE
--}}

@extends('layouts.app')

@section('header')
        <h2 class="font-semibold text-xl text-black-800 leading-tight">
            <a href="{{ route('admin.profile') }}" class="text-black-800  hover:text-blue-700">Profile</a> &nbsp;&nbsp; > &nbsp;&nbsp;Change Password
        </h2>
@endsection

@section('content')
    <div class="max-w-xl mx-auto py-12">
        <form method="POST" action="{{ route('admin.profile_password') }}">
            @csrf
            @method('PATCH')

            <!-- Current Password -->
            <div class="mb-4 flex items-center">
                <label for="old_password" class="w-1/3 text-gray-700 text-base font-bold">Old Password</label>
                <input id="old_password" type="password" name="old_password" required class="w-2/3 text-base shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            @error('old_password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <!-- New Password -->
            <div class="mb-4 flex items-center">
                <label for="new_password" class="w-1/3 text-gray-700 text-base font-bold">New Password</label>
                <input id="new_password" type="password" name="new_password" required class="w-2/3 text-base shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            @error('new_password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <!-- Confirm New Password -->
            <div class="mb-4 flex items-center">
                <label for="new_password_confirmation" class="w-1/3 text-gray-700 text-base font-bold">Confirm New Password</label>
                <input id="new_password_confirmation" type="password" name="new_password_confirmation" required class="w-2/3 text-base shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            @error('new_password_confirmation')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <!-- Submit and Cancel Buttons -->
            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Confirm</button>
                <a href="{{ route('admin.profile') }}" class="ml-4 bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</a>
            </div>
        </form>
    </div>
@endsection
