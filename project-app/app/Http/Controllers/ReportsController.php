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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    /*
        ASSET REPORT METHODS
    */

    public function showAssetFilter()
    {
        $user = Auth::user();
        $categoryOptions = Category::where('dept_ID', $user->dept_id)->get();
        $manufacturerOptions = Manufacturer::where('dept_ID', $user->dept_id)->get();
        $modelOptions = ModelAsset::where('dept_ID', $user->dept_id)->get();
        $locationOptions = locationModel::where('dept_ID', $user->dept_id)->get();

        return view('dept_head.assetCustomReport', compact(
            'categoryOptions',
            'manufacturerOptions',
            'modelOptions',
            'locationOptions'
        ));
    }

    // Generate the custom report based on selected fields
    public function generateAssetReport(Request $request)
    {
        // Get the authenticated user's department ID
        $user = Auth::user();
        $userDepartmentId = $user->dept_id;

        $fields = $request->input('fields', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status', []);
        $category = $request->input('category', []);
        $manufacturer = $request->input('manufacturer', []);
        $model = $request->input('model', []);
        $location = $request->input('location', []);

        // Validate the presence of fields and dates
        if (empty($fields)) {
            return back()->with('error', 'At least one field must be selected.');
        }

        if (!$startDate || !$endDate) {
            return back()->with('error', 'Start date and end date are required.');
        }

        // Build the query
        $query = DB::table('asset')
            ->leftJoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->leftJoin('department', 'asset.dept_ID', '=', 'department.id')
            ->leftJoin('manufacturer', 'asset.manufacturer_key', '=', 'manufacturer.id')
            ->leftJoin('model', 'asset.model_key', '=', 'model.id')
            ->leftJoin('location', 'asset.loc_key', '=', 'location.id')
            ->where('asset.dept_ID', $userDepartmentId)
            ->whereBetween('asset.created_at', [$startDate, $endDate]);

        // Apply filters if provided
        if (!empty($status)) {
            $query->whereIn('asset.status', $status);
        }
        if (!empty($category)) {
            $query->whereIn('asset.ctg_ID', $category);
        }
        if (!empty($manufacturer)) {
            $query->whereIn('asset.manufacturer_key', $manufacturer);
        }
        if (!empty($model)) {
            $query->whereIn('asset.model_key', $model);
        }
        if (!empty($location)) {
            $query->whereIn('asset.loc_key', $location);
        }

        // Select the fields and paginate results
        $assets = $query->select(
            'asset.*',
            'category.name as category_name',
            'department.name as department_name',
            'manufacturer.name as manufacturer_name',
            'model.name as model_name',
            'location.name as location_name'
        )
            ->paginate(10)
            ->appends($request->query());

        // Pass the data to the view
        return view('dept_head.reports.generatedAssetReport', compact('assets', 'fields'));
    }

    public function downloadAssetReport(Request $request)
    {
        $type = $request->query('type');
        $fields = $request->query('fields', []);
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status', []);
        $category = $request->query('category', []);
        $manufacturer = $request->query('manufacturer', []);
        $model = $request->query('model', []);
        $location = $request->query('location', []);
        $user = Auth::user();

        // Fetch the assets with the required data and apply filters
        $query = assetModel::select($this->buildSelectFieldsForAssets($fields))
            ->leftJoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->leftJoin('department', 'asset.dept_ID', '=', 'department.id')
            ->leftJoin('manufacturer', 'asset.manufacturer_key', '=', 'manufacturer.id')
            ->leftJoin('model', 'asset.model_key', '=', 'model.id')
            ->leftJoin('location', 'asset.loc_key', '=', 'location.id')
            ->where('asset.dept_ID', $user->dept_id)
            ->whereBetween('asset.created_at', [$startDate, $endDate]);

        // Apply filters if provided
        if (!empty($status)) {
            $query->whereIn('asset.status', $status);
        }
        if (!empty($category)) {
            $query->whereIn('asset.ctg_ID', $category);
        }
        if (!empty($manufacturer)) {
            $query->whereIn('asset.manufacturer_key', $manufacturer);
        }
        if (!empty($model)) {
            $query->whereIn('asset.model_key', $model);
        }
        if (!empty($location)) {
            $query->whereIn('asset.loc_key', $location);
        }

        $assets = $query->get();

        if ($assets->isEmpty()) {
            return back()->with('error', 'No assets found within the selected date range.');
        }

        if ($type === 'csv') {
            $csvFields = array_diff($fields, ['asst_img', 'qr_img']);  // Exclude images for CSV only
            return $this->generateAssetCSV($assets, $csvFields);
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

            $pdf = Pdf::loadView('dept_head.reports.generatedAssetReportPDF', compact('assets', 'fields'))
                ->setPaper('a2', 'landscape');
            return $pdf->download('asset_report.pdf');
        }

        return back()->with('error', 'Invalid download type.');
    }

    private function buildSelectFieldsForAssets($fields)
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

    private function generateAssetCSV($assets, $fields)
    {
        $filename = 'asset_report.csv';
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

    /*
        MAINTENANCE REPORT METHODS
    */

    public function showMaintenanceFilter()
    {
        $user = Auth::user();
        $deptId = $user->dept_id;

        // Get dept_head and user names from the same department
        $authorizedByOptions = DB::table('users')
            ->where('dept_id', $deptId)
            ->where('usertype', 'dept_head')
            ->pluck('firstname', 'id');

        $requestorOptions = DB::table('users')
            ->where('dept_id', $deptId)
            ->where('usertype', 'user')
            ->pluck('firstname', 'id');

        // Get asset names under the user's department
        $assetOptions = DB::table('asset')
            ->where('dept_ID', $deptId)
            ->pluck('name', 'id');

        return view('dept_head.maintenanceCustomReport', compact(
            'authorizedByOptions',
            'requestorOptions',
            'assetOptions'
        ));
    }

    public function generateMaintenanceReport(Request $request)
    {
        $user = Auth::user();
        $deptId = $user->dept_id;

        // Validate the presence of fields and dates
        $fields = $request->input('fields', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (empty($fields)) {
            return back()->with('error', 'At least one field must be selected.');
        }

        if (!$startDate || !$endDate) {
            return back()->with('error', 'Start date and end date are required.');
        }

        // Validate cost range
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'cost_min' => 'nullable|numeric|min:1',
            'cost_max' => 'nullable|numeric|gt:cost_min', // Cost max must be greater than cost min
        ]);

        // Get input fields
        $fields = $request->input('fields', []);
        $mntcType = $request->input('mntc_type', []);
        $status = $request->input('status', []);
        $isCompleted = $request->input('is_completed', null);
        $authorizedBy = $request->input('authorized_by', []);
        $requestor = $request->input('requestor', []);
        $assetKey = $request->input('asset_key', []);
        $costMin = $request->input('cost_min', null);
        $costMax = $request->input('cost_max', null);
        $startDate = $request->input('start_date', null);
        $endDate = $request->input('end_date', null);

        // Build the query
        $query = DB::table('maintenance')
            ->leftJoin('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->leftJoin('users as authorized', 'maintenance.authorized_by', '=', 'authorized.id')
            ->leftJoin('users as requester', 'maintenance.requestor', '=', 'requester.id');

        // Dynamically select columns
        $selectFields = [];
        foreach ($fields as $field) {
            switch ($field) {
                case 'authorized_by':
                    $selectFields[] = 'authorized.firstname as authorized_by_name';
                    break;
                case 'requestor':
                    $selectFields[] = 'requester.firstname as requestor_name';
                    break;
                case 'asset_key':
                    $selectFields[] = 'asset.name as asset_name';
                    break;
                default:
                    $selectFields[] = "maintenance.$field";
                    break;
            }
        }

        $query->select($selectFields)->where('asset.dept_ID', $deptId);

        // Apply filters
        if (!empty($mntcType)) {
            $query->whereIn('maintenance.type', $mntcType); // Filter by mntc_type
        }
        if (!empty($status)) {
            $query->whereIn('maintenance.status', $status);
        }
        if (!is_null($isCompleted)) {
            $query->where('maintenance.is_completed', $isCompleted);
        }
        if (!empty($authorizedBy)) {
            $query->whereIn('maintenance.authorized_by', $authorizedBy);
        }
        if (!empty($requestor)) {
            $query->whereIn('maintenance.requestor', $requestor);
        }
        if (!empty($assetKey)) {
            $query->whereIn('maintenance.asset_key', $assetKey);
        }
        if (!is_null($costMin)) {
            $query->where('maintenance.cost', '>=', $costMin);
        }
        if (!is_null($costMax)) {
            $query->where('maintenance.cost', '<=', $costMax);
        }
        if ($startDate && $endDate) {
            $query->whereBetween('maintenance.created_at', [$startDate, $endDate]);
        }

        // Paginate results
        $maintenanceRecords = $query->paginate(10)->appends($request->query());

        return view('dept_head.reports.generatedMaintenanceReport', compact('maintenanceRecords', 'fields'));
    }

    public function downloadMaintenanceReport(Request $request)
    {
        Log::info('Maintenance report download initiated.', ['request' => $request->all()]);

        $type = $request->query('type');
        $user = Auth::user();
        $deptId = $user->dept_id;
        $fields = $request->query('fields', []);

        Log::info('User authenticated', ['user_id' => $user->id, 'dept_id' => $deptId]);

        // Fetch the filter inputs
        $mntcType = Arr::wrap($request->query('mntc_type', []));
        $status = Arr::wrap($request->query('status', []));
        $authorizedBy = Arr::wrap($request->query('authorized_by', []));
        $requestor = Arr::wrap($request->query('requestor', []));
        $assetKey = Arr::wrap($request->query('asset_key', []));

        $isCompleted = $request->query('is_completed', null);
        $costMin = $request->query('cost_min', null);
        $costMax = $request->query('cost_max', null);

        Log::info('Filters applied', [
            'typeFilter' => $mntcType,
            'status' => $status,
            'authorizedBy' => $authorizedBy,
            'requestor' => $requestor,
            'assetKey' => $assetKey,
            'isCompleted' => $isCompleted,
            'costMin' => $costMin,
            'costMax' => $costMax
        ]);

        // Build the query dynamically based on selected fields
        $query = DB::table('maintenance')
            ->leftJoin('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->leftJoin('users as authorized', 'maintenance.authorized_by', '=', 'authorized.id')
            ->leftJoin('users as requester', 'maintenance.requestor', '=', 'requester.id')
            ->select(
                'maintenance.*',
                'asset.name as asset_name',
                DB::raw('COALESCE(authorized.firstname, "N/A") as authorized_by_name'),
                DB::raw('COALESCE(requester.firstname, "N/A") as requestor_name'),
                DB::raw('COALESCE(maintenance.status, "N/A") as status')
            )
            ->where('asset.dept_ID', $deptId);

        $selectFields = [];
        foreach ($fields as $field) {
            switch ($field) {
                case 'authorized_by':
                    $selectFields[] = 'authorized.firstname as authorized_by_name';
                    break;
                case 'requestor':
                    $selectFields[] = 'requester.firstname as requestor_name';
                    break;
                case 'asset_key':
                    $selectFields[] = 'asset.name as asset_name';
                    break;
                default:
                    $selectFields[] = "maintenance.$field";
                    break;
            }
        }

        $query->select($selectFields)->where('asset.dept_ID', $deptId);

        // Apply filters
        if (!empty($mntcType)) $query->whereIn('maintenance.type', $mntcType);
        if (!empty($status)) $query->whereIn('maintenance.status', $status);
        if (!is_null($isCompleted)) $query->where('maintenance.is_completed', $isCompleted);
        if (!empty($authorizedBy)) $query->whereIn('maintenance.authorized_by', $authorizedBy);
        if (!empty($requestor)) $query->whereIn('maintenance.requestor', $requestor);
        if (!empty($assetKey)) $query->whereIn('maintenance.asset_key', $assetKey);
        if (!is_null($costMin)) $query->where('maintenance.cost', '>=', $costMin);
        if (!is_null($costMax)) $query->where('maintenance.cost', '<=', $costMax);

        $records = $query->get();

        if ($records->isEmpty()) {
            Log::warning('No maintenance records found with the selected filters.');
            return back()->with('error', 'No maintenance records found with the selected filters.');
        }

        Log::info('Maintenance records retrieved', ['record_count' => $records->count()]);

        // Handle CSV Download
        if ($type === 'csv') {
            Log::info('Generating CSV for maintenance report');
            return $this->generateMaintenanceCSV($records, $fields);
        }

        // Handle PDF Download
        if ($type === 'pdf') {
            Log::info('Generating PDF for maintenance report');
            $pdf = Pdf::loadView('dept_head.reports.generatedMaintenanceReportPDF', compact('records', 'fields'))
                ->setPaper('a2', 'landscape');
            return $pdf->download('maintenance_report.pdf');
        }

        Log::error('Invalid download type provided', ['type' => $type]);
        return back()->with('error', 'Invalid download type.');
    }

    private function generateMaintenanceCSV($records, $fields)
    {
        $filename = 'maintenance_report.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($records, $fields) {
            $file = fopen('php://output', 'w');

            // Write header row based on selected fields
            fputcsv($file, array_map(function ($field) {
                switch ($field) {
                    case 'asset_key':
                        return 'Asset Name';
                    case 'is_completed':
                        return 'Completed';
                    default:
                        return ucfirst(str_replace('_', ' ', $field));
                }
            }, $fields));

            // Write data rows based on selected fields
            foreach ($records as $record) {
                $row = [];
                foreach ($fields as $field) {
                    switch ($field) {
                        case 'authorized_by':
                            $row[] = $record->authorized_by_name ?? 'N/A';
                            break;
                        case 'requestor':
                            $row[] = $record->requestor_name ?? 'N/A';
                            break;
                        case 'asset_key':
                            $row[] = $record->asset_name ?? 'N/A';
                            break;
                        case 'is_completed':
                            $row[] = $record->is_completed ? 'Yes' : 'No';
                            break;
                        default:
                            $row[] = $record->$field ?? 'N/A';
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
