<!-- resources/views/layouts/sideBarUser.blade.php -->
@include('components.icons')

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Temp Sidebar UI for user, update/change -->
<!-- Sidebar -->
<nav class="bg-blue-300 fixed h-full w-64 lg:w-64 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out" id="sidebar">
    <!-- Profile -->
    <div class="flex flex-col items-center mb-6 mt-8">
        <img src="{{ auth()->user()->userPicture ? asset('storage/profile_photos/' . auth()->user()->userPicture) : asset('images/default_profile.jpg') }}" alt="Profile Image" class="w-20 h-20 rounded-full object-cover border-2 border-black-300 mb-2">
        <div class="text-center">
            <h2 class="text-gray-800 text-2xl font-semibold">{{ auth()->user()->firstname ?? 'Guest' }} 
                {{ auth()->user()->middlename ? auth()->user()->middlename . ' ' : '' }} 
                {{ auth()->user()->lastname ?? '' }}
            </h2>
        </div>
    </div>

    <ul class="mt-16">
        <li class="p-3 rounded transition-colors duration-300 hover:bg-blue-500 cursor-pointer  flex items-center space-x-4">
            @yield('scanQRIcon')
            <a href="{{ route('user.scanQR') }}" class="text-gray-800 text-xl font-semibold hover:text-white">Scan QR</a>
        </li>
        <li class="p-3 rounded transition-colors duration-300 hover:bg-blue-500 cursor-pointer flex items-center space-x-4">
            @yield('requestListIcon')
            <a href="{{ route('user.requestList') }}" class="text-gray-800 text-xl font-semibold hover:text-white">Request List</a>
        </li>
        <li class="p-3 rounded transition-colors duration-300 hover:bg-blue-500 cursor-pointer  flex items-center space-x-4">
            @yield('notificationIcon')
            <a href="{{ route('user.notification') }}" class="text-gray-800 text-xl font-semibold hover:text-white">Notification</a>
        </li>
        <li class="p-3 rounded transition-colors duration-300 hover:bg-blue-500 cursor-pointer  flex items-center space-x-4">
            @yield('profileIcon')
            <a href="{{ route('user.profile') }}" class="text-gray-800 text-xl font-semibold hover:text-white">Profile</a>
        </li>
        <li class="p-3 rounded transition-colors duration-300 hover:bg-blue-500 cursor-pointer  flex items-center space-x-4">
            @yield('logoutIcon')
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-800 text-xl font-semibold hover:text-white"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    Logout
                </button>
            </form>
        </li>
    </ul>
</nav>

<!-- Sidebar Responsive -->
<button class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-blue-500 text-white rounded focus:outline-none" id="sidebarToggle">
    ☰
</button>

<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });
</script>
