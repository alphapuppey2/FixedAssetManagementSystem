<aside id="adminSidebar" class="h-screen transition ease-in md:w-[50px] lg:w-[205px] overflow-hidden flex flex-col items-center p-2 fixed bg-blue-950 font-semibold text-white">
    <!-- Sidebar content -->
    <button id="hamburgerToggleAdmin" class="h-[10px] lg:hidden sm:block">
        <x-icons.hamburger />
    </button>

    <a href="{{ route('admin.profile') }}">
        <div class="profileAccount w-auto flex mt-3 items-center p-2 rounded-lg hover:bg-gray-300/15 transition ease-in">
            <div class="imagepart overflow-hidden rounded-full lg:w-auto lg:h-auto transform relative p-4 border-3 border-slate-500">
                <img src="{{ Auth::user()->userPicture ? asset('storage/' . Auth::user()->userPicture) : asset('images/default_profile.jpg') }}"
                    class="absolute bg-white top-1/2 left-1/2 lg:w-auto lg:h-auto transform -translate-x-1/2 -translate-y-1/2 object-cover"
                    alt="User Profile Photo">
            </div>
            <div class="profileUser flex flex-col ml-2 text-[12px] sm:hidden lg:block">
                <span class="font-normal">
                    {{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}
                </span>
                <br>
                <span>
                    @switch(Auth::user()->usertype)
                    @case('dept_head')
                    Department Head
                    @break
                    @case('admin')
                    Admin
                    @break
                    @default
                    @endswitch
                </span>
            </div>
        </div>
    </a>

    <div class="divder w-[80%] h-[1px] bg-white mt-2 mb-2"></div>

    <nav class="flex flex-col w-full font-semibold">
        <ul class="sb h-[100%]">
            <li>
                <x-nav-link class="flex hover:bg-slate-400/15 mb-1 p-1 rounded-md"
                    :href="route('admin.home')" :active="request()->routeIs('admin.home')">
                    <x-dashIcon />
                    <span class="ml-2 sm:hidden lg:block">Dashboard</span>
                </x-nav-link>
            </li>

            <li>
                <x-nav-link class="flex hover:bg-slate-400/15 mb-1 p-1 rounded-md"
                    :href="route('userList')" :active="request()->routeIs('userList')">
                    <x-userListIcon />
                    <span class="ml-2 sm:hidden lg:block">User List</span>
                </x-nav-link>
            </li>

            <!-- Assets Dropdown -->
            <li class="relative">
                <button id="adminAssetDropdownToggle" class="flex items-center w-full text-left hover:bg-slate-400/15 p-1 rounded-md focus:outline-none">
                    <x-receipticon />
                    <span class="ml-2">Assets</span>
                    <i class="fas fa-chevron-down ml-auto"></i>
                </button>

                <!-- Dropdown Menu with left margin for indentation -->
                <ul id="dropdownMenu" class="hidden flex-col rounded-md mt-1 ml-4">
                    <!-- All Assets -->
                    <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        :href="route('assetList', ['dept' => null, 'asstDropdown' => 'open'])"
                        :active="request()->routeIs('assetList') && !request()->has('dept')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        <span class="ml-2 sm:hidden lg:block">All</span>
                    </x-nav-link>

                    <!-- IT Department -->
                    <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        :href="route('assetList', ['dept' => 1, 'asstDropdown' => 'open'])"
                        :active="request()->routeIs('assetList') && request()->dept == 1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                        </svg>
                        <span class="ml-2 sm:hidden lg:block">IT</span>
                    </x-nav-link>

                    <!-- Sales Department -->
                    <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        :href="route('assetList', ['dept' => 2, 'asstDropdown' => 'open'])"
                        :active="request()->routeIs('assetList') && request()->dept == 2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />
                        </svg>
                        <span class="ml-2 sm:hidden lg:block">Sales</span>
                    </x-nav-link>

                    <!-- Fleet Department -->
                    <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        :href="route('assetList', ['dept' => 3, 'asstDropdown' => 'open'])"
                        :active="request()->routeIs('assetList') && request()->dept == 3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                        <span class="ml-2 sm:hidden lg:block">Fleet</span>
                    </x-nav-link>

                    <!-- Production Department -->
                    <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        :href="route('assetList', ['dept' => 4, 'asstDropdown' => 'open'])"
                        :active="request()->routeIs('assetList') && request()->dept == 4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077 1.41-.513m14.095-5.13 1.41-.513M5.106 17.785l1.15-.964m11.49-9.642 1.149-.964M7.501 19.795l.75-1.3m7.5-12.99.75-1.3m-6.063 16.658.26-1.477m2.605-14.772.26-1.477m0 17.726-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205 12 12m6.894 5.785-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
                        </svg>
                        <span class="ml-2 sm:hidden lg:block">Production</span>
                    </x-nav-link>
                </ul>

            </li>

            <li class="relative">
                <button id="adminMaintenanceDropdownToggle" class="flex items-center w-full text-left hover:bg-slate-400/15 p-1 rounded-md focus:outline-none">
                    <x-wrenchicon />
                    <span class="ml-2">Maintenance</span>
                    <i class="fas fa-chevron-down ml-auto"></i>
                </button>

                <ul id="adminMaintenanceDropdownMenu" class="hidden flex-col mt-1 ml-4">
                    <x-nav-link class="flex hover:bg-slate-400/15 mb-1 p-1 rounded-md"
                        :href="route('adminMaintenance', ['mntncDropdown' => 'open'])"
                        :active="request()->routeIs('adminMaintenance')">
                        <x-envelopeicon />
                        <span class="ml-2 sm:hidden lg:block">Request</span>
                    </x-nav-link>

                    <x-nav-link class="flex hover:bg-slate-400/15 mb-1 p-1 rounded-md"
                        :href="route('adminMaintenance_sched', ['mntncDropdown' => 'open'])"
                        :active="request()->routeIs('adminMaintenance_sched')">
                        <x-calendarIcon />
                        <span class="ml-2 sm:hidden lg:block">Scheduling</span>
                    </x-nav-link>
                </ul>
            </li>

            <li>
                <x-nav-link class="flex hover:bg-slate-400/15 mb-1 p-1 rounded-md"
                    :href="route('notifications.index')" :active="request()->routeIs('notifications.index')">
                    <x-bellIcon />
                    <span class="ml-2 sm:hidden lg:block">Notification</span>
                </x-nav-link>
            </li>

            <li>
                <x-nav-link class="flex hover:bg-slate-400/15 mb-1 p-1 rounded-md"
                    :href="route('admin.activity-logs')" :active="request()->routeIs('admin.activity-logs')">
                    <x-receipticon />
                    <span class="ml-2 sm:hidden lg:block">Activity Logs</span>
                </x-nav-link>
            </li>

            <li class="mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full hover:bg-slate-400/15 mb-1 p-1 rounded-md"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <x-icons.logout-icon />
                        <span class="ml-2 sm:hidden lg:block">Log out</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleDropdown = (toggleButton, dropdownMenu, storageKey) => {
            const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
            toggleButton.setAttribute('aria-expanded', !isExpanded);
            dropdownMenu.classList.toggle('hidden', isExpanded);
            localStorage.setItem(storageKey, !isExpanded);
        };

        const restoreDropdownState = (toggleButton, dropdownMenu, storageKey) => {
            const savedState = localStorage.getItem(storageKey) === 'true';
            toggleButton.setAttribute('aria-expanded', savedState);
            dropdownMenu.classList.toggle('hidden', !savedState);
        };

        const sidebar = document.getElementById('adminSidebar');
        const hamburgerToggle = document.getElementById('hamburgerToggleAdmin');

        // Restore sidebar state
        if (localStorage.getItem('adminSidebarExpanded') === 'true') {
            sidebar.classList.add('lg:w-[205px]');
        } else {
            sidebar.classList.add('md:w-[50px]');
        }

        hamburgerToggle.addEventListener('click', () => {
            const isExpanded = sidebar.classList.contains('lg:w-[205px]');
            sidebar.classList.toggle('md:w-[50px]', isExpanded);
            sidebar.classList.toggle('lg:w-[205px]', !isExpanded);
            localStorage.setItem('adminSidebarExpanded', !isExpanded);
        });

        // Asset dropdown
        const assetToggle = document.getElementById('adminAssetDropdownToggle');
        const assetMenu = document.getElementById('adminAssetDropdownMenu');
        assetToggle.addEventListener('click', () => {
            toggleDropdown(assetToggle, assetMenu, 'adminAssetDropdownOpen');
        });
        restoreDropdownState(assetToggle, assetMenu, 'adminAssetDropdownOpen');

        // Maintenance dropdown
        const maintenanceToggle = document.getElementById('adminMaintenanceDropdownToggle');
        const maintenanceMenu = document.getElementById('adminMaintenanceDropdownMenu');
        maintenanceToggle.addEventListener('click', () => {
            toggleDropdown(maintenanceToggle, maintenanceMenu, 'adminMaintenanceDropdownOpen');
        });
        restoreDropdownState(maintenanceToggle, maintenanceMenu, 'adminMaintenanceDropdownOpen');
    });
</script>
