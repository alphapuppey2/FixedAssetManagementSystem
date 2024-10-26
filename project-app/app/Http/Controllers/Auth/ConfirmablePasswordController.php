<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the user's password
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // Store the password confirmation timestamp in the session
        $request->session()->put('auth.password_confirmed_at', time());

        // Redirect based on user type
        $user = $request->user();
        $redirectRoute = match ($user->usertype) {
            'admin' => route('admin.home'),
            'dept_head' => route('dept_head.home'),
            'user' => route('user.scanQR'),
            default => route('login'), // Fallback if user type is unexpected
        };

        return redirect()->intended($redirectRoute);
    }
}
