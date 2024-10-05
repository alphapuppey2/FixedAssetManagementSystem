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

        // Allow one final request when occurrences == ends, then mark as completed
        if ($preventive->occurrences < $preventive->ends) {
            // Increment occurrences by 1
            $preventive->occurrences += 1;
            $preventive->save();

            // Generate a maintenance request
            Maintenance::create([
                'description' => 'Scheduled preventive maintenance for asset ' . $preventive->asset_key,
                'status' => 'request',
                'asset_key' => $preventive->asset_key,
                'type' => 'maintenance',
            ]);

            // If the occurrences now equal the ends, mark as completed
            if ($preventive->occurrences == $preventive->ends) {
                $preventive->status = 'completed';
                $preventive->save();
            }

            return response()->json(['success' => true, 'message' => 'Maintenance generated and occurrences updated']);
        }

        // If occurrences have already reached or exceeded ends, return completed message
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






}
