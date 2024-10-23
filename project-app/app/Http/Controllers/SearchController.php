<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\assetModel;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->input('query');
        $userType = Auth::user()->usertype;
        $deptId = Auth::user()->dept_id; // Get the user's department ID

        // Initialize result collections
        $users = collect();
        $assets = collect();
        $request = collect();

        // Admin Search: Search all tables and departments
        if ($userType === 'admin') {
            // Admin logic: Search all assets and maintenance records
            $users = User::where('firstname', 'LIKE', "%$query%")
                ->orWhere('lastname', 'LIKE', "%$query%")
                ->orWhere('email', 'LIKE', "%$query%")
                ->orWhere('employee_id', 'LIKE', "%$query%")
                ->get();

            $assets = assetModel::where('name', 'LIKE', "%$query%")
                ->orWhere('code', 'LIKE', "%$query%")
                ->get();

            $assetIds = $assets->pluck('id'); // Get IDs of matching assets

            $request = Maintenance::whereIn('asset_key', $assetIds)
                ->orWhere('description', 'LIKE', "%$query%")
                ->orWhere('type', 'LIKE', "%$query%")
                ->get();
        } elseif ($userType === 'dept_head') {
            // Dept Head logic: Filter assets by department and search within those
            $assets = assetModel::where('dept_ID', $deptId)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%")
                        ->orWhere('code', 'LIKE', "%$query%");
                })
                ->get();

            $assetIds = $assets->pluck('id'); // Get IDs of matching assets

            $request = Maintenance::whereIn('asset_key', $assetIds)
                ->orWhere(function ($q) use ($query) {
                    $q->where('description', 'LIKE', "%$query%")
                        ->orWhere('type', 'LIKE', "%$query%");
                })
                ->get();
        }

        // Return the results to a view
        return view('search.results', compact('users', 'assets', 'request', 'query'));
    }

    public function searchMaintenance(Request $request)
    {
        $user = Auth::user();
        $userType = $user->usertype;
        $deptId = $user->dept_id;

        $query = $request->input('query', '');
        $tab = $request->input('tab', 'requests');
        $perPage = $request->input('rows_per_page', 10);

        // Initialize the maintenance query with joins
        $maintenanceQuery = Maintenance::query()
            ->join('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->leftJoin('users', 'maintenance.requestor', '=', 'users.id')
            ->leftJoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->leftJoin('location', 'asset.loc_key', '=', 'location.id')
            ->select(
                'maintenance.*',
                DB::raw("CONCAT(users.firstname, ' ', IFNULL(users.middlename, ''), ' ', users.lastname) AS requestor_name"),
                'category.name AS category_name',
                'location.name AS location_name',
                'asset.code AS asset_code'
            );

        // Apply department filter if the user is a department head
        if ($userType === 'dept_head') {
            $maintenanceQuery->where('asset.dept_ID', $deptId);
        }

        // Apply search filters
        if ($query) {
            $maintenanceQuery->where(function ($q) use ($query) {
                $q->where('maintenance.id', 'LIKE', "%{$query}%")
                    ->orWhere('users.firstname', 'LIKE', "%{$query}%")
                    ->orWhere('users.middlename', 'LIKE', "%{$query}%")
                    ->orWhere('users.lastname', 'LIKE', "%{$query}%")
                    ->orWhere('maintenance.description', 'LIKE', "%{$query}%")
                    ->orWhere('maintenance.type', 'LIKE', "%{$query}%")
                    ->orWhere('maintenance.cost', 'LIKE', "%{$query}%")
                    ->orWhere('maintenance.reason', 'LIKE', "%{$query}%")
                    ->orWhere('maintenance.status', 'LIKE', "%{$query}%")
                    ->orWhere(DB::raw("DATE_FORMAT(maintenance.requested_at, '%Y-%m-%d')"), 'LIKE', "%{$query}%")
                    ->orWhere(DB::raw("DATE_FORMAT(maintenance.authorized_at, '%Y-%m-%d')"), 'LIKE', "%{$query}%")
                    ->orWhere(DB::raw("DATE_FORMAT(maintenance.completion_date, '%Y-%m-%d')"), 'LIKE', "%{$query}%")
                    ->orWhere('asset.code', 'LIKE', "%{$query}%");
            });
        }

        // Paginate the results
        $requests = $maintenanceQuery->paginate($perPage);
        // Determine which view to return based on user type
        $view = $userType === 'admin' ? 'admin.maintenance' : 'dept_head.maintenance';

        // Return the search results to a view
        return view($view, [
            'requests' => $requests,
            'query' => $query,
            'tab' => $tab,
            'perPage' => $perPage
        ]);
    }
}
