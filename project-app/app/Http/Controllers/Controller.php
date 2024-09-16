<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;

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
}
