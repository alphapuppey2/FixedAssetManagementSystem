<?php

namespace App\Http\Controllers;

use app\Models\User;
use Illuminate\Support\Facades\DB;

Class Controller
{
    //
    public $user;

    function ___construct(){
        $this->user = auth()->user();
    }
    public function getUser($user){
        return $this->user;
    }

    public function getUserList(){
        $users = DB::table('users')->get();
        return view('admin.userList', $users);
    }
}
