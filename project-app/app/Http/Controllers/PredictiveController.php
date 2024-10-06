<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PredictiveController extends Controller
{
    public function analyze()
    {
        // Step 1: Query the maintenance data for each asset
        $maintenanceData = DB::table('maintenance')
            ->select('asset_key', DB::raw('COUNT(id) as repair_count'), DB::raw('AVG(cost) as average_cost'), DB::raw('DATEDIFF(MAX(completion_date), MIN(start_date)) as time_between_repairs'))
            ->groupBy('asset_key')
            ->where('completed', '=', 1)
            ->get();

        // Step 2: Loop through the data and send it to the Flask API for prediction
        foreach ($maintenanceData as $data) {
            // Call the machine learning API (Flask) to get prediction
            $response = Http::post('http://127.0.0.1:5000/predict', [
                'repair_count' => $data->repair_count,  // Repair count from query
                'average_cost' => $data->average_cost,  // Average cost from query
                'time_between_repairs' => $data->time_between_repairs  // Time between repairs
            ]);

            // Get the prediction from the API response
            $prediction = $response->json('prediction') ?? 'maintenance'; // Default to 'maintenance' if Flask doesn't return a value

            // Step 3: Update or insert the predictive analysis result in the 'predictive' table
            DB::table('predictive')->updateOrInsert(
                ['asset_key' => $data->asset_key],
                [
                    'repair_count' => $data->repair_count,
                    'average_cost' => $data->average_cost,
                    'recommendation' => $prediction,
                    'created_at' => DB::raw('IFNULL(created_at, NOW())'),
                    'updated_at' => now(),
                ]
            );
        }

        // Return a message to indicate the analysis is complete
        return response()->json(['message' => 'Predictive maintenance analysis completed!']);
    }

    public function startAnalysis()
    {
        // Dispatch the job to run the analysis for the first time
        // RunPredictiveAnalysis::dispatch()->delay(now()->addDay()); //for actual, daily analysis
        RunPredictiveAnalysis::dispatch();

    }
}
