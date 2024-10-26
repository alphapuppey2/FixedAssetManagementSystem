<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse|View
    {
        $user = User::findOrFail($id);

        // Validate the hash against the user's email
        if (!hash_equals(sha1($user->email), $hash)) {
            return redirect('/')->withErrors(['email' => 'The email verification link is invalid.']);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->redirectToRoleBasedRoute($user, true); // Redirect with verified query
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Redirect based on user type
        return $this->redirectToRoleBasedRoute($user, true);
    }

    public function verify(Request $request): RedirectResponse
    {
        $user = User::findOrFail($request->route('id'));

        // Check if the hash is valid
        if (!hash_equals(sha1($user->email), $request->route('hash'))) {
            return redirect('/')->withErrors(['email' => 'The email verification link is invalid.']);
        }

        // Mark the email as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Redirect logic based on user type
        $redirectRoute = match ($user->usertype) {
            'admin' => route('admin.home'),
            'dept_head' => route('dept_head.home'),
            'user' => route('user.scanQR'),
            default => route('login'), // Fallback route if user type is unexpected
        };

        return redirect()->intended($redirectRoute . '?verified=1');
    }

    /**
     * Determine the appropriate route based on user role.
     */
    private function redirectToRoleBasedRoute($user, bool $isVerified = false): RedirectResponse
    {
        $route = match ($user->usertype) {
            'admin' => route('admin.home'),
            'dept_head' => route('dept_head.home'),
            'user' => route('user.scanQR'),
            default => route('login'), // Fallback route
        };

        // Create a redirect response with the verified query parameter if applicable
        return redirect()->intended($route . ($isVerified ? '?verified=1' : ''));
    }
}
