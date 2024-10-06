@php
    $routes = [route('user.home'), route('dept_head.home'), route('admin.home')];
    $homeRoute = null;
    switch (Auth::user()->usertype) {
        case 'user':
            $homeRoute = $routes[0];
            break;
        case 'dept_head':
            $homeRoute = $routes[1];
            break;
        case 'admin':
            $homeRoute = $routes[2];
            break;
    }
@endphp

<div
    class="flex bg-white items-center shadow-md shadow-slate-400/50 justify-between p-2 fixed h-[40px] md:left-[205px] z-1 md:w-[calc(100%_-_205px)] max-md:left-[50px] max-md:w-[calc(100%_-_50px)]">
    <div class="logo">
        <span>
            <a href="{{ $homeRoute }}" class="logoName">
                FAMAS
            </a>
        </span>
    </div>

    @if(Auth::user()->usertype !== 'user')
    <nav>
        <x-dropdown2>
            <x-slot name="trigger">
                <div class="div">Create New</div>
            </x-slot>

            <x-slot name="content">
                @if (Auth::user()->usertype === 'admin')
                <li>
                    <x-dropdown-link class="w-full pl-3 block hover:bg-blue-100" :href="route('users.create')">
                        {{ __('Users') }}
                    </x-dropdown-link>
                </li>
                @endif
                @if(Auth::user()->usertype === 'dept_head')
                <li>
                    <x-dropdown-link class="w-full pl-3 block hover:bg-blue-100" :href="route('newasset')">
                        {{ __('Asset') }}
                    </x-dropdown-link>
                </li>
                <li>
                    <x-dropdown-link class="w-full pl-3 block hover:bg-blue-100" :href="route('formMaintenance')">
                        {{ __('Maintenance') }}
                    </x-dropdown-link>
                </li>
                <li>
                    <x-dropdown-link class="w-full pl-3 block hover:bg-blue-100" :href="route('newasset')">
                        {{ __('Report') }}
                    </x-dropdown-link>
                </li>
                @endif
            </x-slot>
        </x-dropdown2>
    </nav>
    @endif
</div>
