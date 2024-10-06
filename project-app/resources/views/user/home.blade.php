<!-- resources/views/user/home.blade.php -->
@include('layouts.userSideBar')

<div class="ml-64 p-8">

    @yield('scanQR-content')
    @yield('section')
    @yield('requestList-content')
    @yield('notification-content')
    @yield('profile-content')
    @yield('profile_edit-content')
    @yield('profile_password-content')

</div>

<!-- Toast Notification -->
@if(session('status'))
    <div id="toast" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
        {{ session('status') }}
    </div>
@endif

<!-- JavaScript for Toast Notification -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.getElementById('toast');
        if (toast) {
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000); // Hide the toast after 3 seconds
        }
    });
</script>
