<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Get the number of rows to display per page (default is 10)
        $perPage = $request->input('perPage', 10);

        // Fetch logs in descending order with pagination (10 logs per page)
        $logs = ActivityLog::orderBy('created_at', 'desc')->paginate($perPage);

        // Return the logs view to admin
        return view('admin.activityLogs', compact('logs'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $perPage = $request->input('perPage', 10);

        // Perform search and paginate results
        $logs = ActivityLog::where('activity', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends(['query' => $query, 'perPage' => $perPage]);

        return view('admin.activityLogs', ['logs' => $logs]);
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'csv'); // Default to CSV if format is not provided

        // Fetch all activity logs
        $logs = DB::table('activity_logs')->get();

        if ($format === 'csv') {
            return $this->exportAsCSV($logs);
        } elseif ($format === 'pdf') {
            return $this->exportAsPDF($logs);
        }

        return redirect()->back()->with('error', 'Invalid export format selected.');
    }

    protected function exportAsCSV($logs)
    {
        $filename = 'activity_logs.csv';
        $csvContent = "Activity,Description,User Role,User ID,Asset ID,Request ID,Date & Time\n";

        foreach ($logs as $log) {
            $csvContent .= "\"{$log->activity}\",\"{$log->description}\",\"{$log->userType}\","
                . "\"{$log->user_id}\",\"{$log->asset_id}\",\"{$log->request_id}\","
                . "\"{$log->created_at}\"\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    protected function exportAsPDF($logs)
    {
        $pdf = PDF::loadView('admin.pdf.activityLogsPDF', ['logs' => $logs])
            ->setPaper('a2', 'landscape');
        return $pdf->download('activity_logs.pdf');
    }

}
