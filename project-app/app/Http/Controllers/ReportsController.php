<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\category;
use App\Models\department;
use App\Models\Manufacturer;
use App\Models\ModelAsset;
use App\Models\locationModel;

class ReportsController extends Controller
{
    /**
     * Display the reports page.
     *
     * @return \Illuminate\View\View
     */
    public function showReports(Request $request)
    {
        $assetColumns = Schema::getColumnListing('asset');
        $selectedColumns = session('selected_report_columns', $assetColumns); // Use saved columns or default to all
        $filters = session('selected_report_filters', []);

        // Get the department of the logged-in user
        $userDepartmentId = Auth::user()->dept_id;

        // Determine the number of records per page
        $perPage = $request->input('rows_per_page', 10);

        // Fetch categories, departments, manufacturers, models, and locations
        $categories = \App\Models\category::all();
        $departments = \App\Models\department::all();
        $manufacturers = \App\Models\Manufacturer::all();
        $models = \App\Models\ModelAsset::all();
        $locations = \App\Models\locationModel::all();

        // Start building the query
        $query = \DB::table('asset')
            ->select('asset.*',
                'category.name as category_name',
                'department.name as department_name',
                'manufacturer.name as manufacturer_name',
                'model.name as model_name',
                'location.name as location_name'
            )
            ->leftJoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->leftJoin('department', 'asset.dept_ID', '=', 'department.id')
            ->leftJoin('manufacturer', 'asset.manufacturer_key', '=', 'manufacturer.id')
            ->leftJoin('model', 'asset.model_key', '=', 'model.id')
            ->leftJoin('location', 'asset.loc_key', '=', 'location.id')
            ->where('asset.dept_ID', $userDepartmentId); // Filter by the user's department

        // Apply the filters
        foreach ($filters as $field => $values) {
            if (!empty($values) && !in_array('all', $values)) {
                $query->whereIn($field, $values);
            }
        }

        // Fetch data with pagination
        $assetData = $query->paginate($perPage);

        return view('dept_head.reports', compact(
            'assetColumns',
            'selectedColumns',
            'assetData',
            'perPage',
            'categories',
            'departments',
            'manufacturers',
            'models',
            'locations'
        ));
    }



    /**
     * Save the selected report columns.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveReportColumns(Request $request)
    {
        \Log::info($request->all()); // Log the request data for debugging

        // Retrieve selected columns
        $columns = $request->input('columns', []);

        // Validate that some columns are selected
        if (empty($columns)) {
            return response()->json(['success' => false, 'message' => 'No columns selected.']);
        }

        // Save the selected columns in the session (or database)
        session(['selected_report_columns' => $columns]);

        // Retrieve filter options for dropdown fields
        $filters = [
            'status' => $request->input('status', []),
            'ctg_ID' => $request->input('ctg_ID', []),
            'dept_ID' => $request->input('dept_ID', []),
            'manufacturer_key' => $request->input('manufacturer_key', []),
            'model_key' => $request->input('model_key', []),
            'loc_key' => $request->input('loc_key', []),
        ];

        // Save the filters in the session
        session(['selected_report_filters' => $filters]);

        // Return success response
        return response()->json(['success' => true, 'columns' => $columns]);
    }

}
