<!-- resources/views/user/home.blade.php -->
@include('layouts.sideBarUser')

<div class="ml-64 p-8">

    @yield('scanQR-content')
    @yield('requestList-content')
    @yield('notification-content')
    @yield('profile-content')

</div>
