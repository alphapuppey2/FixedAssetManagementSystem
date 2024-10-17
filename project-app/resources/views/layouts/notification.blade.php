<!-- resources/views/layouts/notification.blade.php -->
@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notifications</h2>
@endsection

@section('content')
<div class="p-6 bg-white h-full rounded-md shadow-md">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Latest Updates</h3>
        <button
            onclick="clearNotifications()"
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
            Mark All as Read
        </button>
    </div>

    <!-- Notification List -->
    <ul class="divide-y divide-gray-300 max-h-[40rem] overflow-y-auto">
        @forelse ($notifications as $notification)
            <li onclick="navigateTo('{{ $notification->data['action_url'] ?? '#' }}', '{{ $notification->id }}')"
                class="p-4 hover:bg-gray-100 cursor-pointer flex items-center space-x-4">

                <!-- Unread Indicator -->
                @if (is_null($notification->read_at))
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                @else
                    <div class="w-3 h-3"></div>
                @endif

                <!-- Notification Content -->
                <div class="flex justify-between items-center w-full">
                    <div>
                        <h4 class="font-semibold text-gray-800">{{ $notification->data['title'] ?? 'No Title' }}</h4>
                        <p class="text-sm text-gray-600">{{ $notification->data['message'] ?? 'No Message' }}</p>
                        <span class="text-xs text-gray-500">
                            By: {{ $notification->data['authorized_user_name'] ?? 'System' }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                        <button onclick="deleteNotification('{{ $notification->id }}')"
                                class="text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </li>
        @empty
            <li class="p-4 text-center text-gray-500">No notifications found.</li>
        @endforelse
    </ul>
</div>

<!-- JavaScript -->
<script>
function clearNotifications() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(response => {
        if (response.ok) {
            alert('All notifications marked as read!');
            location.reload();
        } else {
            alert('Failed to mark notifications as read.');
        }
    }).catch(error => console.error('Error:', error));
}

function markAsRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(response => {
        if (response.ok) {
            location.reload(); // Reload to reflect the change
        } else {
            alert('Failed to mark notification as read.');
        }
    }).catch(error => console.error('Error:', error));
}

function deleteNotification(id) {
    fetch(`/notifications/${id}/delete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(response => {
        if (response.ok) {
            alert('Notification deleted!');
            location.reload();
        } else {
            alert('Failed to delete notification.');
        }
    }).catch(error => console.error('Error:', error));
}

// Navigate to the action URL and mark the notification as read
function navigateTo(url, id) {
    if (url !== '#') {
        markAsRead(id); // Mark the notification as read
        window.location.href = url; // Redirect to the action URL
    }
}
</script>
@endsection
