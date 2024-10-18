<?php

namespace App\Http\Controllers;

use App\Models\AssignedToUser;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

abstract class Controller
{
    //
    public $user;

    function ___construct(){
        $this->user = auth()->user();
    }
    public function getUser($user){
        return $this->user;
    }

    public function searchFilter($search, $model , $tableName){
         // Get the search query from the request


         // Query the model and filter based on all columns
         $asst = $model::where(function($query) use ($search) {
             $columns = Schema::getColumnListing('asset');
             foreach ($columns as $column) {
                 $query->orWhere($column, 'LIKE', "%{$search}%");
             }
         })->get();

         // Return the results as JSON
         return response()->json($asst);
    }



    public function assetAcquiredBy($user , $assetKey){
        AssignedToUser::create([
            'date_acquired' => Carbon::now(),
            'used_by' => $user,
            'asset_id' => $assetKey,
            'assigned_by' => Auth::user()->id
        ]);
    }
    public function assetReturnedBy($user , $assetKey){
        AssignedToUser::where('used_by',$user)
        ->where('asset_id' , $assetKey)
        ->where('date_returned' , null)
        ->update([
            'date_returned' => Carbon::now(),
        ]);
    }
}
