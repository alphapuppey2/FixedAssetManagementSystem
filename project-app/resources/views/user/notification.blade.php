@extends('user.home')

@section('notification-content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Unread Notifications</h2>
        <form action="{{ route('markAllAsRead') }}" method="POST">
            @csrf
            <button type="submit" class="text-red-500 hover:text-red-700">Clear All</button>
        </form>
    </div>

    <!-- Notification List -->
    <div class="space-y-4">
        @forelse(auth()->user()->unreadNotifications as $notification)
            <div class="bg-white border border-gray-300 rounded p-4">
                <p class="text-lg font-semibold">{{ $notification->data['message'] }}</p>
                <p class="text-sm text-gray-500">{{ $notification->created_at->format('Y-m-d h:i A') }}</p>
                <form action="{{ route('markAsRead', $notification->id) }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="text-blue-500 hover:text-blue-700">Mark as read</button>
                </form>
            </div>
        @empty
            <p class="text-gray-500">No unread notifications</p>
        @endforelse
    </div>
@endsection
