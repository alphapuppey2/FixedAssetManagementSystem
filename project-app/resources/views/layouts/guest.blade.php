<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            html, body {
                margin: 0;
                padding: 0;
                height: 100%;
                width: 100%;
            }

            body {
                display: flex;
                justify-content: center;
                align-items: center;
                background: linear-gradient(135deg, white 50%, #0A2942 50%);
                background-size: 150% 150%; /* Scale gradient to ensure it covers */
                background-position: center;
                background-repeat: no-repeat;
                transition: background-size 0.5s ease-in-out;
            }

            .content-container {
                width: 100%;
                max-width: 1200px;
                padding: 1rem;
                box-sizing: border-box;
            }

            /* Responsive background adjustment */
            @media (max-width: 768px) {
                body {
                    background-size: 200% 200%; /* Larger background for smaller screens */
                    background-position: center;
                }
            }

            @media (min-width: 1200px) {
                body {
                    background-size: 100% 100%; /* Exact fit for larger screens */
                }
            }
        </style>

    </head>
    <body class="font-sans antialiased h-screen w-screen max-md:text-xs">

        <div class="min-h-screen w-full flex justify-center items-center" style="background: linear-gradient(135deg, white 50%, #0A2942 50%); background-size: cover; background-position: center; background-repeat: no-repeat;">
            {{ $slot }}
        </div>

        {{-- <script src="js/form.js"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
