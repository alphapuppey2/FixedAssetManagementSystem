<div class="flex bg-white items-center shadow-md shadow-slate-400/50 justify-between p-2 fixed h-[40px] md:left-[205px] z-1 md:w-[calc(100%_-_205px)] max-md:left-[50px] max-md:w-[calc(100%_-_50px)]">
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




{{-- <nav class="topBar fixed h-[40px] lg:left-[205px] lg:w-[calc(100%_-_205px)] md:left-[50px] md:w-[calc(100%_-_50px)]">
    <div class="display">
        <div class="navlinks">
            <div class="nl1">
                <div class="">
                    <a href="{{ route('dept_head.home') }}" class="logoName">
                        FAMAS
                    </a>
                </div>
            </div>
            <div class="right" style="display:flex">
                    <x-dropdown2 align="right" width="48">
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
            </div>
        </div>
    </div>


</nav> --}}
