<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    // Show the list of maintenance requests based on user type and tab
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'requests'); // Default tab is 'requests'

        $query = Maintenance::join('asset', 'maintenance.asset_key', '=', 'asset.id');

        // Apply status filter based on the selected tab
        if ($tab === 'requests') {
            $query->where('maintenance.status', 'request');
        } elseif ($tab === 'approved') {
            $query->where('maintenance.status', 'approved');
        } elseif ($tab === 'denied') {
            $query->where('maintenance.status', 'denied');
        }

        // Apply department filter for department heads
        if ($user->usertype === 'dept_head') {
            $deptId = $user->dept_id;
            $query->where('asset.dept_ID', $deptId);
        } elseif ($user->usertype === 'user') {
            $query->where('maintenance.requestor', $user->id);
        } else {
            return redirect()->route('user.home');
        }

        // Fetch the filtered and paginated results
        $requests = $query->paginate(10);

        // Return the view with the filtered requests and selected tab
        if ($user->usertype === 'dept_head') {
            return view('dept_head.maintenance', [
                'requests' => $requests,
                'tab' => $tab
            ]);
        } else {
            return view('user.requestList', compact('requests'));
        }
    }

    // Show the list of maintenance requests for the department head
    public function requests()
    {
        $user = Auth::user();

        if ($user->usertype === 'dept_head') {
            $deptId = $user->dept_id;

            $requests = Maintenance::join('asset', 'maintenance.asset_key', '=', 'asset.id')
                ->where('asset.dept_ID', $deptId)
                ->where('maintenance.status', 'request')
                ->select('maintenance.*')
                ->get();

                return view('dept_head.maintenance', [
                    'requests' => $requests,
                    'tab' => 'requests' // Default tab
                ]);
        }

        return redirect()->route('user.home');
    }

    // Show the list of approved maintenance requests
    public function approved()
    {
        $user = Auth::user();
        $query = Maintenance::join('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->where('maintenance.status', 'approved')
            ->select('maintenance.*');
    
        if ($user->usertype === 'dept_head') {
            $deptId = $user->dept_id;
            $query->where('asset.dept_ID', $deptId);
        } else {
            return redirect()->route('user.home');
        }
    
        $requests = $query->get();
    
        return view('dept_head.maintenance', [
            'requests' => $requests,
            'tab' => 'approved',
        ]);
    }

    // Show the list of denied maintenance requests
    public function denied()
    {
        $user = Auth::user();
        $query = Maintenance::join('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->where('maintenance.status', 'denied')
            ->select('maintenance.*');
    
        if ($user->usertype === 'dept_head') {
            $deptId = $user->dept_id;
            $query->where('asset.dept_ID', $deptId);
        } else {
            return redirect()->route('user.home');
        }
    
        $requests = $query->get();
    
        return view('dept_head.maintenance', [
            'requests' => $requests,
            'tab' => 'denied',
        ]);
    }

        // Approve a maintenance request
        public function approve($id)
        {
            $user = Auth::user();
    
            // Ensure the user is a department head
            if ($user->usertype !== 'dept_head') {
                return redirect()->route('user.home');
            }
    
            // Find the maintenance request
            $maintenance = Maintenance::findOrFail($id);
    
            // Update the status to 'approved'
            $maintenance->status = 'approved';
            $maintenance->authorized_by = $user->id;
            $maintenance->authorized_at = now();
            $maintenance->save();
    
            return redirect()->route('maintenance')->with('status', 'Request approved successfully.');
        }
    
        // Deny a maintenance request
        public function deny(Request $request, $id)
        {
            $user = Auth::user();
    
            // Ensure the user is a department head
            if ($user->usertype !== 'dept_head') {
                return redirect()->route('user.home');
            }
    
            // Find the maintenance request
            $maintenance = Maintenance::findOrFail($id);
    
            // Validate the reason
            $request->validate([
                'reason' => 'required|string|max:255',
            ]);
    
            // Update the status to 'denied'
            $maintenance->status = 'denied';
            $maintenance->authorized_by = $user->id;
            $maintenance->authorized_at = now();
            $maintenance->reason = $request->input('reason');
            $maintenance->save();
    
            return redirect()->route('maintenance')->with('status', 'Request denied.');
        }

}
