<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FiltersController extends Controller
{
    public function filterAssets(Request $request)
    {
        $statuses = $request->input('status', []);
        $categories = $request->input('category', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Redirect to the asset route with query parameters
        return redirect()->route('asset', [
            'status' => $statuses,
            'category' => $categories,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

}
