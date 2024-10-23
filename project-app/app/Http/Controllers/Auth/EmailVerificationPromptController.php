<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            // Conditional logic to determine the redirect route based on user type
            $redirectRoute = match ($request->user()->usertype) {
                'admin' => route('admin.home'),
                'dept_head' => route('dept_head.home'),
                'user' => route('user.scanQR'),
                default => route('login'), // Fallback route
            };

            return redirect()->intended($redirectRoute);
        }

        return view('auth.verify-email');
    }
}
