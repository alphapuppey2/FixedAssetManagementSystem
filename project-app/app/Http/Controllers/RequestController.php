<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // Import DB facade
use Illuminate\Http\Request as HttpRequest;

class RepairController extends Controller
{
    
    public function showRequestList() {
        // Fetch requests using the DB facade
        $requests = DB::table('request')->get();

    
        // Debugging the query output
        if ($requests->isEmpty()) {
            dd('No requests found in the database.');
        }
    
        // Pass the requests data to the view
        return view('user.requestList', compact('requests'));
    }
}
