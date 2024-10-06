<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FAMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Boxicons JS -->
    <link href='https://unpkg.com/boxicons@2.1.4/dist/boxicons.js' rel='javascript'>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased h-screen w-screen max-md:text-xs">
    <div class="bodyContent relative h-full">
        @include('layouts.TopBar')
        @if (Auth::user()->usertype === 'admin')
            @include('layouts.adminSidebar')
        @elseif(Auth::user()->usertype === 'dept_head')
            @include('layouts.deptHeadSideBar')
        @else
            @include('layouts.sideBar')
        @endif
        <!-- Page Content -->
        <main
            class="relative md:left-[205px] max-md:w-[calc(100%_-_50px)] max-md:left-[50px] pl-3 pr-1 pb-1 pt-3 top-[40px] h-[calc(100%_-_40px)] w-[calc(100%_-_205px)] bg-slate-100/50 overflow-y-auto">
            <div class="mainContent relative grid grid-rows-[60px_1fr] min-h-full p-2">
                <div class="uppercase font-semibold items-center p-2">
                    <div class="flex flex-wrap items-center w-full">
                        @yield('header')
                    </div>
                    <div class="divider h-[2px] bg-slate-400 opacity-50 mb-2 mt-2"></div>
                </div>
                <div class="mmC relative min-h-[calc(100%_-_60px)]">
                    <div class="contentPage relative p-2 h-full">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
