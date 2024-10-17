<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use App\Mail\NewUserCredentialsMail;
use App\Models\User;

class UserController extends Controller
{
    public function autocomplete(Request $request)
    {
        try {
            // Get and trim the query parameter
            $query = trim($request->get('query'));

            // Get the authenticated user's department ID
            $departmentId = Auth::user()->dept_id;

            // Build the base query to filter users by the department
            $userQuery = User::where('dept_id', $departmentId)
                ->select('id', 'firstname', 'middlename', 'lastname') // Include 'id' in the select
                ->take(10); // Limit to 10 results for better performance

            // If a query is provided, add conditions to search for matching first or last names
            if (!empty($query)) {
                $userQuery->where(function ($q) use ($query) {
                    $q->where('firstname', 'LIKE', "%{$query}%")
                        ->orWhere('lastname', 'LIKE', "%{$query}%");
                });
            }

            // Execute the query and get the results
            $results = $userQuery->get();

            // Transform the results to match the format expected by Select2
            $formattedResults = $results->map(function ($user) {
                $fullName = $user->lastname . ',' . $user->firstname . ' ' . $user->middlename;
                return [
                    'id' => $user->id, // User ID
                    'name' => $fullName, // User full name as the display text
                ];
            });

            // Return the results with a 200 status
            return response()->json($formattedResults, 200);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Autocomplete error: ' . $e->getMessage());
            // Return a generic error message
            return response()->json(['error' => 'An unexpected server error occurred. Please try again later.'], 500); // Internal Server Error
        }
    }

    // SHOWS USER LIST
    public function getUserList(Request $request)
    {
        // Get the number of rows to display per page (default is 10)
        $perPage = $request->input('perPage', 10);

        // Use paginate directly on the query, before transforming the data
        $userList = DB::table('users')
            ->paginate($perPage);

        return view('admin.userList', ['userList' => $userList]);
    }

    // EDIT/UPDATE USER DETAILS
    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'id' => 'required|integer|exists:users,id',
            'employee_id' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dept_id' => 'required|integer|in:1,2,3,4',
            'status' => 'required|in:active,inactive',
            'birthdate' => 'required|date',
            'usertype' => 'required|in:user,dept_head,admin',
            'userPicture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the user and update their information
        $user = User::findOrFail($request->id);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = 'profile_' . strtolower($user->lastname) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public'); // Save to storage/app/public/profile_photos
            $user->userPicture = $path; // Store the relative path for future use
        }

        // Update other user detalis
        $user->employee_id = $request->employee_id;
        $user->firstname = $request->firstname;
        $user->middlename = $request->middlename;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->contact = $request->contact;
        $user->address = $request->address;
        $user->gender = $request->gender;
        $user->dept_id = $request->dept_id;
        $user->status = $request->status;
        $user->birthdate = $request->birthdate;
        $user->usertype = $request->usertype;
        $user->updated_at = now(); // Update the timestamp

        $user->save();

        return redirect()->route('userList')->with('success', 'User updated successfully.');
    }

    // SENDS EMAIL FOR CHANGE PASSWORD
    public function changePassword(Request $request)
    {
        $request->validate([
            'email' => 'requried|email',
        ]);

        $status = Password::sendResetLink(
            $request->only("email")
        );

        return $request === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // SOFT DELETE
    public function delete($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Set is_deleted to 1 (soft delete)
        $user->is_deleted = 1;
        $user->save();

        return redirect()->route('userList')->with('success', 'User deactivated successfully.');
    }


    public function search(Request $request)
    {
        $query = $request->input('query');
        $perPage = $request->input('perPage', 10); // Get rows per page from the request

        // Perform search query and paginate the results
        $userList = DB::table('users')
            ->where('firstname', 'like', "%{$query}%")
            ->orWhere('lastname', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->paginate($perPage) // Use the dynamic per page value
            ->appends(['query' => $query, 'perPage' => $perPage]); // Keep the query and perPage in pagination links

        return view('admin.userList', ['userList' => $userList]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'usertype' => 'required|string|in:admin,dept_head,user',
            'gender' => 'required|string|in:male,female,other',
            'dept_id' => 'required|integer|in:1,2,3,4',
            'address' => 'nullable|string',
            'contact' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate email and password based on input

        $email = strtolower(substr($validated['firstname'], 0, 1) . $validated['lastname'] . '@virginiafood.com.ph');
        // $email = 'dain.potato09@gmail.com';         // FOR TESTING PURPOSES
        $password = $validated['lastname'] . $validated['birthdate'];
        $hashedPassword = Hash::make($password);

        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = 'profile_' . strtolower($validated['lastname']) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public');
            $profilePicturePath = $path; // Store the relative path for future use
        }

        // Create the new user
        $user = User::create([
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'] ?? null,
            'lastname' => $validated['lastname'],
            'email' => $email,
            'password' => $hashedPassword,
            'birthdate' => $validated['birthdate'],
            'usertype' => $validated['usertype'],
            'gender' => $validated['gender'],
            'dept_id' => $validated['dept_id'],
            'address' => $validated['address'],
            'contact' => $validated['contact'],
            'status' => 'active',
            'remember_token' => Str::random(10),
            'userPicture' => $profilePicturePath,
        ]);

        // Generate employee_id based on usertype and user id
        switch ($user->usertype) {
            case 'admin':
                $employee_id = 'FMS-ADMN-' . $user->id;
                break;
            case 'dept_head':
                $employee_id = 'FMS-DPTHD-' . $user->id;
                break;
            default: // for 'user'
                $employee_id = 'FMS-USR-' . $user->id;
                break;
        }

        // Update the user with the generated employee_id
        $user->employee_id = $employee_id;
        $user->save();

        // Send an email to the user with their login credentials
        Mail::to($user->email)->send(new NewUserCredentialsMail($user->email, $password));

        // Redirect or return response after creation
        return redirect()->route('userList')->with('success', 'User created successfully!');
    }
}
