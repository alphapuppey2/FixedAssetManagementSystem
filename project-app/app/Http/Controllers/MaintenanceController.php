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

        // Check if the authenticated user is a dept_head
        if (Auth::user()->usertype === 'dept_head') {
            // Fetch all maintenance requests with status 'request'
            $requests = Maintenance::where('status', 'request')->get();

            // Pass the requests to the view
            return view('dept_head.maintenance', compact('requests'));
        } else {
            // Fetch maintenance requests made by the authenticated user
            $requests = Maintenance::where('requestor', $userId)->where('status', 'request')->get();

            // Pass the requests to the view
            return view('user.requestList', compact('requests'));
        }
    }

        // Show the list of maintenance requests for the authenticated user
        public function requests()
        {
            // Get the ID of the currently authenticated user
            $userId = Auth::id();
            // Fetch maintenance requests made by the authenticated user
            $requests = Maintenance::where('requestor', $userId)
                ->where('status', 'request')
                ->get();
    
            return view('dept_head.maintenance', compact('requests'));
        }
    
        public function approved()
        {
            // Get the ID of the currently authenticated user
            $userId = Auth::id();
            // Fetch approved maintenance requests
            $requests = Maintenance::where('requestor', $userId)
                ->where('status', 'approve')
                ->get();
    
            return view('dept_head.maintenance', compact('requests'));
        }
    
        public function denied()
        {
            // Get the ID of the currently authenticated user
            $userId = Auth::id();
            // Fetch denied maintenance requests
            $requests = Maintenance::where('requestor', $userId)
                ->where('status', 'deny')
                ->get();
    
            return view('dept_head.maintenance', compact('requests'));
        }
}