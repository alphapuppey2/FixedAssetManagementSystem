<?php

namespace App\Jobs;

use App\Http\Controllers\PredictiveController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunPredictiveAnalysis implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle()
    {
        // Run the predictive analysis
        app(PredictiveController::class)->analyze();

        // Redispatch the job to run again after 24 hours (or any desired interval)
        // self::dispatch()->delay(now()->addDay());
        self::dispatch(); // Redispatch without any delay (for testing)
    }
}

