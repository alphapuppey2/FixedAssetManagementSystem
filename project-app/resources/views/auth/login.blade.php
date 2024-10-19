<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="contentContainer flex flex-col items-center justify-center min-h-screen">
        <div class="z-10">
            @if ($errors->has('email'))
            <div class="mb-4 p-4 text-red-700 bg-red-100 border border-red-700 rounded">
                {{ $errors->first('email') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                @csrf
                <div class="w-96">
                    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
                        <div class="border-b border-black py-8 mb-8">
                            <h2 class="text-2xl font-bold text-center">LOGIN</h2>
                        </div>


                        <!-- Email Address -->
                        <div class="space-y-1">
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                    </svg>

                                </div>
                                <input id="email" placeholder="Email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" class="block w-full pl-10 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="space-y-1">
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>

                                </div>
                                <input id="password" placeholder="Password" type="password" name="password" required autocomplete="current-password" class="block w-full pl-10 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center text-sm text-gray-700">
                                <input id="remember_me" type="checkbox" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" name="remember">
                                <span class="ml-2">Remember me</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm text-customButtonBlue hover:text-indigo-500">Forgot Password?</a>
                        </div>

                        <div class="flex justify-center mt-8">
                            <button type="submit" class="px-4 py-2 text-lg font-medium text-white bg-customButtonBlue rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">LOGIN</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        {{-- background --}}

</x-guest-layout>
