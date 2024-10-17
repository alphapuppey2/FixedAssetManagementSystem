<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Preventive;
use App\Models\Maintenance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SystemNotification;
use App\Models\assetModel;


class PreventiveMaintenanceController extends Controller
{
    public function checkAndGenerate(Request $request)
    {
        $assetKey = $request->input('asset_key');
        $occurrences = $request->input('occurrences');

        // Retrieve the preventive maintenance record for the asset
        $preventive = Preventive::where('asset_key', $assetKey)->first();

        if (!$preventive) {
            return response()->json(['error' => 'Preventive record not found'], 404);
        }

        // Retrieve the asset and user
        $asset = assetModel::find($preventive->asset_key);
        $user = auth()->user();

        // Retrieve the department head
        $deptHead = \App\Models\User::where('usertype', 'dept_head')
            ->where('dept_id', auth()->user()->dept_id)
            ->first();

        // Enable query logging
        \DB::enableQueryLog();

        if ($preventive->occurrences < $preventive->ends) {
            // Increment occurrences by 1
            $preventive->occurrences += 1;
            $preventive->save();

            // Log the executed query
            \Log::info('Query log: ' . json_encode(\DB::getQueryLog()));

            // Generate a maintenance request
            Maintenance::create([
                'description' => 'Scheduled preventive maintenance for asset ' . $preventive->asset_key,
                'status' => 'request',
                'asset_key' => $preventive->asset_key,
                'type' => 'maintenance',
                'cost' => $preventive->cost,
                'requested_at' => now(),
            ]);

            if ($deptHead) {
                $notificationData = [
                    'title' => 'New Maintenance Request',
                    'message' => "Maintenance request for asset '{$asset->name}' (Code: {$asset->code}) has been generated.",
                    'asset_name' => $asset->name,
                    'asset_code' => $asset->code,
                    'authorized_by' => $user->id,
                    'authorized_user_name' => "{$user->firstname} {$user->lastname}",
                    'action_url' => route('maintenance'),
                ];
                \Log::info('Sending notification to department head.', [
                    'dept_head_id' => $deptHead->id,
                    'notification_data' => $notificationData
                ]);
        
                // Send the notification
                $deptHead->notify(new SystemNotification($notificationData));
            } else {
                \Log::warning('No department head found for user department.', [
                    'dept_id' => auth()->user()->dept_id
                ]);
            }

            if ($preventive->occurrences == $preventive->ends) {
                $preventive->status = 'completed';
                $preventive->save();

                \Log::info('Preventive maintenance marked as completed.', [
                    'asset_key' => $preventive->asset_key
                ]);

                // Send notification for completed maintenance cycle
                if ($deptHead) {
                    $completionNotificationData = [
                        'title' => 'Maintenance Cycle Completed',
                        'message' => "Preventive maintenance cycle for asset '{$asset->name}' (Code: {$asset->code}) has been completed.",
                        'asset_name' => $asset->name,
                        'asset_code' => $asset->code,
                        'authorized_by' => $user->id,
                        'authorized_user_name' => "{$user->firstname} {$user->lastname}",
                        'action_url' => route('maintenance.records'), // Link to records or reports page
                    ];

                    \Log::info('Sending notification for completed maintenance cycle.', [
                        'dept_head_id' => $deptHead->id,
                        'notification_data' => $completionNotificationData
                    ]);

                    $deptHead->notify(new SystemNotification($completionNotificationData));
                }
            }

            return response()->json(['success' => true, 'message' => 'Maintenance generated and occurrences updated']);
        }

        if ($preventive->occurrences >= $preventive->ends) {
            return response()->json(['success' => true, 'message' => 'Maintenance already completed']);
        }
    }

    public function resetCountdown(Request $request)
    {
        $assetKey = $request->input('asset_key');
        $preventive = Preventive::where('asset_key', $assetKey)->first();

        if ($preventive) {
            // Dynamically calculate the next maintenance date based on the last maintenance (updated_at) and frequency
            $lastMaintenance = Carbon::parse($preventive->updated_at);
            $nextMaintenanceDate = $lastMaintenance->addSeconds(10);  // For testing, use seconds instead of days

            return response()->json([
                'success' => true,
                'nextMaintenanceTimestamp' => $nextMaintenanceDate->timestamp  // Pass the next due timestamp to the frontend
            ]);
        }

        return response()->json(['error' => 'Preventive record not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $preventive = Preventive::findOrFail($id);

        // Update basic fields
        $preventive->cost = $request->input('cost');
        $preventive->frequency = $request->input('frequency');
        $preventive->ends = $request->input('ends');

        // Handle status change
        $preventive->status = $request->input('status');

        // If the status is 'cancelled', store the cancellation reason
        if ($request->input('status') == 'cancelled') {
            $preventive->cancel_reason = $request->input('cancel_reason');
        } else {
            $preventive->cancel_reason = null; // Reset the reason if not cancelled
        }

        $preventive->save();

        return redirect()->route('maintenance_sched')->with('status', 'Preventive maintenance updated successfully.');
    }

    public function edit($id)
    {
        // Find the preventive record by ID
        $preventive = Preventive::findOrFail($id);

        // Return the data as JSON for the modal to populate
        return response()->json($preventive);
    }



}
