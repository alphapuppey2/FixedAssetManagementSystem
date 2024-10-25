{{-- <aside id="adminSidebar" class="h-screen transition ease-in md:w-[50px] lg:w-[205px] overflow-hidden flex flex-col items-center p-2 fixed bg-blue-950 font-semibold text-white"> --}}
<aside id="adminSidebar" class="h-screen transition-all duration-300 ease-in-out max-md:w-[50px] md:w-[205px] overflow-hidden flex flex-col items-center p-2 fixed bg-blue-950 font-semibold text-white">
    <!-- Sidebar content -->
    {{-- <button id="hamburgerToggleAdmin" class="h-[10px] lg:hidden sm:block"> --}}
    <button id="hamburgerToggleAdmin" class="h-[30px] mb-4 max-md:block lg:hidden">
        <x-icons.hamburger />
    </button>

    <!-- Profile Section -->
    <x-nav-link :href="route('admin.profile')" class="mt-3 items-center justify-center">
        <div class="profileAccount flex items-center p-2 rounded-lg transition-all">
            <div class="imagepart overflow-hidden rounded-full w-[30px] h-[30px] md:w-[60px] md:h-[60px] border-2 border-slate-500">
                {{-- <img src="{{ Auth::user()->userPicture ? asset('storage/' . Auth::user()->userPicture) : asset('images/default_profile.jpg') }}" --}}
                <img src="{{ Auth::user()->userPicture ? asset('storage/profile_photos/' . Auth::user()->userPicture) : asset('images/default_profile.jpg') }}"
                    class="w-full h-full object-cover rounded-full" alt="User Profile Photo">
            </div>
            <div class="profileUser flex-col ml-2 text-[12px] hidden lg:flex">
                <span class="font-normal">{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</span>
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
    </x-nav-link>

    {{-- <div class="divder w-[80%] h-[1px] bg-white mt-2 mb-2"></div> --}}
    <div class="divider w-[80%] h-[1px] bg-white mt-2 mb-2"></div>

    {{-- <nav class="flex flex-col w-full font-semibold"> --}}
    <nav class="w-full">
        {{-- <ul class="sb h-[100%]"> --}}
        <ul class="flex flex-col w-full space-y-1">
            <li>
                <x-nav-link :href="route('admin.home')" :active="request()->routeIs('admin.home')"
                    class="flex items-center p-2 space-x-2 sidebar-icon rounded-md transition-all">
                    <x-icons.dash-icon class="w-8 h-8 md:w-6 md:h-6" />
                    <span class="hidden md:inline">Dashboard</span>
                </x-nav-link>
            </li>

            <li>
                <x-nav-link :href="route('userList')" :active="request()->routeIs('userList')"
                    class="flex items-center p-2 space-x-2 sidebar-icon rounded-md transition-all">
                    <x-icons.user-list-icon class="w-8 h-8 md:w-6 md:h-6" />
                    <span class="hidden md:inline">User List</span>
                </x-nav-link>
            </li>

            <!-- Assets Dropdown -->
            <li class="relative">
                <button id="adminAssetDropdownToggle"
                    class="flex items-center w-full text-left p-2 hover:bg-slate-400/15 rounded-md transition-all"
                    aria-expanded="false">
                    <x-icons.receipticon class="w-8 h-8 md:w-6 md:h-6" />
                    <span class="hidden md:inline">&nbsp;&nbsp;Assets</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor"
                        class="ml-auto w-5 h-5 transition-transform duration-200 toggle-icon"
                        id="assetIcon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <!-- Dropdown Menu with left margin for indentation -->
                <ul id="adminAssetDropdownMenu">
                    <!-- All Assets -->
                    <x-nav-link :href="route('assetList', ['dept' => null, 'asstDropdown' => 'open'])"
                        :active="request()->routeIs('assetList') && !request()->has('dept')"
                        class="flex items-center p-2 space-x-2 sidebar-icon rounded-md transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 md:w-6 md:h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        <span class="hidden sm:inline">All</span>
                    </x-nav-link>

                    <!-- IT Department -->
                    <x-nav-link :href="route('assetList', ['dept' => 1, 'asstDropdown' => 'open'])"
                        :active="request()->routeIs('assetList') && request()->dept == 1"
                        class="flex items-center p-2 space-x-2 sidebar-icon rounded-md transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 md:w-6 md:h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                        </svg>
                        <span class="hidden sm:inline">IT</span>
                    </x-nav-link>

                    <!-- Sales Department -->
                    <x-nav-link :href="route('assetList', ['dept' => 2, 'asstDropdown' => 'open'])"
                        :active="request()->routeIs('assetList') && request()->dept == 2"
                        class="flex items-center p-2 space-x-2 sidebar-icon rounded-md transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 md:w-6 md:h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />
                        </svg>
                        <span class="hidden sm:inline">Sales</span>
                    </x-nav-link>

                    <!-- Fleet Department -->
                    <x-nav-link :href="route('assetList', ['dept' => 3, 'asstDropdown' => 'open'])"
                        :active="request()->routeIs('assetList') && request()->dept == 3"
                        class="flex items-center p-2 space-x-2 sidebar-icon rounded-md transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 md:w-6 md:h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                        <span class="hidden sm:inline">Fleet</span>
                    </x-nav-link>

                    <!-- Production Department -->
                    <x-nav-link :href="route('assetList', ['dept' => 4, 'asstDropdown' => 'open'])"
                        :active="request()->routeIs('assetList') && request()->dept == 4"
                        class="flex items-center p-2 space-x-2 sidebar-icon rounded-md transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 md:w-6 md:h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077 1.41-.513m14.095-5.13 1.41-.513M5.106 17.785l1.15-.964m11.49-9.642 1.149-.964M7.501 19.795l.75-1.3m7.5-12.99.75-1.3m-6.063 16.658.26-1.477m2.605-14.772.26-1.477m0 17.726-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205 12 12m6.894 5.785-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
                        </svg>
                        <span class="hidden sm:inline">Production</span>
                    </x-nav-link>
                </ul>

            </li>

            <li class="relative">
                <button id="adminMaintenanceDropdownToggle"
                    class="flex items-center w-full text-left p-2 hover:bg-slate-400/15 rounded-md transition-all"
                    aria-expanded="false">
                    <x-icons.wrench-icon class="w-8 h-8 md:w-6 md:h-6" />
                    <span class="hidden md:inline">&nbsp;&nbsp;Maintenance</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor"
                        class="ml-auto w-5 h-5 transition-transform duration-200 toggle-icon"
                        id="maintenanceIcon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <ul id="adminMaintenanceDropdownMenu">

                    <x-nav-link :href="route('adminMaintenance', ['mntncDropdown' => 'open'])"
                        :active="request()->routeIs('adminMaintenance')"
                        class="flex items-center p-2 space-x-2 sidebar-icon rounded-md transition-all">
                        <x-icons.envelope-icon class="w-8 h-8 md:w-6 md:h-6"/>
                        <span class="hidden sm:inline">Request</span>
                    </x-nav-link>

                    <x-nav-link :href="route('adminMaintenance_sched', ['mntncDropdown' => 'open'])"
                        :active="request()->routeIs('adminMaintenance_sched')"
                        class="flex items-center p-2 space-x-2 sidebar-icon rounded-md transition-all">
                        <x-icons.calendar-icon class="w-8 h-8 md:w-6 md:h-6"/>
                        <span class="hidden sm:inline">Scheduling</span>
                    </x-nav-link>

                    <x-nav-link :href="route('adminMaintenance.records', ['mntncDropdown' => 'open'])"
                        :active="request()->routeIs('adminMaintenance.records')"
                        class="flex items-center p-2 space-x-2 sidebar-icon rounded-md transition-all">
                        <x-icons.records-icon class="w-8 h-8 md:w-6 md:h-6" />
                        <span class="hidden sm:inline">Records</span>
                    </x-nav-link>
                </ul>
            </li>

            <li>
                <x-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.index')"
                    class="flex items-center p-2 space-x-2 sidebar-icon rounded-md transition-all">
                    <x-icons.bell-icon class="w-8 h-8 md:w-6 md:h-6" />
                    <span class="hidden md:inline">Notification</span>
                </x-nav-link>
            </li>

            <li>
                <x-nav-link :href="route('admin.activity-logs')" :active="request()->routeIs('admin.activity-logs')"
                    class="flex items-center p-2 space-x-2 sidebar-icon rounded-md transition-all">
                    <x-icons.receipticon class="w-8 h-8 md:w-6 md:h-6" />
                    <span class="hidden md:inline">Activity Logs</span>
                </x-nav-link>
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full p-2 hover:bg-slate-400/15 rounded-md space-x-2 transition-all"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <x-icons.logout-icon class="w-8 h-8 md:w-6 md:h-6"/>
                        <span class="hidden md:inline">Log out</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>


<style>
    #adminAssetDropdownMenu, #adminMaintenanceDropdownMenu {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
    }

    .rotate-180 {
        transform: rotate(180deg);
    }

    /* Hide SVG arrow when sidebar is collapsed or on small screens */
    @media (max-width: 768px) {
        .toggle-icon {
            display: none;
        }
    }

    /* Hide the arrow if sidebar is collapsed */
    .collapsed .toggle-icon {
        display: none;
    }
</style>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        const adminAssetToggle = document.getElementById('adminAssetDropdownToggle');
        const adminAssetMenu = document.getElementById('adminAssetDropdownMenu');
        const adminMaintenanceToggle = document.getElementById('adminMaintenanceDropdownToggle');
        const adminMaintenanceMenu = document.getElementById('adminMaintenanceDropdownMenu');

        // Restore dropdown states on page load
        restoreDropdownState('assetDropdownOpen', adminAssetMenu, adminAssetToggle);
        restoreDropdownState('maintenanceDropdownOpen', adminMaintenanceMenu, adminMaintenanceToggle);

        // Toggle reports dropdown
        adminAssetToggle.addEventListener('click', (event) => {
            event.preventDefault();
            toggleDropdown(adminAssetMenu, adminAssetToggle, 'assetDropdownOpen');
        });

        // Toggle maintenance dropdown
        adminMaintenanceToggle.addEventListener('click', (event) => {
            event.preventDefault();
            toggleDropdown(adminMaintenanceMenu, adminMaintenanceToggle, 'maintenanceDropdownOpen');
        });

        // Detect clicks inside dropdowns and allow navigation
        document.addEventListener('click', (event) => {
            const target = event.target;

            if (!target.closest('#adminAssetDropdownMenu') && !target.closest('#adminAssetDropdownToggle')) {
                closeDropdown(adminAssetMenu, adminAssetToggle, 'assetDropdownOpen');
            }

            if (!target.closest('#adminMaintenanceDropdownMenu') && !target.closest('#adminMaintenanceDropdownToggle')) {
                closeDropdown(adminMaintenanceMenu, adminMaintenanceToggle, 'maintenanceDropdownOpen');
            }
        });

        // Prevent unnecessary propagation on dropdown items to allow them to function
        const dropdownLinks = document.querySelectorAll('#adminAssetDropdownMenu a, #adminMaintenanceDropdownMenu a');
        dropdownLinks.forEach((link) => {
            link.addEventListener('click', (event) => {
                const targetUrl = link.getAttribute('href');
                if (targetUrl) {
                    window.location.href = targetUrl; // Navigate to the target URL
                }
            });
        });

        // Toggle dropdown logic
        function toggleDropdown(menu, toggle, key) {
            const isOpen = toggle.getAttribute('aria-expanded') === 'true';
            if (isOpen) {
                closeDropdown(menu, toggle, key);
            } else {
                openDropdown(menu, toggle, key);
            }
        }

        // Open dropdown
        function openDropdown(menu, toggle, key) {
            menu.style.maxHeight = `${menu.scrollHeight}px`;
            menu.style.opacity = '1';
            toggle.setAttribute('aria-expanded', 'true');
            localStorage.setItem(key, 'true');
        }

        // Close dropdown
        function closeDropdown(menu, toggle, key) {
            menu.style.maxHeight = '0';
            menu.style.opacity = '0';
            toggle.setAttribute('aria-expanded', 'false');
            localStorage.setItem(key, 'false');
        }

        // Restore dropdown state from localStorage
        function restoreDropdownState(key, menu, toggle) {
            const isOpen = localStorage.getItem(key) === 'true';
            if (isOpen) {
                openDropdown(menu, toggle, key);
            }
        }
    });
</script>

{{-- <script>
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
        const adminMaintenanceToggle = document.getElementById('adminMaintenanceDropdownToggle');
        const adminMaintenanceMenu = document.getElementById('adminMaintenanceDropdownMenu');
        adminMaintenanceToggle.addEventListener('click', () => {
            toggleDropdown(adminMaintenanceToggle, adminMaintenanceMenu, 'adminMaintenanceDropdownOpen');
        });
        restoreDropdownState(adminMaintenanceToggle, adminMaintenanceMenu, 'adminMaintenanceDropdownOpen');
    });
</script> --}}
