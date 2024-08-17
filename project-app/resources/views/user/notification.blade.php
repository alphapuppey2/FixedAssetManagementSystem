<!-- resources/views/user/notification.blade.php -->
@extends('user.home')

@section('notification-content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Unread</h2>
        <a href="#" class="text-red-500 hover:text-red-700">Clear All</a>
    </div>

    <!-- Notification List -->
    <div class="space-y-4">
        <!-- Example Notification 1 -->
        <div class="bg-white border border-gray-300 rounded p-4">
            <p class="text-lg font-semibold">Tony Stark <span class="font-normal">approved your request</span></p>
            <p class="text-sm text-gray-500">2024-08-16 02:30 PM</p>
        </div>

        <!-- Example Notification 2 -->
        <div class="bg-white border border-gray-300 rounded p-4">
            <p class="text-lg font-semibold">Steve Rogers <span class="font-normal">denied your request</span></p>
            <p class="text-sm text-gray-500">2024-08-15 09:45 AM</p>
        </div>

        <!-- More notifications as needed -->
    </div>
@endsection
