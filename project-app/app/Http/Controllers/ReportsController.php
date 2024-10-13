<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\assetModel as Asset; // Import your model correctly


class ReportsController extends Controller
{
    /**
     * Display a listing of the assets with pagination.
     */
    public function AssetReport(Request $request)
    {
        // Retrieve query parameters
        $query = $request->input('query');
        $rowsPerPage = $request->input('rows_per_page', 10);

        // Fetch assets with relationships and filters
        $assets = Asset::with(['category', 'manufacturer', 'location'])
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%");
            })
            ->paginate($rowsPerPage);

        // Return the view with assets and current request inputs
        return view('dept_head.reports', compact('assets'));
    }
}
