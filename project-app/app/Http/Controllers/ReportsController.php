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
use App\Exports\AssetReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    /**
     * Display the reports page.
     *
     * @return \Illuminate\View\View
     */
    public function showReports(Request $request, $isExport = false)
    {
        $assetColumns = ['id', 'name', 'code', 'status', 'created_at'];
        $selectedColumns = session('selected_report_columns', $assetColumns);
        $filters = session('selected_report_filters', []);
    
        // Get the department of the logged-in user
        $userDepartmentId = Auth::user()->dept_id;
    
        // Determine the number of records per page (only relevant for non-export)
        $perPage = $request->input('rows_per_page', 10);
    
        // Fetch categories, manufacturers, models, and locations
        $categories = \App\Models\category::all();
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
            ->where('asset.dept_ID', $userDepartmentId);
    
        // Apply date filters
        $dateFilter = $request->input('date_filter');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date', date('Y-m-d'));
    
        if ($dateFilter) {
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('asset.created_at', '=', now()->toDateString());
                    break;
                case 'weekly':
                    $query->whereBetween('asset.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'monthly':
                    $query->whereMonth('asset.created_at', now()->month)
                          ->whereYear('asset.created_at', now()->year);
                    break;
                case 'yearly':
                    $query->whereYear('asset.created_at', now()->year);
                    break;
                case 'custom':
                    if ($startDate && $endDate) {
                        $query->whereBetween('asset.created_at', [$startDate, $endDate]);
                    }
                    break;
            }
        }
    
        // Fetch data with or without pagination
        $assetData = $isExport ? $query->get() : $query->paginate($perPage);
    
        if ($isExport) {
            return $assetData; // For export, just return the data
        }
    
        // Pass the variables to the view
        return view('dept_head.reports', compact(
            'assetColumns',
            'selectedColumns',
            'assetData',
            'perPage',
            'categories',
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
        \Log::info('Received request for saving report columns', $request->all()); // Log the request data for debugging
    
        // Retrieve selected columns
        $columns = $request->input('columns', []);
    
        // Validate that some columns are selected
        if (empty($columns)) {
            \Log::error('No columns selected');
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
        \Log::info('Successfully saved columns and filters', ['columns' => $columns, 'filters' => $filters]);
        return response()->json(['success' => true, 'columns' => $columns]);
    }
    

    public function fetchReportData(Request $request)
    {
        try {
            $selectedColumns = $request->input('columns', ['id', 'name', 'code', 'status', 'purchase_date']); // Default columns
    
            // Ensure the columns exist in the database schema to avoid invalid column errors
            $validColumns = \Schema::getColumnListing('asset');
            $selectedColumns = array_filter($selectedColumns, function($column) use ($validColumns) {
                return in_array($column, $validColumns);
            });
    
            if (empty($selectedColumns)) {
                \Log::error('No valid columns provided for fetching report data.');
                return response()->json(['success' => false, 'message' => 'No valid columns selected.'], 400);
            }
    
            // Fetch the data
            $assetData = \DB::table('asset')
                ->select($selectedColumns)
                ->get();
    
            // Return the data
            return response()->json([
                'success' => true,
                'columns' => $selectedColumns,
                'assetData' => $assetData,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching report data: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while fetching report data.'], 500);
        }
    }


    public function export(Request $request)
    {
        $format = $request->query('format', 'csv');
        $assetData = $this->showReports($request, true); // Pass true for export
        $selectedColumns = session('selected_report_columns', ['id', 'name', 'code', 'status', 'created_at']);
    
        // if ($format === 'pdf') {
        //     // Return as a regular HTML view for testing
        //     return view('exports.asset_report', [
        //         'assetData' => $assetData,
        //         'selectedColumns' => $selectedColumns,
        //     ]);
        // }
    
        switch ($format) {
            case 'xlsx':
                return Excel::download(new AssetReportExport($assetData, $selectedColumns), 'asset_report.xlsx');
            case 'pdf':
                $pdf = Pdf::loadView('exports.asset_report', [
                    'assetData' => $assetData,
                    'selectedColumns' => $selectedColumns,
                ]);
                return $pdf->download('asset_report.pdf');
            case 'csv':
            default:
                return Excel::download(new AssetReportExport($assetData, $selectedColumns), 'asset_report.csv');
        }
    }
    
    

}
