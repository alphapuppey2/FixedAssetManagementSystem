<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class sideBarUserController extends Controller
{
    public function scanQR()
    {
        return view('user.scanQR');
    }

    public function requestList()
    {
        return view('user.requestList');
    }

    public function notification()
    {
        return view('user.notification');
    }

    public function profile()
    {
        return view('user.profile');
    }
}
