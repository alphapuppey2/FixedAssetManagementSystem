<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB facade

class ReportsController extends Controller
{
    /**
     * Display the list of assets with relationships, filters, and pagination.
     */
    public function AssetReport(Request $request)
    {
        // Retrieve query parameters
        $query = $request->input('query'); // Search query
        $rowsPerPage = $request->input('rows_per_page', 10); // Pagination value (default: 10)

        // Fetch assets using raw SQL with relationships
        $assets = DB::table('asset')
        ->leftJoin('category', 'asset.ctg_ID', '=', 'category.id')
        ->leftJoin('manufacturer', 'asset.manufacturer_key', '=', 'manufacturer.id')
        ->leftJoin('location', 'asset.loc_key', '=', 'location.id')
        ->select(
            'asset.id',
            'asset.name',
            'asset.code',
            'asset.purchase_date',
            'asset.usage_lifespan',  // Changed to original column name
            'asset.salvage_value',
            'asset.purchase_cost',
            'asset.depreciation',
            'asset.status',
            'category.name as category_name',       // Alias as category_name
            'manufacturer.name as manufacturer_name', // Alias as manufacturer_name
            'location.name as location_name'         // Alias as location_name
        )
        ->when($query, function ($q) use ($query) {
            $q->where('asset.name', 'like', "%{$query}%")
              ->orWhere('asset.code', 'like', "%{$query}%");
        })
        ->paginate($rowsPerPage);

    return view('dept_head.reports', compact('assets'));


    }
}
