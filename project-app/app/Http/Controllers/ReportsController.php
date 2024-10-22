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
    public function generateAssetRerport(Request $request)
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
}
