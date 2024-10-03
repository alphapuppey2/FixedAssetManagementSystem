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

        $preventive = Preventive::where('asset_key', $assetKey)->first();

        if ($preventive) {
            // Check if occurrences match the 'ends' value, marking it as complete
            if ($occurrences >= $preventive->ends) {
                $preventive->status = 'completed';
                $preventive->save();

                return response()->json(['message' => 'Maintenance completed']);
            }

            // Update the occurrences count
            $preventive->occurrences = $occurrences;
            $preventive->save();

            // Generate a maintenance request with requestor = null
            Maintenance::create([
                'description' => 'Scheduled preventive maintenance for asset ' . $preventive->asset_key,
                'status' => 'request',
                'asset_key' => $preventive->asset_key,
                // 'requestor' => null,  // Set requestor to null for system-generated requests
                'type' => 'maintenance',
            ]);

            return response()->json(['message' => 'Maintenance request generated and occurrences updated']);
        } else {
            return response()->json(['error' => 'Preventive record not found'], 404);
        }
    }

}
