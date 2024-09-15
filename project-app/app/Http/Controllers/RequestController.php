<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // Import DB facade
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Http\Request as HttpRequest;

class RequestController extends Controller
{
    public function showRequestList(HttpRequest $request) {
        // Get the ID of the currently logged-in user
        $userId = Auth::id();

        // Get the search query from the request
        $search = $request->input('search');

        // Fetch requests made by the currently logged-in user and apply search filter if present
        $requests = DB::table('request')
            ->where('requestor', $userId) // Assuming 'requestor' is the column storing the user ID
            ->when($search, function ($query, $search) {
                return $query->where('Description', 'like', '%' . $search . '%')
                             ->orWhere('status', 'like', '%' . $search . '%')
                             ->orWhere('asset_id', 'like', '%' . $search . '%')
                             ->orWhere('name', 'like', '%' . $search . '%')
                             ->orWhere('approvedBy', 'like', '%' . $search . '%');
            })
            ->get();

        // Pass the filtered requests data and search query back to the view
        return view('user.requestList', ['requests' => $requests, 'search' => $search]);
    }
}
