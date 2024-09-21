<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest; // Renaming Laravel's Request class to avoid conflict
use App\Models\Request; // Import the Request model (your repair requests)
use Illuminate\Support\Facades\Auth;

class RepairController extends Controller
{
    // Method to handle the form submission for repair request
    public function store(HttpRequest $request)
    {
        // Validate the input from the form
        $request->validate([
            'asset_id' => 'required|exists:asset,id', // Correcting to match 'asset' table
            'issue_description' => 'required|string|max:1000',
        ]);

        // Create a new repair request in the 'request' table
        \App\Models\Request::create([
            'Description' => $request->input('issue_description'), // Field for the issue description
            'status' => 'pending', // Default status for a new repair request
            'asset_id' => $request->input('asset_id'), // Reference to the asset
            'requestor' => Auth::id(), // Store the logged-in user's ID as the requestor
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('status', 'Repair request submitted successfully.');
    }
}
