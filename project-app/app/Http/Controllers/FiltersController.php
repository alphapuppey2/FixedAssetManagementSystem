<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FiltersController extends Controller
{

    //admin

    public function filterAssetsAdmin(Request $request)
    {
        $statuses = $request->input('status', []);
        $categories = $request->input('category', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by', 'asset.name');
        $sortOrder = $request->input('sort_order', 'asc');

        // Redirect with filters and sorting to showAllAssets
        return redirect()->route('assetList', [
            'status' => $statuses,
            'category' => $categories,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'perPage' => $perPage,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ]);
    }

    //dept head
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
