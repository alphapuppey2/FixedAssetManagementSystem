<aside class="h-screen transition ease-in max-md:w-[50px] md:w-[205px] overflow-hidden flex flex-col items-center p-2 fixed bg-blue-950 font-semibold text-white">
<!-- Sidebar -->
    <x-nav-link :href="route('user.profile')">
        <div class="profileAccount w-auto flex mt-3 items-center p-2 rounded-lg transition ease-in">
            <div class="imagepart overflow-hidden rounded-full lg:w-auto lg:h-auto transform relative p-4 border-3 border-slate-500">
                <img src="{{ Auth::user()->userPicture ? asset('uploads/profile_photos/' . Auth::user()->userPicture) : asset('images/default_profile.jpg') }}"
                         class="absolute bg-white top-1/2 left-1/2 lg:w-auto lg:h-auto transform -translate-x-1/2 -translate-y-1/2 object-cover"
                         alt="User Profile Photo">
             </div>
            <!-- Profile Section -->
            <div class="profileUser grid grid-col-2 ml-2 text-[12px] w-full max-md:hidden lg:block">
                <span class="font-normal">
                    {{ Auth::user()->lastname.','.Auth::user()->firstname }}
                </span>
                <br>
                <span>
                 Worker
                </span>
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
                <x-nav-link class="flex transition ease-in mb-1 p-1 rounded-md" :href="route('user.notification')" :active="request()->routeIs('user.notification')">
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
