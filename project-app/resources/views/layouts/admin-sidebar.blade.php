<div class="navigationBar fixed h-screen w-[130px] max-w-[130px] flex flex-col justify-center items-center bg-blue-400" style="z-index:2">
        <div class="flex flex-col items-center mb-6 mt-8">
            <img src="" alt="" class="w-20 h-20 rounded-full object-cover border-2 border-black-300 mb-2">
            <div class="text-center">
                <h2 class="text-gray-800 text-2xl font-semibold">{{ Auth::user()->name }}</h2>
            </div>
        </div>
        <nav class="nb">

           <div>
             <div class="">
                <div>
                    <x-nav-link :href="route('admin.home')" :active="request()->routeIs('admin.home')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
                <div class="nav-item" >
                    <x-nav-link :href="route('userList')" :active="request()->routeIs('userList')">
                        {{ __('User List') }}
                    </x-nav-link>
                </div>

                <!-- <div class="nav-item">
                    <x-nav-link :href="route('maintenance')" :active="request()->routeIs('maintenance')">
                        {{ __('Maintenance') }}
                    </x-nav-link>
                </div>
                <div class="nav-item">
                    <x-nav-link :href="route('manufacturer')" :active="request()->routeIs('manufacturer')">
                        {{ __('Manufacturer') }}
                    </x-nav-link>
                </div>
                <div class="nav-item">
                    <x-nav-link :href="route('report')" :active="request()->routeIs('report')">
                        {{ __('Report') }}
                    </x-nav-link>
                </div>
                <div class="nav-item">
                    <x-nav-link :href="route('setting')" :active="request()->routeIs('setting')">
                        {{ __('Setting') }}
                    </x-nav-link>
                </div>
                <div class="nav-item">
                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                        {{ __('Profile') }}
                    </x-nav-link>
                </div> -->
             </div>
           </div>
    </nav>

    </div>
