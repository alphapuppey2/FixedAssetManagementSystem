<aside class="h-screen transition ease-in max-md:w-[50px] md:w-[205px] overflow-hidden flex flex-col items-center p-2 fixed bg-blue-950 font-semibold text-white">
    <button class="h-[10px] lg:hidden max-md:block">
        <x-icons.hamburger />
    </button>
    <x-nav-link :href="route('profile')">
        <div class="profileAccount w-auto flex mt-3 items-center p-2 rounded-lg transition ease-in">
            <div class="imagepart overflow-hidden rounded-full lg:w-auto lg:h-auto transform relative p-4 border-3 border-slate-500">
                <img src="{{ Auth::user()->userPicture ? asset('uploads/profile_photos/' . Auth::user()->userPicture) : asset('images/default_profile.jpg') }}"
                    class="absolute bg-white top-1/2 left-1/2 lg:w-auto lg:h-auto transform -translate-x-1/2 -translate-y-1/2 object-cover"
                    alt="User Profile Photo">
            </div>
            <div class="profileUser flex flex-col ml-2 text-[12px] max-md:hidden lg:block">
                <span class="font-normal">
                    {{ Auth::user()->lastname.','.Auth::user()->firstname }}
                </span>
                <span>
                    @switch(Auth::user()->usertype)
                    @case('dept_head')
                    department Head
                    @break
                    @case(2)
                    admin
                    @break
                    @default
                    @endswitch
                </span>
            </div>
        </div>
    </x-nav-link>
    <div class="divder w-[80%] h-[1px] bg-white mt-2 mb-2"></div>
    <nav class="flex flex-col w-full font-semibold">
        <ul class="sb h-[100%]">
            <li>
                <x-nav-link class="flex transition ease-in mb-1 p-1 rounded-md" :href="route('dept_head.home')" :active="request()->routeIs('dept_head.home')">
                    <x-dashIcon />
                    <span class="ml-2 max-md:hidden lg:block">Dashboard</span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link class="flex transition ease-in mb-1 p-1 rounded-md" :href="route('asset')" :active="request()->routeIs('asset') ">
                    <x-receipticon />
                    <span class="ml-2 max-md:hidden lg:block">Asset</span>
                </x-nav-link>
            </li>
            <li class="relative">
                <button id="maintenanceDropdown" class="flex items-center w-full text-left hover:bg-slate-400/15 p-1 rounded-md focus:outline-none">
                    <x-wrenchicon />
                    <span class="ml-2">Maintenance</span>
                    <i class="fas fa-chevron-down ml-auto"></i> <!-- Dropdown icon -->
                </button>
                <!-- Dropdown Menu with left margin for indentation -->
                <ul id="maintenanceDropdownMenu" class="hidden flex-col rounded-md mt-1 ml-4"> <!-- Added ml-4 for indentation -->
                    <!-- Maintenance Request -->
                    <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        :href="route('maintenance', ['dropdown' => 'open'])"
                        :active="request()->routeIs('maintenance') || request()->routeIs('maintenance.approved') || request()->routeIs('maintenance.denied')">
                        <x-envelopeIcon />
                        <span class="ml-2 sm:hidden lg:block">Request</span>
                    </x-nav-link>

                    <!-- Maintenance Scheduling -->
                    <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        :href="route('maintenance_sched', ['dropdown' => 'open'])"
                        :active="request()->routeIs('maintenance_sched') || request()->routeIs('maintenance_sched.predictive')">
                        <x-calendarIcon />
                        <span class="ml-2 sm:hidden lg:block">Scheduling</span>
                    </x-nav-link>
                </ul>
            </li>
            <li>
                <x-nav-link class="flex transition ease-in mb-1 p-1 rounded-md" :href="route('report')" :active="request()->routeIs('report')">
                    <x-chartIcon></x-chartIcon>
                    <span class="ml-2 max-md:hidden lg:block">Report</span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link class="flex transition ease-in mb-1 p-1 rounded-md" :href="route('setting')" :active="request()->routeIs('setting')">
                    <x-gearIcon />
                    <span class="ml-2 max-md:hidden lg:block">Setting</span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="#" class="flex transition ease-in mb-1 p-1 rounded-md">
                    <x-bellIcon />
                    <span class="ml-2 max-md:hidden lg:block">Notification</span>
                </x-nav-link>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full transition ease-in mb-1 p-1 rounded-md"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <x-icons.logoutIcon />
                        <span class="ml-2 max-md:hidden lg:block">Log out</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const maintenanceDropdownButton = document.getElementById('maintenanceDropdown');
        const maintenanceDropdownMenu = document.getElementById('maintenanceDropdownMenu');

        // Toggle dropdown menu on button click
        maintenanceDropdownButton.addEventListener('click', function() {
            maintenanceDropdownMenu.classList.toggle('hidden'); // Show or hide the dropdown
        });

        // Keep the dropdown open if the URL contains 'dropdown=open'
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('dropdown') === 'open') {
                maintenanceDropdownMenu.classList.remove('hidden'); // Keep dropdown open
            }
        };
    });
</script>