<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Check if the user exists and is soft-deleted
        $user = User::where('email', $request->email)->first();

        if ($user && $user->is_deleted) {
            return back()->withErrors([
                'email' => 'This account is deactivated. Please contact support.',
            ])->withInput();
        }

        // Proceed with authentication if the user is not deactivated
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended($this->redirectByUserType());
    }

    public function redirectByUserType()
    {
        $user = Auth::user();

        // Redirect based on user type and department
        switch ($user->usertype) {
            case 'admin':
                return route('admin.home');
            case 'dept_head':
                return route('dept_head.home');
            case 'user':
                return route('user.scanQR');
        }

        return route('login');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
