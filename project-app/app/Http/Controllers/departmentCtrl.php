<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class departmentCtrl extends Controller
{
    //
    protected $table = "department";
   public static  function index(Request $request)
    {
        $departments = array('list' => DB::table('department')->get());

        return view('dept_head.createDepartment' , $departments);
    }
    public static function create(Request $request){
        $department = new department();
        $department->name = $request->name;
        $department->save();

        $departments = array('list' => DB::table('department')->get());
        return view('dept_head.createDepartment', $departments);
    }

    public static function store(Request $request){}
}
