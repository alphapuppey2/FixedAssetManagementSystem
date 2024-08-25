<aside class="h-screen transition ease-in md:w-[50px] lg:w-[205px] overflow-hidden flex flex-col items-center p-2 fixed bg-blue-950 font-semibold text-white">
    <button class="h-[10px] lg:hidden sm:block">
        <x-icons.hamburger />
    </button>
    <a href="#">
        <div class="profileAccount w-auto flex mt-3 items-center p-2 rounded-lg hover:bg-gray-300/15 transition ease-in">
            <div class="imagepart overflow-hidden rounded-full lg:w-auto lg:h-auto transform relative p-4 border-3 border-slate-500">
            <img src="{{ Auth::user()->userPicture ? asset('uploads/profile_photos/' . Auth::user()->userPicture) : asset('images/default_profile.jpg') }}"
                     class="absolute bg-white top-1/2 left-1/2 lg:w-auto lg:h-auto transform -translate-x-1/2 -translate-y-1/2 object-cover"
                     alt="User Profile Photo">
            </div>
            <div class="profileUser flex flex-col ml-2 text-[12px] sm:hidden lg:block">
                <span class="font-normal">
                    {{ Auth::user()->firstname. ' ' .Auth::user()->lastname }}
                </span>
                <br>
                <span>
                   @switch(Auth::user()->usertype)
                       @case('dept_head')
                           department Head
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
                    <x-dashIcon/>
                    <span class="ml-2 sm:hidden lg:block">Dashboard</span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md" :href="route('userList')" :active="request()->routeIs('userList')">
                    <x-receipticon />
                    <span class="ml-2 sm:hidden lg:block">User List</span>
                </x-nav-link>
            </li>

            <!--
            <li>
                <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md" :href="route('asset')" :active="request()->routeIs('asset')">
                    <x-paperplane class="bg-red-500"/>
                    <span class="ml-2 sm:hidden lg:block">Request</span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md" :href="route('maintenance')" :active="request()->routeIs('maintenance')">
                    <x-wrenchIcon />
                    <span class="ml-2 sm:hidden lg:block">Maintenance</span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md" :href="route('report')" :active="request()->routeIs('report')">
                    <x-chartIcon></x-chartIcon>
                    <span class="ml-2 sm:hidden lg:block">Report</span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link class="flex hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md" :href="route('setting')" :active="request()->routeIs('setting')">
                    <x-gearIcon />
                    <span class="ml-2 sm:hidden lg:block">Setting</span>
                </x-nav-link>
            </li>
            <li class="hover:bg-slate-400/15 transition ease-in mb-1 p-1 rounded-md">
                <a href="#" class="flex">
                    <x-bellIcon/>
                    <span class="ml-2 sm:hidden lg:block">Nofitifcation</span>
                </a>
            </li> -->

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

</script>