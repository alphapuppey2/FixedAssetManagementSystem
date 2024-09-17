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
            ->where('requestor', $userId) // Ensure only the current user's requests are shown
            ->when($search, function ($query, $search) {
                // Group the OR conditions to maintain the user filter
                return $query->where(function($query) use ($search) {
                    $query->where('Description', 'like', '%' . $search . '%')
                          ->orWhere('status', 'like', '%' . $search . '%')
                          ->orWhere('asset_id', 'like', '%' . $search . '%')
                          ->orWhere('id', 'like', '%' . $search . '%')
                          ->orWhere('approvedBy', 'like', '%' . $search . '%')
                          ->orWhere('created_at', 'like', '%' . $search . '%')
                          ->orWhere('updated_at', 'like', '%' . $search . '%');
                });
            })
            ->get();

        // Pass the filtered requests data and search query back to the view
        return view('user.requestList', ['requests' => $requests, 'search' => $search]);
    }
}
