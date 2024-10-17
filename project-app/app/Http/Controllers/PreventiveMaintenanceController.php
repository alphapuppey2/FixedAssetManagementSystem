<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Preventive;
use App\Models\Maintenance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

            if ($preventive->occurrences == $preventive->ends) {
                $preventive->status = 'completed';
                $preventive->save();
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
