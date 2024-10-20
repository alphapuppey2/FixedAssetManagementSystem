<aside id="sidebar" class="h-screen transition-all duration-300 ease-in-out max-md:w-[50px] md:w-[205px] overflow-hidden flex flex-col items-center p-2 fixed bg-blue-950 font-semibold text-white">
    <!-- Hamburger Button -->
    <button id="hamburgerToggle" class="h-[30px] mb-4 max-md:block lg:hidden">
        <x-icons.hamburger />
    </button>

    <!-- Profile Section -->
    <x-nav-link :href="route('profile')" class="mt-3 items-center justify-center">
        <div class="profileAccount flex items-center p-2 rounded-lg transition-all">
            <div class="imagepart overflow-hidden rounded-full w-[30px] h-[30px] md:w-[60px] md:h-[60px] border-2 border-slate-500">
                <img src="{{ Auth::user()->userPicture ? asset('storage/' . Auth::user()->userPicture) : asset('images/default_profile.jpg') }}"
                    class="w-full h-full object-cover rounded-full" alt="User Profile Photo">
            </div>
            <div class="profileUser flex-col ml-2 text-[12px] hidden lg:flex">
                <span class="font-normal">{{ Auth::user()->lastname }}, {{ Auth::user()->firstname }}</span>
                <span>
                    @switch(Auth::user()->usertype)
                    @case('dept_head') Department Head @break
                    @case(2) Admin @break
                    @endswitch
                </span>
            </div>
        </div>
    </x-nav-link>

    <div class="divider w-[80%] h-[1px] bg-white mt-2 mb-2"></div>

    <!-- Navigation Menu -->
    <nav class="w-full">
        <ul class="flex flex-col w-full space-y-1">
            <li>
                <x-nav-link :href="route('dept_head.home')" :active="request()->routeIs('dept_head.home')"
                    class="flex items-center p-2 space-x-2 sidebar-icon transition-all">
                    <x-dashIcon class="w-8 h-8 md:w-6 md:h-6" />
                    <span class="hidden md:inline">Dashboard</span>
                </x-nav-link>
            </li>

            <li>
                <x-nav-link :href="route('asset')" :active="request()->routeIs('asset')"
                    class="flex items-center p-2 space-x-2 sidebar-icon transition-all">
                    <x-receipticon class="w-8 h-8 md:w-6 md:h-6" />
                    <span class="hidden md:inline">Asset</span>
                </x-nav-link>
            </li>

            <li class="relative">
                <button id="maintenanceDropdownToggle"
                    class="flex items-center w-full text-left p-2 hover:bg-slate-400/15 rounded-md transition-all"
                    aria-expanded="false">
                    <x-wrench-icon class="w-8 h-8 md:w-6 md:h-6" />
                    <span class="hidden md:inline">&nbsp;&nbsp;Maintenance</span>
                    <i class="fas fa-chevron-down ml-auto"></i>
                </button>

                <ul id="maintenanceDropdownMenu"
                    class="hidden flex-col mt-1 space-y-1 pl-8"> <!-- Added padding-left -->
                    <x-nav-link :href="route('maintenance', ['dropdown' => 'open'])"
                        :active="request()->routeIs('maintenance')"
                        class="flex items-center p-2 space-x-2 transition-all">
                        <x-envelopeIcon class="w-6 h-6" />
                        <span class="hidden sm:inline">Request</span>
                    </x-nav-link>

                    <x-nav-link :href="route('maintenance_sched', ['dropdown' => 'open'])"
                        :active="request()->routeIs('maintenance_sched')"
                        class="flex items-center p-2 space-x-2 transition-all">
                        <x-calendarIcon class="w-6 h-6" />
                        <span class="hidden sm:inline">Scheduling</span>
                    </x-nav-link>

                    <x-nav-link :href="route('maintenance.records', ['status' => 'completed', 'dropdown' => 'open'])"
                        :active="request()->routeIs('maintenance.records')"
                        class="flex items-center p-2 space-x-2 transition-all">
                        <x-icons.records-icon class="w-6 h-6" />
                        <span class="hidden sm:inline">Records</span>
                    </x-nav-link>
                </ul>
            </li>

            <li class="relative">
                <button id="reportsDropdownToggle"
                    class="flex items-center w-full text-left p-2 hover:bg-slate-400/15 rounded-md transition-all"
                    aria-expanded="false">
                    <x-chartIcon class="w-8 h-8 md:w-6 md:h-6" />
                    <span class="hidden md:inline">&nbsp;&nbsp;Reports</span>
                    <i class="fas fa-chevron-down ml-auto"></i>
                </button>

                <ul id="reportsDropdownMenu"
                    class="hidden flex-col mt-1 space-y-1 pl-8"> <!-- Added padding-left -->
                    <x-nav-link :href="route('custom.report', ['dropdown' => 'open'])"
                        :active="request()->routeIs('custom.report')"
                        class="flex items-center p-2 space-x-2 transition-all hover:bg-gray-100">
                        <x-envelopeIcon class="w-6 h-6" />
                        <span class="hidden sm:inline">Assets</span>
                    </x-nav-link>

                    <a href="#"
                        class="flex items-center p-2 space-x-2 transition-all hover:bg-gray-100">
                        <x-calendarIcon class="w-6 h-6" />
                        <span class="hidden sm:inline">Maintenance</span>
                    </a>
                </ul>
            </li>


            <li>
                <x-nav-link :href="route('setting')" :active="request()->routeIs('setting')"
                    class="flex items-center p-2 space-x-2 sidebar-icon transition-all">
                    <x-gearIcon class="w-8 h-8 md:w-6 md:h-6" />
                    <span class="hidden md:inline">Settings</span>
                </x-nav-link>
            </li>

            <li>
                <x-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.index')"
                    class="flex items-center p-2 space-x-2 sidebar-icon transition-all">
                    <x-bellIcon class="w-8 h-8 md:w-6 md:h-6" />
                    <span class="hidden md:inline">Notifications</span>
                </x-nav-link>
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full p-2 hover:bg-slate-400/15 rounded-md space-x-2 transition-all">
                        <x-icons.logout-icon class="w-8 h-8 md:w-6 md:h-6" />
                        <span class="hidden md:inline">Log out</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>


<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleDropdown = (toggleButton, dropdownMenu, storageKey) => {
            const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
            toggleButton.setAttribute('aria-expanded', !isExpanded);
            dropdownMenu.classList.toggle('hidden', isExpanded);

            // Save the state in localStorage
            localStorage.setItem(storageKey, !isExpanded);
        };

        const restoreDropdownState = (toggleButton, dropdownMenu, storageKey) => {
            const savedState = localStorage.getItem(storageKey) === 'true';
            toggleButton.setAttribute('aria-expanded', savedState);
            dropdownMenu.classList.toggle('hidden', !savedState);
        };

        // Sidebar toggle logic
        document.getElementById('hamburgerToggle').addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('w-[50px]');
            document.getElementById('sidebar').classList.toggle('md:w-[205px]');
        });

        // Maintenance dropdown logic
        const maintenanceToggle = document.getElementById('maintenanceDropdownToggle');
        const maintenanceMenu = document.getElementById('maintenanceDropdownMenu');
        maintenanceToggle.addEventListener('click', () => {
            toggleDropdown(maintenanceToggle, maintenanceMenu, 'maintenanceDropdownOpen');
        });

        // Reports dropdown logic
        const reportsToggle = document.getElementById('reportsDropdownToggle');
        const reportsMenu = document.getElementById('reportsDropdownMenu');
        reportsToggle.addEventListener('click', () => {
            toggleDropdown(reportsToggle, reportsMenu, 'reportsDropdownOpen');
        });

        // Restore the state on page load
        restoreDropdownState(maintenanceToggle, maintenanceMenu, 'maintenanceDropdownOpen');
        restoreDropdownState(reportsToggle, reportsMenu, 'reportsDropdownOpen');
    });
</script>