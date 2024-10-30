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
        // Step 1: Fetch relevant maintenance and asset data
        $maintenanceData = DB::table('maintenance')
            ->join('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->select(
                'maintenance.asset_key',
                DB::raw('COUNT(maintenance.id) as repair_count'),
                DB::raw('AVG(maintenance.cost) as average_cost'),
                DB::raw('DATEDIFF(MAX(maintenance.completion_date), MIN(maintenance.start_date)) as time_between_repairs'),
                'asset.purchase_cost as asset_cost',
                'asset.name as asset_name',  // Add this line
                'asset.code as asset_code',  // Add this line
                'asset.dept_ID as dept_ID'  // Include dept_ID here
            )
            ->where('maintenance.is_completed', 1) // Only completed repairs
            ->groupBy(
                'maintenance.asset_key',
                'asset.purchase_cost',
                'asset.name',
                'asset.code',
                'asset.dept_ID'  // Add dept_ID to the GROUP BY clause
            )
            ->get();

        // Step 2: Loop through the data and apply the conditions
        foreach ($maintenanceData as $data) {
            // Condition 1: Repair count must be more than 4
            if ($data->repair_count > 1) {
                // Condition 2: Average cost must exceed 60% of the asset's purchase cost
                $cost_threshold = $data->asset_cost * 0.40; // 60% of asset cost
                if ($data->average_cost > $cost_threshold) {
                    // Condition 3: Time between repairs must decrease by 20-30%
                    // Assuming time_between_repairs is decreasing (you can enhance this logic based on previous time intervals)
                    if ($data->time_between_repairs < 30) { //days
                        // Step 3: Send the data to Flask API for prediction
                        $response = Http::post('http://165.22.59.23:5000/predict', [
                            'repair_count' => $data->repair_count,
                            'average_cost' => $data->average_cost,
                            'time_between_repairs' => $data->time_between_repairs,
                        ]);

                        // Step 4: Get the prediction from the API
                        $prediction = $response->json()['prediction'];

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
                            'asset_name' => $data->asset_name,  // Ensure this is included
                            'asset_code' => $data->asset_code,  // Ensure this is included
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
                }
            }
        }

        return response()->json(['status' => 'Analysis Complete']);
    }

    public function startAnalysis()
    {
        // Dispatch the job to run the analysis for the first time
        // RunPredictiveAnalysis::dispatch()->delay(now()->addDay()); //for actual, daily analysis
        RunPredictiveAnalysis::dispatch();
    }
    // deep logic for predictive, incase
    // public function analyze()
    // {
    //     $maintenanceData = DB::table('maintenance')
    //         ->join('asset', 'maintenance.asset_key', '=', 'asset.id')
    //         ->select('maintenance.asset_key', 'asset.cost as asset_cost',
    //             DB::raw('COUNT(maintenance.id) as repair_count'),
    //             DB::raw('AVG(maintenance.cost) as average_cost'),
    //             DB::raw('DATEDIFF(MAX(maintenance.completion_date), MIN(maintenance.start_date)) as total_time_between_repairs'))
    //         ->where('maintenance.completed', 1)
    //         ->groupBy('maintenance.asset_key', 'asset.cost')
    //         ->get();

    //     foreach ($maintenanceData as $data) {
    //         if ($data->repair_count > 4) {
    //             $cost_threshold = $data->asset_cost * 0.60; // 60% of asset cost

    //             if ($data->average_cost > $cost_threshold) {
    //                 // Get all time intervals between repairs for this asset
    //                 $time_intervals = DB::table('maintenance')
    //                     ->where('asset_key', $data->asset_key)
    //                     ->orderBy('completion_date', 'asc')
    //                     ->pluck(DB::raw('DATEDIFF(completion_date, start_date) as interval'));

    //                 // Compare consecutive time intervals to calculate percentage decrease
    //                 $total_decrease = 0;
    //                 $previous_interval = null;
    //                 foreach ($time_intervals as $index => $interval) {
    //                     if ($previous_interval !== null) {
    //                         $percentage_decrease = (($previous_interval - $interval) / $previous_interval) * 100;
    //                         $total_decrease += $percentage_decrease;
    //                     }
    //                     $previous_interval = $interval;
    //                 }

    //                 $average_decrease = $total_decrease / (count($time_intervals) - 1); // Get the average percentage decrease

    //                 // If the average percentage decrease is greater than 20%, flag for predictive maintenance
    //                 if ($average_decrease > 20) {
    //                     // Send data to Flask API for prediction
    //                     $response = Http::post('http://127.0.0.1:5000/predict', [
    //                         'repair_count' => $data->repair_count,
    //                         'average_cost' => $data->average_cost,
    //                         'time_between_repairs' => $data->total_time_between_repairs,
    //                     ]);

    //                     $prediction = $response->json()['prediction'];

    //                     // Store the prediction in the predictive table
    //                     DB::table('predictive')->updateOrInsert(
    //                         ['asset_key' => $data->asset_key],
    //                         [
    //                             'repair_count' => $data->repair_count,
    //                             'average_cost' => $data->average_cost,
    //                             'recommendation' => $prediction,
    //                             'created_at' => now(),
    //                             'updated_at' => now()
    //                         ]
    //                     );
    //                 }
    //             }
    //         }
    //     }

    //     return response()->json(['status' => 'Analysis Complete']);
    // }

}
