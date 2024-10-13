@extends('user.home')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ "Notification"}}
    </h2>
@endsection

@section('content')
<div class="p-6 bg-white rounded-md shadow-md">
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
    @foreach ([
        ['id'=> 1, 'title' => 'New Asset Assigned', 'message' => 'A new asset has been assigned to your department.', 'user' => 'Admin', 'time' => now()->subMinutes(19), 'unread' => true],
        ['id'=> 2, 'title' => 'Maintenance Scheduled', 'message' => 'System maintenance is scheduled for tomorrow.', 'user' => 'System', 'time' => now()->subHours(2), 'unread' => false],
        ['id'=> 3, 'title' => 'Repair Request Approved', 'message' => 'Your repair request has been approved.', 'user' => 'Dept Head', 'time' => now()->subDays(1), 'unread' => true],
        ['id'=> 4, 'title' => 'User Registered', 'message' => 'A new user registered successfully.', 'user' => 'Admin', 'time' => now()->subMinutes(5), 'unread' => false],
        ['id'=> 5, 'title' => 'User Registered', 'message' => 'A new user registered successfully.', 'user' => 'Admin', 'time' => now()->subMinutes(5), 'unread' => true]
    ] as $notification)
    <li class="p-4 hover:bg-gray-100 cursor-pointer flex items-center space-x-4">
        <!-- Unread Indicator -->
        @if ($notification['unread'])
        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
        @else
        <div class="w-3 h-3"></div> <!-- Empty space for alignment -->
        @endif

        <!-- Notification Content -->
        <div class="flex justify-between items-center w-full">
            <div>
                <h4 class="font-semibold text-gray-800">{{ $notification['title'] }}</h4>
                <p class="text-sm text-gray-600">{{ $notification['message'] }}</p>
                <span class="text-xs text-gray-500">By: {{ $notification['user'] }}</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-400">
                    {{ $notification['time']->diffForHumans() }}
                </span>
                <!-- Delete Icon -->
                <button onclick="deleteNotification({{ $notification['id'] }})" class="text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                      </svg>
                </button>
            </div>
        </div>
    </li>
    @endforeach
</ul>
</div>

<!-- JavaScript -->
<script>
function clearNotifications() {
    fetch('/notifications/clear', {
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
            alert('Failed to clear notifications.');
        }
    }).catch(error => console.error('Error:', error));
}

function deleteNotification(id) {
    fetch(`/notifications/${id}/delete`, {
        method: 'DELETE',
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
</script>
@endsection
