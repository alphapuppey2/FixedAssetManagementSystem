<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $departmentIDs = ['depID' => DB::table('department')->get()];
        return view('auth.register', $departmentIDs);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'department' => ['required', 'string', 'lowercase'],
            'usertype' => ['required', 'string', 'in:admin,dept_head,user'], // Validate user type
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'dept_id' => $request->department,
            'usertype' => $request->usertype, // Save the usertype
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on user type
        $redirectRoute = match ($user->usertype) {
            'admin' => route('admin.home'),
            'dept_head' => route('dept_head.home'),
            'user' => route('user.scanQR'),
            default => route('login'), // Fallback in case of an unexpected usertype
        };

        return redirect($redirectRoute);
    }
}