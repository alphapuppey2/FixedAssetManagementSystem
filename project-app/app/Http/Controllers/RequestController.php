<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // Import DB facade
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Http\Request as HttpRequest;
class RequestController extends Controller
{
    public function showRequestList() {
        // Get the ID of the currently logged-in user
        $userId = Auth::id();

        // Fetch requests made by the currently logged-in user
        $requests = DB::table('request')
            ->where('requestor', $userId) // Assuming 'requestor' is the column storing the user ID
            ->get();

        // Debugging the query output
        if ($requests->isEmpty()) {
            dd('No requests found for the current user.');
        }

        // Pass the filtered requests data to the view
        return view('user.requestList', compact('requests'));
    }
}
