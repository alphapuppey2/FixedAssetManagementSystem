<?php

namespace App\Jobs;

use App\Http\Controllers\PredictiveController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RunPredictiveAnalysis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle()
    {
        Log::info("Predictive maintenance job started.");

        // Run the predictive analysis logic
        app(PredictiveController::class)->analyze();

        Log::info("Predictive maintenance job finished.");
    }

}
