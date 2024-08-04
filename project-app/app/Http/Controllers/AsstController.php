<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\assetModel as AssetModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AsstController extends Controller
{
    //
    public function show(){
        $userDept = Auth::user()->dept_id;


        $asset = array('assets' => DB::table('asset')->join('department', 'asset.dept_ID','=','department.id')
                                                     ->join('category', 'asset.ctg_ID','=','category.id')
                                                     ->where('asset.dept_ID','=',$userDept)
                                                     ->select('asset.*' ,'asset.id as assetcode','category.name as category','department.name as department')
                                                     ->get());
        return view("asset" , $asset);
    }
    public function showForm(){

        $departments = array('list' => DB::table('department')->get());
        $categories = array('ctglist' => DB::table('category')->get());

        // dd($departments);

        return view('createAsset',['departments' => $departments,'category' => $categories]);
    }
    public static function create(Request $request){
        $userDept = Auth::user()->dept_id;

        $request->validate([
            'name'=> 'required',
            'category'=> 'required',
        ]);


        $newAsset = new AssetModel();
        $newAsset->name = $request->name;
        $newAsset->ctg_ID= $request->category;
        $newAsset->dept_ID = $userDept;
        $newAsset->save();

        return redirect()->route('asset')->withInput();
    }
    public static function assetCount(){
        $userDept = Auth::user()->dept_id;

        $asset['active'] = DB::table('asset')->where('asset.status','=' , 'active')
                                             ->where("asset.dept_ID","=", $userDept)->count();
        $asset['um'] = DB::table('asset')->where('status','=' , 'under maintenance')
                                         ->where("asset.dept_ID","=", $userDept)->count();
        $asset['dispose'] = DB::table('asset')->where('status','=' , 'dispose')
                                              ->where("asset.dept_ID","=", $userDept)->count();
        $asset['deploy'] = DB::table('asset')->where('status','=' , 'deploy')
                                             ->where("asset.dept_ID","=", $userDept)->count();


        // dd($asset);

        return view('dashboard' , ['asset' => $asset]);

    }
}
