<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        $request->authenticate();
        $request->session()->regenerate();

        // Get the authenticated user
        $user = Auth::user();

        // Redirect based on user type and department
        if ($user->usertype === 'admin') {
            return redirect()->route('admin.home');
        } elseif ($user->usertype === 'dept_head') {
            // Redirect based on department
            if ($user->dept_id === 1) { // IT department ID
                return redirect()->route('dept_head.it.home');
            } elseif ($user->dept_id === 2) { // Sales department ID
                return redirect()->route('dept_head.sales.home');
            } elseif ($user->dept_id === 3) { // Fleet department ID
                return redirect()->route('dept_head.fleet.home');
            } elseif ($user->dept_id === 4) { // Production department ID
                return redirect()->route('dept_head.production.home');
            } else {
                return redirect()->route('default.home');
            }
        } else { // 'user' type
            return redirect()->route('user.home');
        }
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
