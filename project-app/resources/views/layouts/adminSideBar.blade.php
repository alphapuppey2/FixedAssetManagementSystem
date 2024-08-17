
<div class="navigationBar fixed h-screen w-[130px] max-w-[130px] flex flex-col justify-center items-center bg-blue-400" style="z-index:2">
        <div class="headContent w-[80px] text-center text-wrap">
            <div class="img bg-blue-800">
                IMG
            </div>
            <h1>{{ Auth::user()->name }}</h1>
            admin
        </div>
        <nav class="nb ">

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
                <div class="nav-item">
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
                </div>
             </div>
           </div>
    </nav>

    </div>
