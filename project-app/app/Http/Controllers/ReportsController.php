<?php

namespace App\Http\Controllers;

use App\Models\assetModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class ReportsController extends Controller
{
    /**
     * Display the asset report with optional pagination and filtering.
     */
    public function assetReport(Request $request)
    {
        $user = Auth::user();
        $query = assetModel::query();

        // If the user is a department head, filter by their department
        if ($user->role === 'dept_head') {
            $query->where('dept_ID', $user->department_id);
        }

        // Apply search query if provided
        if ($request->filled('query')) {
            $query->where('name', 'like', '%' . $request->query . '%')
                  ->orWhere('code', 'like', '%' . $request->query . '%');
        }

        // Paginate the results
        $assets = $query->paginate($request->input('rows_per_page', 10));

        return view('dept_head.reports', compact('assets'));
    }

    /**
     * Generate a custom report based on selected fields and date range.
     */
    public function generateCustomReport(Request $request)
    {
        $fields = $request->input('fields', []);
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $dateRange = $request->input('dateRange'); // Handle date range input

        if (empty($fields)) {
            return response()->json(['status' => 'error', 'message' => 'Please select at least one field.'], 400);
        }

        // Handle dynamic date ranges
        if ($dateRange === 'weekly') {
            $startDate = now()->startOfWeek()->format('Y-m-d');
            $endDate = now()->endOfWeek()->format('Y-m-d');
        } elseif ($dateRange === 'monthly') {
            $startDate = now()->startOfMonth()->format('Y-m-d');
            $endDate = now()->endOfMonth()->format('Y-m-d');
        } elseif ($dateRange === 'yearly') {
            $startDate = now()->startOfYear()->format('Y-m-d');
            $endDate = now()->endOfYear()->format('Y-m-d');
        }

        try {
            $query = DB::table('assets')->select($fields);

            // Apply date filter if both dates are provided
            if ($startDate && $endDate) {
                $query->whereBetween('purchase_date', [$startDate, $endDate]);
            }

            $data = $query->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Report generated successfully.',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Report Generation Error:', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Failed to generate report.'], 500);
        }
    }

}
