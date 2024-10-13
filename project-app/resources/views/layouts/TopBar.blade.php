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
@endphp

<div
    class="flex bg-white items-center shadow-md shadow-slate-400/50 justify-between p-2 fixed h-[40px] md:left-[205px] z-1 md:w-[calc(100%_-_205px)] max-md:left-[50px] max-md:w-[calc(100%_-_50px)]">
    <div class="logo">
        <span>
            <a href="{{ $homeRoute }}" class="logoName">
                FAMAS
            </a>
        </span>
    </div>

    @if(Auth::user()->usertype !== 'user')
    <nav class="flex items-center space-x-4 relative">
        <!-- Notification Icon with Click Event -->
        <div x-data="{ open: false }" class="relative">
            <svg @click="open = !open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.5" stroke="currentColor"
                 class="w-6 h-6 cursor-pointer transition transform hover:scale-110 hover:text-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>

            <!-- Notification Dropdown -->
            <div x-show="open" @click.away="open = false"
                 class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-md shadow-lg overflow-hidden z-50">

                <!-- Actions: View All & Clear -->
                <div class="border-b border-gray-200 flex justify-between items-center px-3 py-2 bg-gray-50">
                    <a href="{{route('notification')}}" class="text-blue-500 hover:underline text-sm">
                        View All
                    </a>
                    <button
                        @click="clearNotifications()"
                        class="text-red-500 hover:underline text-sm">
                        Clear All
                    </button>
                </div>

                <!-- Notification List -->
                <ul class="divide-y divide-gray-200 max-h-56 overflow-y-auto">
                    @if(Auth::user()->usertype === 'admin')
                        <li class="p-3 hover:bg-gray-100 cursor-pointer">New user registered.</li>
                        <li class="p-3 hover:bg-gray-100 cursor-pointer">System maintenance scheduled.</li>
                    @elseif(Auth::user()->usertype === 'dept_head')
                        <li class="p-3 hover:bg-gray-100 cursor-pointer">New asset assigned to your department.</li>
                        <li class="p-3 hover:bg-gray-100 cursor-pointer">Maintenance request approved.</li>
                    @elseif(Auth::user()->usertype === 'user')
                        <li class="p-3 hover:bg-gray-100 cursor-pointer">Your repair request is under review.</li>
                        <li class="p-3 hover:bg-gray-100 cursor-pointer">Preventive maintenance scheduled for your equipment.</li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Create New Dropdown -->
        <x-dropdown2>
            <x-slot name="trigger">
                <div class="div">Create New</div>
            </x-slot>

            <x-slot name="content">
                @if (Auth::user()->usertype === 'admin')
                <li>
                    <x-dropdown-link class="w-full pl-3 block hover:bg-blue-100" :href="route('users.create')">
                        {{ __('Users') }}
                    </x-dropdown-link>
                </li>
                @endif
                @if(Auth::user()->usertype === 'dept_head')
                <li>
                    <x-dropdown-link class="w-full pl-3 block hover:bg-blue-100" :href="route('newasset')">
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
</div>

<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
    function clearNotifications() {
        // Implement AJAX to mark all notifications as read (example)
        fetch('/notifications/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) {
                alert('Notifications cleared!');
                location.reload(); // Reload to reflect changes
            }
        }).catch(error => {
            console.error('Error clearing notifications:', error);
        });
    }
</script>
