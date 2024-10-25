<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // return view('profile.edit', [
        //     'user' => $request->user(),
        // ]);
        return view('user.profile_edit', [
            'user' => $request->user(),
        ]);
    }

    public function adminView(Request $request): View
    {

        $user = $request->user();
        $departmentName = $user->department ? $user->department->name : 'N/A';

        return view('admin.profile', [
            'user' => $request->user(),
            'departmentName' => $departmentName,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    // public function update(ProfileUpdateRequest $request): RedirectResponse
    // {
    //     $request->user()->fill($request->validated());

    //     if ($request->user()->isDirty('email')) {
    //         $request->user()->email_verified_at = null;
    //     }

    //     $request->user()->save();

    //     return Redirect::route('profile.edit')->with('status', 'profile-updated');
    // }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'location' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        // Update text fields
        $user->address = $request->input('location');
        $user->contact = $request->input('contact');
        // $user->birthdate = $request->input('birthdate');
        // $user->gender = $request->input('gender');

        // Only update the birthdate if the user is an admin
        if ($user->usertype === 'admin') {
            $user->birthdate = $request->input('birthdate');
            $user->gender = $request->input('gender');
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete the old profile photo if it exists
            if ($user->userPicture && Storage::exists('public/profile_photos/' . $user->userPicture)) {
                Storage::delete('public/profile_photos/' . $user->userPicture);
            }

            // Store the new profile photo
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->userPicture = basename($path);
        }

        // Save changes
        $user->save();

        // Conditional redirect based on user type. Need polishing on routing, temp routing
        switch ($user->usertype) {
            case 'admin':
                return Redirect::route('admin.profile')->with('status', 'Profile updated successfully.');
            case 'dept_head':
                return Redirect::route('profile')->with('status', 'Profile updated successfully.');
            default:
                return Redirect::route('user.profile')->with('status', 'Profile updated successfully.');
        }
    }

    /**
     * Update the user's password.
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'old_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        // Verify the old password
        if (!Hash::check($request->input('old_password'), $user->password)) {
            return Redirect::back()->withErrors(['old_password' => 'The provided password does not match our records.']);
        }

        // Update the password
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        // Conditional redirect based on user type. Need polishing on routing, temp routing
        switch ($user->usertype) {
            case 'admin':
                return Redirect::route('admin.profile')->with('status', 'Password updated successfully.');
            case 'dept_head':
                return Redirect::route('profile')->with('status', 'Password updated successfully.');
            default:
                return Redirect::route('user.profile')->with('status', 'Password updated successfully.');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

}
