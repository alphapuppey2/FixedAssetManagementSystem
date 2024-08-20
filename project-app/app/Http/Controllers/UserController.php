<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{
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
        ]);

        // Find the user and update their information
        $user = User::findOrFail($request->id);

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

    // SOFT DELETE
    public function changeStatus($id){
        // Find the user and update their status to inactive
        $user = User::findOrFail($id);
        $user->status = 'inactive'; // Set status to inactive
        $user->save();
    
        return redirect()->route('userList')->with('success', 'User status updated to inactive.');
    }
    

}
