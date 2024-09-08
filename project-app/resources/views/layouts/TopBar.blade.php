{{--
    ADMIN TOP BAR
--}}
@if (Auth::user()->usertype === 'admin')
    <div class="flex bg-white items-center shadow-md shadow-slate-400/50 justify-between p-2 fixed h-[40px] lg:left-[205px] lg:w-[calc(100%_-_205px)] md:left-[50px] md:w-[calc(100%_-_50px)]">
        <div class="logo">
            <span>
                <a href="{{ route('admin.home') }}" class="logoName">
                    FAMAS
                </a>
            </span>
        </div>
        <nav>
            <x-dropdown2 width="48">
                <x-slot name="trigger">
                    <div class="div">New</div>
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
                        {{-- TO CHANGE ROUTE --}}
                        <x-dropdown-link :href="route('newasset')">
                            {{ __('User') }}
                        </x-dropdown-link>
                    </li>
                </x-slot>
            </x-dropdown2>
        </nav>
    </div>
{{--
    DEPARTMENT HEAD TOP BAR
--}}
@elseif (Auth::user()->usertype === 'dept_head')
    <div class="flex bg-white items-center shadow-md shadow-slate-400/50 justify-between p-2 fixed h-[40px] md:left-[205px] z-1 md:w-[calc(100%_-_205px)] max-md:left-[50px] max-md:w-[calc(100%_-_50px)]">
        <div class="logo">
            <span>
                <a href="{{ route('dept_head.home') }}" class="logoName">
                    FAMAS
                </a>
            </span>
        </div>
        <nav>
            <x-dropdown2 >
                <x-slot name="trigger">
                    <div class="div">Create </div>
                </x-slot>

                <x-slot name="content">
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
                </x-slot>
            </x-dropdown2>
        </nav>
    </div>
{{--
    USER TOP BAR
--}}
@else
    <div class="flex bg-white items-center shadow-md shadow-slate-400/50 justify-between p-2 fixed h-[40px] lg:left-[205px] lg:w-[calc(100%_-_205px)] md:left-[50px] md:w-[calc(100%_-_50px)]">
        <div class="logo">
            <span>
                <a href="{{ route('dept_head.home') }}" class="logoName">
                    FAMAS
                </a>
            </span>
        </div>
        <nav>
            <x-dropdown2 width="48">
                <x-slot name="trigger">
                    <div class="div" style="margin-right:5px">New</div>
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
        </nav>
    </div>

@endif
