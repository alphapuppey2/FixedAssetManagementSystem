<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Fetch logs in descending order with pagination (10 logs per page)
        $logs = ActivityLog::orderBy('created_at', 'desc')->paginate(10);

        // Return the logs view to admin
        return view('admin.activityLogs', compact('logs'));
    }
}

