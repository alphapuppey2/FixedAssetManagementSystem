<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $this->deleteLogsAutomatically();

        // Get the current interval from cache (default to 'never' if not set)
        $interval = Cache::get('activity_log_deletion_interval', 'never');

        // Get the number of rows to display per page (default is 10)
        $perPage = $request->input('perPage', 10);

        // Fetch logs in descending order with pagination (10 logs per page)
        // $logs = ActivityLog::orderBy('created_at', 'desc')->paginate($perPage);
        $logs = ActivityLog::with(['user', 'asset'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Return the logs view to admin
        return view('admin.activityLogs', compact('logs', 'interval'));
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

    /**
     * Update the log deletion settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'deletion_interval' => 'required|in:1_week,1_month,1_year,never',
        ]);

        Cache::put('activity_log_deletion_interval', $validated['deletion_interval']);

        if ($validated['deletion_interval'] !== 'never') {
            Log::info('Scheduling next deletion after updating settings.');
            // Schedule the next deletion immediately when settings are updated
            $this->scheduleNextDeletion();
        } else {
            Log::info('Clearing next deletion timestamp because interval is set to "never".');
            Cache::forget('next_deletion_time');
        }

        return redirect()->back()->with('status', 'Log deletion settings updated successfully!');
    }

    /**
     * FOR TESTING ONLY
     */
    public function deleteLogsAutomatically()
    {
        $interval = Cache::get('activity_log_deletion_interval', 'never');

        // Respect the 'never' interval by skipping the deletion process
        if ($interval === 'never') {
            Log::info('Deletion skipped due to interval set to "never".');
            return;
        }

        $nextDeletionTimestamp = Cache::get('next_deletion_timestamp', null);
        $now = now()->timestamp * 1000;

        Log::info('Checking if it\'s time to delete logs.', [
            'nextDeletionTimestamp' => $nextDeletionTimestamp,
            'now' => $now,
        ]);

        // Check if it's time to delete logs
        if ($nextDeletionTimestamp  && $now >= $nextDeletionTimestamp) {
            Log::info('Deleting all logs from the activity_logs table.');
            // Delete all logs from the table
            DB::table('activity_logs')->truncate();

            Log::info('Scheduling the next deletion.');
            // Schedule the next deletion based on the interval
            $this->scheduleNextDeletion();
        } else {
            Log::info('It is not yet time to delete logs.', [
                'now' => $now,
                'nextDeletionTimestamp' => $nextDeletionTimestamp,
            ]);
        }
    }

    protected function scheduleNextDeletion()
    {
        $interval = Cache::get('activity_log_deletion_interval', 'never');
        $now = now();

        $nextDeletionTime = match ($interval) {
            '1_week' => $now->addWeek(),    // 1 week
            '1_month' => $now->addMonth(),  // 1 month
            '1_year' => $now->addYear(),    // 1 year
            default => null,
        };

        // FOR TESTING
        // $nextDeletionTime = match ($interval) {
        //     '1_week' => $now->addMinutes(1),   // 1 minute for testing
        //     '1_month' => $now->addMinutes(2),  // 2 minutes for testing
        //     '1_year' => $now->addMinutes(3),   // 3 minutes instead of 1 year
        //     default => null,
        // };

        if ($nextDeletionTime) {
            // Store the next deletion time in both cache and as a UNIX timestamp
            Cache::put('next_deletion_timestamp', $nextDeletionTime->timestamp * 1000);
            Log::info('Next deletion scheduled at:', ['timestamp' => $nextDeletionTime->timestamp * 1000]);
        } else {
            Log::warning('No valid interval provided. Deletion not scheduled.');
        }
    }
}
