<nav x-data="{ open: false }" class="topBar" style="z-index:1">
    <!-- Primary Navigation Menu -->
    <div class="displaylang">
        <div class="navlinks">
            <div class="nl1">
                <!-- Logo -->
                <div class="">
                    <a href="{{ route('dashboard') }}" class="logoName">
                        FAMAS
                    </a>
                </div>

                <!-- Navigation Links -->
                {{-- <h1 class="text-white">Famas</h1> --}}
            </div>
            <div class="right" style="display:flex">
                    <x-dropdown2 align="right" width="48">
                        <x-slot name="trigger">
                            <div class="div" style="margin-right:5px">New</div>
                            {{-- <button>
                                <div>{{  }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button> --}}
                        </x-slot>

                        <x-slot name="content">
                            <li>
                                <x-dropdown-link :href="route('newasset')">
                                    {{ __('Asset') }}
                                </x-dropdown-link>
                            </li>
                           <li>
                                <x-dropdown-link :href="route('formMaintenance')">
                                    {{ __('Maintenance') }}
                                </x-dropdown-link>
                           </li>
                            <li>
                                <x-dropdown-link :href="route('newasset')">
                                    {{ __('Asset') }}
                                </x-dropdown-link>
                            </li>
                        </x-slot>
                    </x-dropdown2>
            <!-- Settings Dropdown -->
            <div class="">
                <x-dropdown2 align="right" width="48">
                    <x-slot name="trigger" >

                            <div style="margin-right:5px">{{ Auth::user()->name }}</div>

                            {{-- <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div> --}}

                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown2></div>
            </div>

            <!-- Hamburger -->
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" style="display: none">
        <div class="pt-2 pb-3 space-y-1">
            @include('layouts.sideBar')
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">asd{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
