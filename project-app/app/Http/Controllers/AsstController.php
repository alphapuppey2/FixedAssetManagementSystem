<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\assetModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AsstController extends Controller
{
    //

    protected $cols = ['id' , 'name'];

    public function show(){

        $departments = array('list' => DB::table('department')->get());

        return view("asset" , $departments);
    }
    public function showForm(){
        return view('createAsset');
    }
    public function create(Request $request){
        $request->validate([
            'name'=> 'required',
            'category'=> 'required',
            'department' =>'required',
        ]);

    }
}
