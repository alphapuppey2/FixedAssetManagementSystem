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

    // Ensure that only one request is processed at a time
    if ($preventive->occurrences >= $preventive->ends) {
        // If occurrences reached ends, mark as completed
        $preventive->status = 'completed';
        $preventive->save();

        return response()->json(['success' => true, 'message' => 'Maintenance completed']);
    }

    // Increment occurrences and generate a maintenance request
    if ($preventive->occurrences < $preventive->ends) {
        $preventive->occurrences += 1;  // Increment by 1 only
        $preventive->save();

        // Generate a maintenance request
        Maintenance::create([
            'description' => 'Scheduled preventive maintenance for asset ' . $preventive->asset_key,
            'status' => 'request',
            'asset_key' => $preventive->asset_key,
            'type' => 'maintenance',
        ]);

        return response()->json(['success' => true, 'message' => 'Maintenance generated']);
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
