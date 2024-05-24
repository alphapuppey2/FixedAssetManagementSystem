
    <nav class="nb navbar navbar-expand-lg " style="position: fixed; background-color:teal; height:100vh">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

       <div class="collapse navbar-collapse navLists" id="navbarSupportedContent">
         <div class="navbar-nav me-auto mb-2 mb-lg-0" style="display: flex; flex-direction:column; ">
            <div class="nav-item">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
            </div>
            <div class="nav-item">
                <x-nav-link :href="route('asset')" :active="request()->routeIs('asset')">
                    {{ __('Asset') }}
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
         </div>
       </div>
</nav>
