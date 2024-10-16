<aside class="h-screen transition ease-in max-md:w-[50px] md:w-[205px] overflow-hidden flex flex-col items-center p-2 fixed bg-blue-950 font-semibold text-white">
<!-- Sidebar -->
<!-- User Profile Section -->
<x-nav-link :href="route('user.profile')" class="mt-3 flex items-center justify-center md:justify-start">
    <div class="profileAccount flex items-center p-2 transition-all duration-300 ease-in-out">
        <!-- Image changes size smoothly based on sidebar state -->
        <div class="imagepart overflow-hidden rounded-full relative border-2 border-slate-500 
                    transition-all duration-300 ease-in-out w-[45px] h-[45px] md:w-[60px] md:h-[60px]">
            <img src="{{ Auth::user()->userPicture ? asset('uploads/profile_photos/' . Auth::user()->userPicture) : asset('images/default_profile.jpg') }}" 
                 class="absolute inset-0 object-cover w-full h-full rounded-full" 
                 alt="User Profile Photo">
        </div>
        <!-- Sidebar Text - Hidden in compressed state -->
        <div class="ml-2 sidebar-text hidden md:block transition-all duration-300 ease-in-out">
            <span>{{ Auth::user()->lastname }}, {{ Auth::user()->firstname }}</span><br>
            <span>Worker</span>
        </div>
    </div>
</x-nav-link>




    <div class="divder w-[80%] h-[1px] bg-white mt-2 mb-2"></div>
    <nav class="flex flex-col w-full font-semibold">
        <!-- Menu Items -->
        <ul class="sb h-[100%]">
            <li>
                <x-nav-link class="flex transition ease-in mb-1 p-1 rounded-md" :href="route('user.scanQR')" :active="request()->routeIs('user.scanQR')">
                    <x-icons.scanQR-icon/>
                    <span class="ml-2 max-md:hidden lg:block">Scan QR</span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link class="flex transition ease-in mb-1 p-1 rounded-md" :href="route('requests.list')" :active="request()->routeIs('requests.list')">
                    <x-icons.requestList-icon/>
                    <span class="ml-2 max-md:hidden lg:block">Request List</span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link class="flex transition ease-in mb-1 p-1 rounded-md" :href="route('notifications.index')" :active="request()->routeIs('notifications.index')">
                    <x-icons.notification-icon/>
                    <span class="ml-2 max-md:hidden lg:block">Notification</span>
                </x-nav-link>
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full transition ease-in mb-1 p-1 rounded-md"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            <x-icons.logout-icon />
                            <span class="ml-2 max-md:hidden lg:block">Log out</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>

<!-- Script for Sidebar Toggle -->
<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    // Sidebar Toggle for Mobile View
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });
</script>
