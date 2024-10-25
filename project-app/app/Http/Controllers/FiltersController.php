<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FiltersController extends Controller
{


    //DEPT HEAD
    public function filterAssets(Request $request)
    {
        $userDept = Auth::user()->dept_id;

        // Get filter inputs
        $statuses = (array) $request->input('status', []);
        $categories = (array) $request->input('category', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query assets with filters
        $assets = DB::table('asset')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->where('asset.dept_ID', $userDept)
            ->when($statuses, function ($query, $statuses) {
                return $query->whereIn('asset.status', $statuses);
            })
            ->when($categories, function ($query, $categories) {
                return $query->whereIn('category.id', $categories);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('asset.created_at', [$startDate, $endDate]);
            })
            ->select('asset.*', 'category.name as category_name')
            ->get();

        // Fetch categories for the dropdown
        $categoriesList = DB::table('category')
            ->where('dept_ID', $userDept)
            ->get();

        // Return the view with the assets and categories
        return view('dept_head.asset', compact('assets', 'categoriesList'));
    }



}
