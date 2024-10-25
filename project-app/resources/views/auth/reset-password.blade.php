<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-[400px]">
            <h2 class="text-center text-2xl font-semibold mb-4 text-navy-700">Reset Password</h2>
            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-2 w-full border-gray-300 rounded-md bg-gray-100" type="email"
                        name="email" :value="old('email', $request->email)" required autocomplete="username" readonly />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('New Password')" />
                    <x-text-input id="password" class="block mt-2 w-full border-gray-300 rounded-md" type="password"
                        name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-2 w-full border-gray-300 rounded-md"
                        type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400 bg-red-500" />
                </div>

                <!-- Reset Password Button -->
                <div class="flex justify-center mt-6">
                    <button type="submit"
                        class="bg-customButtonBlue text-white font-semibold py-2 px-4 rounded-lg hover:bg-navy-600 focus:outline-none btn-outline-primary">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
