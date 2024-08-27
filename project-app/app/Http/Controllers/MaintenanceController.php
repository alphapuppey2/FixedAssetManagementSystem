<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    // Show the list of maintenance requests for the authenticated user
    public function index()
    {
        // Get the ID of the currently authenticated user
        $userId = Auth::id();

        // Fetch maintenance requests made by the authenticated user
        $requests = Maintenance::where('requestor', $userId)->get();

        // Pass the requests to the view
        return view('user.requestList', compact('requests'));
    }
}