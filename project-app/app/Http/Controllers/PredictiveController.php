<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\ActivityLog;

class PredictiveController extends Controller
{
    public function analyze()
    {
        \Log::info('Analyze method triggered');

        // Step 1: Fetch all maintenance and asset data that we want to analyze
        $maintenanceData = DB::table('maintenance')
            ->join('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->select(
                'maintenance.asset_key',
                DB::raw('COUNT(maintenance.id) as repair_count'),
                DB::raw('AVG(maintenance.cost) as average_cost'),
                DB::raw('DATEDIFF(MAX(maintenance.completion_date), MIN(maintenance.start_date)) as time_between_repairs'),
                'asset.purchase_cost as asset_cost',
                'asset.usage_lifespan as lifespan_years',
                'asset.salvage_value',
                'asset.name as asset_name',
                'asset.code as asset_code',
                'asset.dept_ID as dept_ID'
            )
            ->where('maintenance.is_completed', 1) // Only completed repairs
            ->groupBy(
                'maintenance.asset_key',
                'asset.purchase_cost',
                'asset.usage_lifespan',
                'asset.salvage_value',
                'asset.name',
                'asset.code',
                'asset.dept_ID'
            )
            ->get();

        \Log::info('Maintenance Data Count:', ['count' => $maintenanceData->count()]);

        // Step 2: Loop through the data and send it to the Flask API for predictions
        foreach ($maintenanceData as $data) {
            // Check if a prediction already exists
            $existingPrediction = DB::table('predictive')
                ->where('asset_key', $data->asset_key)
                ->select('repair_count', 'average_cost') // Include relevant fields
                ->first();

            // Determine if prediction needs to be updated
            $needsUpdate = false;

            if ($existingPrediction) {
                // Check if any relevant data has changed
                if ($existingPrediction->repair_count != $data->repair_count ||
                    $existingPrediction->average_cost != $data->average_cost) {
                    $needsUpdate = true;
                }
            } else {
                // If no prediction exists, we need to generate one
                $needsUpdate = true;
            }

            // Proceed only if a new prediction is needed
            if ($needsUpdate) {
                // Calculate depreciation per year if lifespan is greater than 0
                $depreciationPerYear = $data->lifespan_years > 0 ?
                    ($data->asset_cost - $data->salvage_value) / $data->lifespan_years : 0;

                \Log::info('Sending Maintenance Data to Flask API:', [
                    'asset_key' => $data->asset_key,
                    'repair_count' => $data->repair_count,
                    'average_cost' => $data->average_cost,
                    'time_between_repairs' => $data->time_between_repairs,
                    'lifespan_years' => $data->lifespan_years,
                    'asset_cost' => $data->asset_cost,
                    'salvage_value' => $data->salvage_value,
                    'depreciation_per_year' => $depreciationPerYear,
                ]);

                // Step 3: Send the data to Flask API for prediction
                $response = Http::post('http://127.0.0.1:5000/predict', [
                    'purchase_cost' => $data->asset_cost,
                    'lifespan_years' => $data->lifespan_years,
                    'salvage_value' => $data->salvage_value,
                    'depreciation_per_year' => $depreciationPerYear, // Include the depreciation per year
                    'repair_count' => $data->repair_count,
                    'average_cost' => $data->average_cost,
                    'time_between_repairs' => $data->time_between_repairs,
                ]);

                \Log::info('Flask API Response Status:', ['status' => $response->status()]);
                \Log::info('Step 3: Flask API Response:', ['response' => $response->body()]);

                // Step 4: Get the prediction from the API
                if ($response->successful()) {
                    $prediction = $response->json()['prediction'];

                    \Log::info('Prediction received from API:', ['prediction' => $prediction]);

                    // Step 5: Store the prediction in the predictive table
                    DB::table('predictive')->updateOrInsert(
                        ['asset_key' => $data->asset_key],
                        [
                            'repair_count' => $data->repair_count,
                            'average_cost' => $data->average_cost,
                            'recommendation' => $prediction,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    );

                    // Step 6: Send Notification
                    $notificationData = [
                        'title' => 'Predictive Maintenance Generated',
                        'message' => "Predictive maintenance for asset '{$data->asset_name}' (Code: {$data->asset_code}) has been generated. Recommendation: {$prediction}.",
                        'asset_name' => $data->asset_name,
                        'asset_code' => $data->asset_code,
                        'action_url' => route('maintenance_sched.predictive')
                    ];

                    // Log notification data for debugging
                    \Log::info('Notification Data:', $notificationData);

                    $deptHead = \App\Models\User::where('usertype', 'dept_head')
                        ->where('dept_id', $data->dept_ID)
                        ->first();

                    if ($deptHead) {
                        $deptHead->notify(new \App\Notifications\SystemNotification($notificationData));
                        \Log::info('Notification sent to dept head for predictive maintenance.', ['asset_key' => $data->asset_key]);
                    } else {
                        \Log::warning('No department head found to notify.');
                    }

                    // Step 7: Log the predictive maintenance generation
                    ActivityLog::create([
                        'activity' => 'Predictive Maintenance Generated',
                        'description' => "Predictive maintenance generated for asset '{$data->asset_name}' (Code: {$data->asset_code}). Recommendation: {$prediction}.",
                        'userType' => 'system', // System-generated action
                        'user_id' => null, // No specific user responsible
                        'asset_id' => $data->asset_key,
                    ]);
                } else {
                    \Log::error('Flask API error: ' . $response->body());
                }
            } else {
                \Log::info('No update needed for asset_key:', ['asset_key' => $data->asset_key]);
            }
        }

        return response()->json(['status' => 'Analysis Complete']);
    }

}
