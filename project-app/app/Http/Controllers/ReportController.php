<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Asset;

class ReportController extends Controller
{
    public function maintenanceReport(Request $request)
    {
        // Fetch filters from the request
        $assetName = $request->input('asset_name');
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query for maintenance reports with optional filters
        $query = Maintenance::query()
            ->join('assets', 'maintenance.asset_key', '=', 'assets.id')
            ->select('maintenance.*', 'assets.name as asset_name');

        if ($assetName) {
            $query->where('assets.name', 'like', '%' . $assetName . '%');
        }

        if ($type) {
            $query->where('maintenance.type', $type);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('maintenance.start_date', [$startDate, $endDate]);
        }

        // Paginate the results
        $maintenanceReports = $query->paginate(10);

        // Pass data to the view
        return view('dept_head.reports', compact('maintenanceReports'));
    }
}
