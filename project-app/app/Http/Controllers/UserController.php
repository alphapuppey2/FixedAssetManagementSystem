<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function getUserList(){
        $userList = array('userList' => DB::table('users')->get());
        return view('admin.userList', $userList);
    }
}
