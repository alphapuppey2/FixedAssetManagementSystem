@php
$routes = [route('user.scanQR'), route('dept_head.home'), route('admin.home')];
$homeRoute = null;
switch (Auth::user()->usertype) {
case 'user':
$homeRoute = $routes[0];
break;
case 'dept_head':
$homeRoute = $routes[1];
break;
case 'admin':
$homeRoute = $routes[2];
break;
}
$notifications = Auth::user()->unreadNotifications; // Fetch unread notifications
@endphp

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div class="flex bg-white items-center shadow-md justify-between p-2 fixed h-[60px]
    z-10 md:left-[205px] md:w-[calc(100%_-_205px)]
    max-md:left-[50px] max-md:w-[calc(100%_-_50px)] w-full">

    <!-- Left Section: Logo and Search Bar -->
    <div class="flex items-center w-full space-x-2">
        <!-- Logo Section: Hidden on small screens -->
        <div class="hidden md:flex items-center space-x-2">
            <a href="{{ $homeRoute }}" class="flex items-center space-x-2">
                <img src="{{ asset('images/system_logo.png') }}" alt="FAMAS Logo"
                     class="h-8 w-8 md:h-10 md:w-10">
                <span class="text-lg md:text-xl font-bold text-gray-800">FAMS</span>
            </a>
        </div>

        <!-- Search Bar: Larger on big screens -->
        <div class="w-full md:max-w-3xl lg:max-w-4xl mx-2">
            <form action="{{ route('search.global') }}" method="GET"
                  onsubmit="return validateSearchInput();"
                  class="flex items-center space-x-2">
                <div class="relative search-container">
                    <x-search-input
                        placeholder="{{ Auth::user()->usertype == 'admin'
                            ? 'Search for users, assets, or maintenance...'
                            : 'Search for assets or maintenance...' }}"
                        class="w-96" />
                </div>
            </form>
        </div>
    </div>

    <!-- Navigation Section -->
    <nav class="flex items-center space-x-2 md:space-x-4">
        <!-- Notification Icon -->
        <div
            x-data="{ open: false }"
            @click.away="open = false"
            x-init="open = false"
            class="relative">

            <button
                @click="open = !open"
                class="relative focus:outline-none">

                <!-- Bell Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor"
                    class="w-6 h-6 text-gray-600 transition hover:scale-110 hover:text-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>

                <!-- Notification Badge -->
                @if ($notifications->count() > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-semibold w-4 h-4 rounded-full flex items-center justify-center">
                    {{ $notifications->count() }}
                </span>
                @endif
            </button>

            <!-- Notification Dropdown -->
            <div
                x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-md shadow-lg overflow-hidden z-50">

                <!-- Header -->
                <div class="border-b px-3 py-2 bg-gray-50 flex justify-between items-center">
                    <a href="{{ route('notifications.index') }}" class="text-blue-500 hover:underline text-sm">
                        View All
                    </a>
                    <button @click="clearNotifications()" class="text-red-500 hover:underline text-sm">
                        Clear All
                    </button>
                </div>

                <!-- Notification List -->
                <ul class="divide-y divide-gray-200 max-h-56 overflow-y-auto">
                    @forelse ($notifications as $notification)
                    <li
                        @click="navigateTo('{{ $notification->data['action_url'] ?? '#' }}', '{{ $notification->id }}')"
                        class="p-3 hover:bg-gray-100 cursor-pointer flex items-start space-x-4">

                        <!-- Unread Indicator -->
                        <div class="w-3 h-3 {{ is_null($notification->read_at) ? 'bg-blue-500' : '' }} rounded-full"></div>

                        <!-- Notification Content -->
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $notification->data['title'] ?? 'No Title' }}</h4>
                            <p class="text-sm text-gray-600">{{ $notification->data['message'] ?? 'No Message' }}</p>
                            <span class="text-xs text-gray-500">By: {{ $notification->data['authorized_user_name'] ?? 'System' }}</span>
                            <span class="block text-xs text-gray-400 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </li>
                    @empty
                    <li class="p-3 text-center text-gray-500">No new notifications.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        @if(Auth::user()->usertype !== 'user')
        <nav>
            <x-dropdown2>
                <x-slot name="trigger">
                    <div class="div text-blue-950">Create New</div>
                </x-slot>

                <x-slot name="content">
                    @if (Auth::user()->usertype === 'admin')
                    <li>
                        <x-dropdown-link class="w-full pl-3 block hover:bg-blue-100" :href="route('users.create')">
                            {{ __('User') }}
                        </x-dropdown-link>
                        <x-dropdown-link class="w-full pl-3 block hover:bg-blue-100" :href="route('admin.newasset')">
                            {{ __('Asset') }}
                        </x-dropdown-link>
                        <x-dropdown-link class="w-full pl-3 block hover:bg-blue-100" :href="route('adminFormMaintenance')">
                            {{ __('Maintenance') }}
                        </x-dropdown-link>
                    </li>
                    @endif
                    @if(Auth::user()->usertype === 'dept_head')
                    <li>
                        <x-dropdown-link class="w-full pl-3 block text-blue-950 hover:bg-blue-100" :href="route('newasset')">
                            {{ __('Asset') }}
                        </x-dropdown-link>
                    </li>
                    <li>
                        <x-dropdown-link class="w-full pl-3 block hover:bg-blue-100" :href="route('formMaintenance')">
                            {{ __('Maintenance') }}
                        </x-dropdown-link>
                    </li>
                    <li>
                        <x-dropdown-link class="w-full pl-3 block hover:bg-blue-100" :href="route('newasset')">
                            {{ __('Report') }}
                        </x-dropdown-link>
                    </li>
                    @endif
                </x-slot>
            </x-dropdown2>
        </nav>
        @endif
    </nav>
</div>

<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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
            }
        }).catch(error => console.error('Error clearing notifications:', error));
    }

    function navigateTo(url, id) {
        if (url !== '#') {
            markAsRead(id); // Mark the notification as read
            window.location.href = url; // Redirect to the action URL
        }
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
                console.log('Notification marked as read.');
            } else {
                console.error('Failed to mark notification as read.');
            }
        }).catch(error => console.error('Error:', error));
    }

    function validateSearchInput() {
        const searchInput = document.querySelector('[data-search-input]');
        const toast = document.getElementById('toast');

        if (!searchInput.value.trim()) {
            if (toast) {
                toast.classList.remove('hidden'); // Show toast
                toast.classList.remove('opacity-0'); // Ensure visibility

                // Re-run the flash notification logic from your script
                setTimeout(() => {
                    toast.classList.add('opacity-0'); // Fade out
                    setTimeout(() => {
                        toast.remove(); // Remove from DOM
                    }, 300); // After animation finishes
                }, 3000); // After 3 seconds
            }

            searchInput.focus(); // Focus back on input
            return false; // Prevent form submission
        }

        return true; // Allow form submission if input is valid
    }
</script>
