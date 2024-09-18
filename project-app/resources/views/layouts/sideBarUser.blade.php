@include('components.icons')

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<!-- Sidebar -->
<nav class="bg-blue-900 fixed h-full w-56 lg:w-56 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-lg" id="sidebar">
    <!-- Profile Section -->
    <div class="flex flex-col items-center mb-4 mt-6">
        <img src="{{ auth()->user()->userPicture ? asset('storage/profile_photos/' . auth()->user()->userPicture) : asset('images/default_profile.jpg') }}" alt="Profile Image" class="w-16 h-16 rounded-full object-cover border-2 border-white mb-2">
        <div class="text-center">
            <h2 class="text-white text-lg font-semibold">{{ auth()->user()->firstname ?? 'Guest' }}
                {{ auth()->user()->middlename ? auth()->user()->middlename . ' ' : '' }}
                {{ auth()->user()->lastname ?? '' }}
            </h2>
        </div>
    </div>

    <!-- Menu Items -->
    <ul class="mt-8">
        <li class="p-2 rounded transition-colors duration-300 {{ request()->routeIs('user.scanQR') ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' }} cursor-pointer flex items-center space-x-3">
            @yield('scanQRIcon')
            <a href="{{ route('user.scanQR') }}" class="text-white text-lg font-semibold hover:text-gray-200">Scan QR</a>
        </li>
        <li class="p-2 rounded transition-colors duration-300 {{ request()->routeIs('requests.list') ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' }} cursor-pointer flex items-center space-x-3">
            @yield('requestListIcon')
            <a href="{{ route('requests.list') }}" class="text-white text-lg font-semibold hover:text-gray-200">Request List</a>
        </li>
        <li class="p-2 rounded transition-colors duration-300 {{ request()->routeIs('user.notification') ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' }} cursor-pointer flex items-center space-x-3">
            @yield('notificationIcon')
            <a href="{{ route('user.notification') }}" class="text-white text-lg font-semibold hover:text-gray-200">Notification</a>
        </li>
        <li class="p-2 rounded transition-colors duration-300 {{ request()->routeIs('user.profile') ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' }} cursor-pointer flex items-center space-x-3">
            @yield('profileIcon')
            <a href="{{ route('user.profile') }}" class="text-white text-lg font-semibold hover:text-gray-200">Profile</a>
        </li>
        <li class="p-2 rounded transition-colors duration-300 hover:bg-blue-500 cursor-pointer flex items-center space-x-3">
            @yield('logoutIcon')
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-white text-lg font-semibold hover:text-gray-200"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    Logout
                </button>
            </form>
        </li>
    </ul>
</nav>

<!-- Sidebar Responsive Toggle Button -->
<button class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-blue-500 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 hover:bg-blue-600 transition duration-200" id="sidebarToggle">
    ☰
</button>

<!-- Script for Sidebar Toggle -->
<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    // Sidebar Toggle for Mobile View
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });
</script>
