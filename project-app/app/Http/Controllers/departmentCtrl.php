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
        // $departments = new department(['id', 'name']);
        // $departments->save();
        $departments = array('list' => DB::table('department')->get());

        // dump(departments::all());
        return view('createDepartment' , $departments);
    }
    public function retrieve(Request $request){
        $departments = array('list' => DB::table('department')->get());
        return  $departments;
    }
    public static function create(Request $request){
        $department = new department();
        $department->name = $request->name;
        $department->save();

        $departments = array('list' => DB::table('department')->get());
        return view('createDepartment', $departments);
    }

    public static function store(Request $request){}
}
