<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class maintenance extends Controller
{
    //
    public function show(){

        return view("dept_head.maintenance");
    }
    public function showForm(){
        return view("dept_head.createMaintenance");
    }
}
