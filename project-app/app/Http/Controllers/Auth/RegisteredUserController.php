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
        // dd($request->all());
        $request->validate([
            'firstname'=> ['required', 'string', 'max:255'],
            'lastname'=> ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'dept_id' => ['required', 'exists:department,id'],
            'usertype' => ['required', 'string', 'in:admin,dept_head,user'], // Validate user type
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'contact' => ['required', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
            'birthdate' => ['required', 'date'],
        ]);

        $user = User::create([
            'firstname'=> $request->firstname,
            'lastname'=> $request->lastname,
            'email' => $request->email,
            'dept_id' => $request->dept_id,
            'usertype' => $request->usertype,
            'contact' => $request->contact,
            'birthdate' => $request->birthdate,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user); // Make sure this is here

        // Redirect logic based on user type
        $redirectRoute = match ($user->usertype) {
            'admin' => route('admin.home'),
            'dept_head' => route('dept_head.home'),
            'user' => route('user.scanQR'),
            default => route('login'),
        };

        return redirect($redirectRoute);
    }
}
