<?php

namespace App\Http\Controllers;

use App\Models\assetModel;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\ModelAsset;
use App\Models\locationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ReportsController extends Controller
{
    /**
     * Display the asset report with pagination, filtering, and search.
     */
    public function assetReport(Request $request)
    {
        $user = Auth::user();
        $query = assetModel::query();

        // Filter by department if the user is a department head
        if ($user && $user->usertype === 'dept_head') {
            $query->where('dept_ID', $user->dept_id);
        }

        // Apply search query if provided
        if ($request->filled('query')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->query('query') . '%')
                    ->orWhere('code', 'like', '%' . $request->query('query') . '%');
            });
        }

        // Paginate the results
        $assets = $query->with(['category', 'manufacturer', 'model', 'location'])
            ->paginate($request->input('rows_per_page', 10));

        // Load related data for filters
        $categories = Category::all(['id', 'name']);
        $manufacturers = Manufacturer::all(['id', 'name']);
        $models = ModelAsset::all(['id', 'name']);
        $locations = locationModel::all(['id', 'name']);

        // Pass the data to the view
        return view('dept_head.reports', compact('assets', 'categories', 'manufacturers', 'models', 'locations'));
    }

    public function show()
    {
        return view('dept_head.customReport');
    }

    // Generate the custom report based on selected fields
    public function generate(Request $request)
    {
        // Get the authenticated user's department ID
        $user = Auth::user();
        $userDepartmentId = $user->dept_id;

        $fields = $request->input('fields', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validate the presence of fields and dates
        if (empty($fields)) {
            return back()->with('error', 'At least one field must be selected.');
        }

        if (!$startDate || !$endDate) {
            return back()->with('error', 'Start date and end date are required.');
        }

        // Add the necessary joins and select statements for related fields
        $assets = DB::table('asset')
            ->leftJoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->leftJoin('department', 'asset.dept_ID', '=', 'department.id')
            ->leftJoin('manufacturer', 'asset.manufacturer_key', '=', 'manufacturer.id')
            ->leftJoin('model', 'asset.model_key', '=', 'model.id')
            ->leftJoin('location', 'asset.loc_key', '=', 'location.id')
            ->where('asset.dept_ID', $userDepartmentId) // Filter by user's department
            ->whereBetween('asset.created_at', [$startDate, $endDate])
            ->select(
                'asset.*',
                'category.name as category_name',
                'department.name as department_name',
                'manufacturer.name as manufacturer_name',
                'model.name as model_name',
                'location.name as location_name'
            )
            ->paginate(10)
            ->appends($request->query()); // Preserve query parameters

        // Pass the data to the view
        return view('dept_head.generatedReport', compact('assets', 'fields'));
    }

    public function downloadReport(Request $request)
    {
        $type = $request->query('type');
        $fields = $request->query('fields', []);
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $user = Auth::user();

        // Fetch the assets with the required data
        $assets = assetModel::select($this->buildSelectFields($fields))
            ->leftJoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->leftJoin('department', 'asset.dept_ID', '=', 'department.id')
            ->leftJoin('manufacturer', 'asset.manufacturer_key', '=', 'manufacturer.id')
            ->leftJoin('model', 'asset.model_key', '=', 'model.id')
            ->leftJoin('location', 'asset.loc_key', '=', 'location.id')
            ->where('asset.dept_ID', $user->dept_id)
            ->whereBetween('asset.created_at', [$startDate, $endDate])
            ->get();

        if ($assets->isEmpty()) {
            return back()->with('error', 'No assets found within the selected date range.');
        }

        if ($type === 'csv') {
            $csvFields = array_diff($fields, ['asst_img', 'qr_img']);  // Exclude images for CSV only
            return $this->generateCSV($assets, $csvFields);
        }

        if ($type === 'pdf') {
            // Use full URL paths for the images
            foreach ($assets as $asset) {
                $asset->asst_img_url = $asset->asst_img
                    ? public_path('storage/' . $asset->asst_img)
                    : public_path('images/no-image.png');
                $asset->qr_img_url = $asset->qr_img
                    ? public_path('storage/' . $asset->qr_img)
                    : public_path('images/defaultQR.png');
            }

            $pdf = Pdf::loadView('dept_head.generatedReportPDF', compact('assets', 'fields'))
                ->setPaper('a2', 'landscape');
            return $pdf->download('report.pdf');
        }

        return back()->with('error', 'Invalid download type.');
    }

    private function buildSelectFields($fields)
    {
        $selectFields = [];
        foreach ($fields as $field) {
            switch ($field) {
                case 'ctg_ID':
                    $selectFields[] = 'category.name as category_name';
                    break;
                case 'dept_ID':
                    $selectFields[] = 'department.name as department_name';
                    break;
                case 'manufacturer_key':
                    $selectFields[] = 'manufacturer.name as manufacturer_name';
                    break;
                case 'model_key':
                    $selectFields[] = 'model.name as model_name';
                    break;
                case 'loc_key':
                    $selectFields[] = 'location.name as location_name';
                    break;
                default:
                    $selectFields[] = "asset.$field";
                    break;
            }
        }
        return $selectFields;
    }

    private function generateCSV($assets, $fields)
    {
        $filename = 'report.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($assets, $fields) {
            $file = fopen('php://output', 'w');

            // Write header row
            fputcsv($file, array_map(function ($field) {
                return ucfirst(str_replace('_', ' ', $field));
            }, $fields));

            // Write data rows
            foreach ($assets as $asset) {
                $row = [];
                foreach ($fields as $field) {
                    switch ($field) {
                        case 'ctg_ID':
                            $row[] = $asset->category_name ?? 'N/A';
                            break;
                        case 'dept_ID':
                            $row[] = $asset->department_name ?? 'N/A';
                            break;
                        case 'manufacturer_key':
                            $row[] = $asset->manufacturer_name ?? 'N/A';
                            break;
                        case 'model_key':
                            $row[] = $asset->model_name ?? 'N/A';
                            break;
                        case 'loc_key':
                            $row[] = $asset->location_name ?? 'N/A';
                            break;
                        default:
                            $row[] = $asset->$field ?? 'N/A';
                            break;
                    }
                }
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}

/**
 * Generate a custom report based on selected fields and date range.
 */
    // public function generateCustomReport(Request $request)
    // {
    //     $fields = $request->input('fields', []);
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');
    //     $dateRange = $request->input('dateRange'); // Handle date range input

    //     if (empty($fields)) {
    //         return response()->json(['status' => 'error', 'message' => 'Please select at least one field.'], 400);
    //     }

    //     // Handle dynamic date ranges
    //     if ($dateRange === 'weekly') {
    //         $startDate = now()->startOfWeek()->format('Y-m-d');
    //         $endDate = now()->endOfWeek()->format('Y-m-d');
    //     } elseif ($dateRange === 'monthly') {
    //         $startDate = now()->startOfMonth()->format('Y-m-d');
    //         $endDate = now()->endOfMonth()->format('Y-m-d');
    //     } elseif ($dateRange === 'yearly') {
    //         $startDate = now()->startOfYear()->format('Y-m-d');
    //         $endDate = now()->endOfYear()->format('Y-m-d');
    //     }

    //     try {
    //         $query = DB::table('assets')->select($fields);

    //         // Apply date filter if both dates are provided
    //         if ($startDate && $endDate) {
    //             $query->whereBetween('purchase_date', [$startDate, $endDate]);
    //         }

    //         $data = $query->get();

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Report generated successfully.',
    //             'data' => $data
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Report Generation Error:', ['error' => $e->getMessage()]);
    //         return response()->json(['status' => 'error', 'message' => 'Failed to generate report.'], 500);
    //     }
    // }