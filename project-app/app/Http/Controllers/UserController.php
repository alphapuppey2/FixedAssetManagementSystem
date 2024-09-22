<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\NewUserCredentialsMail;
use App\Models\User;

class UserController extends Controller{
    public function getUserList(){
        $userList = DB::table('users')->get()->map(function($user) {
            // Map dept_id to department name
            switch ($user->dept_id) {
                case 1:
                    $user->department = 'IT';
                    break;
                case 2:
                    $user->department = 'Sales';
                    break;
                case 3:
                    $user->department = 'Fleet';
                    break;
                case 4:
                    $user->department = 'Production';
                    break;
                default:
                    $user->department = 'Unknown';
            }
            return $user;
        });

        return view('admin.user-list', ['userList' => $userList]);
    }

    public function update(Request $request){
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
            $file->move(public_path('uploads/profile_photos'), $filename);
            $user->userPicture = $filename;
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

    // HARD DELETE
    public function delete($id){
        // Find the user and delete
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('userList')->with('success', 'User deleted successfully.');
    }

    public function search(Request $request){
        $query = $request->input('query');
        $userList = DB::table('users')
            ->where('firstname', 'like', "%$query%")
            ->orWhere('lastname', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->get()
            ->map(function($user) {
                switch ($user->dept_id) {
                    case 1:
                        $user->department = 'IT';
                        break;
                    case 2:
                        $user->department = 'Sales';
                        break;
                    case 3:
                        $user->department = 'Fleet';
                        break;
                    case 4:
                        $user->department = 'Production';
                        break;
                    default:
                        $user->department = 'Unknown';
                }
                return $user;
            });

        return view('admin.user-list', ['userList' => $userList]);
    }

    public function store(Request $request){
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

        // $email = strtolower(substr($validated['firstname'], 0, 1) . substr($validated['middlename'], 0, 1) . $validated['lastname'] . '@virginiafood.com.ph');
        $email = 'dain.potato09@gmail.com';         // FOR TESTING PURPOSES
        $password = $validated['lastname'] . $validated['birthdate'];
        $hashedPassword = Hash::make($password);

        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = 'profile_' . strtolower($validated['lastname']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile_photos'), $filename);
            $profilePicturePath = $filename;
        }

        // Create the new user
        $user = User::create([
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'],
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
