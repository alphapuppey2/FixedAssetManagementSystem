<aside class="h-screen transition ease-in md:w-[50px] lg:w-[205px] overflow-hidden flex flex-col items-center p-2 fixed bg-blue-950 font-semibold text-white">
    <!-- Sidebar content -->
    <button class="h-[10px] lg:hidden sm:block">
        <x-icons.hamburger />
    </button>
    <a href="{{ route('admin.profile') }}">
        <div class="profileAccount w-auto flex mt-3 items-center p-2 rounded-lg hover:bg-gray-300/15 transition ease-in">
            <div class="imagepart overflow-hidden rounded-full lg:w-auto lg:h-auto transform relative p-4 border-3 border-slate-500">
                <img src="{{ Auth::user()->userPicture ? asset('uploads/profile_photos/' . Auth::user()->userPicture) : asset('images/default_profile.jpg') }}"
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
            <li class="">
                <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md" :href="route('admin.home')" :active="request()->routeIs('admin.home')">
                    <x-dashIcon />
                    <span class="ml-2 sm:hidden lg:block">Dashboard</span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md" :href="route('userList')" :active="request()->routeIs('userList')">
                    <x-receipticon />
                    <span class="ml-2 sm:hidden lg:block">User List</span>
                </x-nav-link>
            </li>

            <!-- Assets Dropdown -->
            <li class="relative">
                <button id="assetDropdown" class="flex items-center w-full text-left hover:bg-slate-400/15 p-1 rounded-md focus:outline-none">
                    <x-receipticon />
                    <span class="ml-2">Assets</span>
                    <i class="fas fa-chevron-down ml-auto"></i>
                </button>

                <!-- Dropdown Menu with left margin for indentation -->
                <ul id="dropdownMenu" class="hidden flex-col rounded-md mt-1 ml-4"> <!-- Added ml-4 for indentation -->
                    <!-- All Assets -->
                    <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        :href="route('assetList', ['dropdown' => 'open'])"
                        :active="request()->routeIs('assetList')">
                        <span class="ml-2 sm:hidden lg:block">All</span>
                    </x-nav-link>

                    <!-- IT Department Link -->
                    <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        :href="route('assetListByDept', ['dept' => 1, 'dropdown' => 'open'])"
                        :active="request()->routeIs('assetListByDept') && request()->dept == 1">
                        <span class="ml-2 sm:hidden lg:block">IT</span>
                    </x-nav-link>

                    <!-- Sales Department Link -->
                    <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        :href="route('assetListByDept', ['dept' => 2, 'dropdown' => 'open'])"
                        :active="request()->routeIs('assetListByDept') && request()->dept == 2">
                        <span class="ml-2 sm:hidden lg:block">Sales</span>
                    </x-nav-link>

                    <!-- Fleet Department Link -->
                    <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        :href="route('assetListByDept', ['dept' => 3, 'dropdown' => 'open'])"
                        :active="request()->routeIs('assetListByDept') && request()->dept == 3">
                        <span class="ml-2 sm:hidden lg:block">Fleet</span>
                    </x-nav-link>

                    <!-- Production Department Link -->
                    <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        :href="route('assetListByDept', ['dept' => 4, 'dropdown' => 'open'])"
                        :active="request()->routeIs('assetListByDept') && request()->dept == 4">
                        <span class="ml-2 sm:hidden lg:block">Production</span>
                    </x-nav-link>
                </ul>
            </li>


            <!-- Log out button moves down dynamically -->
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <x-icons.logoutIcon />
                        <span class="ml-2 sm:hidden lg:block">Log out</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>

<script>
    // JavaScript to toggle the dropdown
    document.getElementById('assetDropdown').addEventListener('click', function() {
        var dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.classList.toggle('hidden'); // Toggle the hidden class to show/hide the dropdown
    });

    // Keep the dropdown open if the URL contains 'dropdown=open'
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('dropdown') === 'open') {
            document.getElementById('dropdownMenu').classList.remove('hidden'); // Keep dropdown open
        }
    };
</script>