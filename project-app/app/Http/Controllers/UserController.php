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
    /*
        ASSIGNED TO
    */
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
        // Get search query and pagination settings
        $query = $request->input('query', '');
        $perPage = $request->input('perPage', 10);

        // Get sorting parameters from the request (default: id, asc)
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');

        // List of valid columns for sorting
        $validSortFields = [
            'id', 'employee_id', 'firstname', 'middlename', 'lastname',
            'email', 'usertype', 'is_deleted', 'department_name',
            'created_at', 'updated_at'
        ];

        // Validate the sort field
        if (!in_array($sortBy, $validSortFields)) {
            $sortBy = 'id';
        }

        // Get the ID of the currently logged-in admin
        $loggedInAdminId = Auth::id();

        // Retrieve filter parameters from the request
        $userTypes = $request->input('usertype', []);
        $departments = $request->input('department', []);
        $statuses = $request->input('status', []);

        // Build the query with search, filtering, sorting, and pagination
        $userList = DB::table('users')
            ->leftJoin('department', 'users.dept_id', '=', 'department.id')
            ->select('users.*', 'department.name as department_name')
            ->where('users.id', '!=', $loggedInAdminId) // Exclude the logged-in admin
            ->when($query, function ($q) use ($query) {
                // Apply search filter
                $q->where(function ($subquery) use ($query) {
                    $subquery->where('users.firstname', 'like', "%{$query}%")
                             ->orWhere('users.lastname', 'like', "%{$query}%")
                             ->orWhere('users.email', 'like', "%{$query}%");
                });
            })
            ->when(!empty($userTypes), function ($q) use ($userTypes) {
                // Handle 'Department Head' (which is 'dept_head')
                if (in_array('Department Head', $userTypes)) {
                    $userTypes = array_replace($userTypes, array_fill_keys(
                        array_keys($userTypes, 'Department Head'), 'dept_head'
                    ));
                }
                // Apply user type filter
                $q->whereIn('users.usertype', $userTypes);
            })
            ->when(!empty($departments), function ($q) use ($departments) {
                // Apply department filter
                $q->whereIn('users.dept_id', $departments);
            })
            ->when(!empty($statuses), function ($q) use ($statuses) {
                // Apply status filter (0 = Active, 1 = Inactive)
                $q->where(function ($subquery) use ($statuses) {
                    if (in_array('Active', $statuses)) {
                        $subquery->orWhere('users.is_deleted', 0);
                    }
                    if (in_array('Inactive', $statuses)) {
                        $subquery->orWhere('users.is_deleted', 1);
                    }
                });
            })
            ->orderBy($sortBy, $sortOrder) // Apply sorting
            ->paginate($perPage) // Apply pagination
            ->appends($request->all()); // Preserve query parameters for pagination links

        // Retrieve all departments for the filter dropdown
        $departments = DB::table('department')->select('id', 'name')->get();

        // Pass the necessary data to the view
        return view('admin.userList', [
            'userList' => $userList,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'perPage' => $perPage,
            'query' => $query,
            'userTypes' => $userTypes,
            'departments' => $departments,
            'statuses' => $statuses,
        ]);
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
            'gender' => 'required|in:male,female',
            'dept_id' => 'required|integer|in:1,2,3,4',
            'birthdate' => 'required|date',
            'usertype' => 'required|in:user,dept_head,admin',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the user by ID
        $user = User::findOrFail($request->id);

        // Handle profile photo upload if a new one is provided
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = 'profile_' . strtolower($user->lastname) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public'); // Save to storage/app/public/profile_photos
            $user->userPicture = $path; // Store the relative path
        }

        // Update other user details
        $user->employee_id = $request->employee_id;
        $user->firstname = $request->firstname;
        $user->middlename = $request->middlename;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->contact = $request->contact;
        $user->address = $request->address;
        $user->gender = $request->gender;
        $user->dept_id = $request->dept_id;
        $user->birthdate = $request->birthdate;
        $user->usertype = $request->usertype;
        $user->updated_at = now(); // Update the timestamp

        // Save the user record
        $user->save();

        // Redirect to the user list with a success message
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

    public function createUser(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'usertype' => 'required|string|in:admin,dept_head,user',
            'gender' => 'required|string|in:male,female,other',
            'dept_id' => 'required|integer|in:1,2,3,4',
            'address' => 'nullable|string',
            'contact' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'required|string|email|max:255|unique:users,email',
        ]);

        // $password = $validated['lastname'] . $validated['birthdate'];
        $password = Str::random(8);
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
            'email' => $validated['email'],
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

    public function reactivate($id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 0; // Set is_deleted to 0 to reactivate the user
        $user->save();

        return response()->json(['success' => true, 'message' => 'User reactivated successfully']);
    }
}
