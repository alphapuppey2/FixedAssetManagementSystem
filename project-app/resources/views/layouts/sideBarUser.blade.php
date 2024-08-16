<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Temp Sidebar UI for user, update/change -->
<!-- Sidebar -->
<nav class="bg-blue-300 fixed h-full w-64 lg:w-64 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out" id="sidebar">
    <!-- Profile -->
    <div class="flex flex-col items-center mb-6 mt-8">
        <img src="" alt="" class="w-20 h-20 rounded-full object-cover border-2 border-black-300 mb-2">
        <div class="text-center">
            <h2 class="text-gray-800 text-2xl font-semibold">Name</h2>
        </div>
    </div>

    <ul class="mt-16">
        <li class="p-3 rounded transition-colors duration-300 hover:bg-blue-500 cursor-pointer  flex items-center space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-gray-800 ml-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
            </svg>
            <a href="{{ route('scanQR') }}" class="text-gray-800 text-xl font-semibold hover:text-white">Scan QR</a>
        </li>
        <li class="p-3 rounded transition-colors duration-300 hover:bg-blue-500 cursor-pointer flex items-center space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-gray-800 ml-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            <a href="{{ route('requestList') }}" class="text-gray-800 text-xl font-semibold hover:text-white">Request List</a>
        </li>
        <li class="p-3 rounded transition-colors duration-300 hover:bg-blue-500 cursor-pointer  flex items-center space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-gray-800 ml-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            <a href="{{ route('notification') }}" class="text-gray-800 text-xl font-semibold hover:text-white">Notification</a>
        </li>
        <li class="p-3 rounded transition-colors duration-300 hover:bg-blue-500 cursor-pointer  flex items-center space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-gray-800 ml-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <a href="{{ route('profile') }}" class="text-gray-800 text-xl font-semibold hover:text-white">Profile</a>
        </li>
        <li class="p-3 rounded transition-colors duration-300 hover:bg-blue-500 cursor-pointer  flex items-center space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-gray-800 ml-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
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
