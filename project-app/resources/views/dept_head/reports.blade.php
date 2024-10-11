@extends('layouts.app')

@section('content')
<div class="content">
    <h1 class="mb-4">Asset Maintenance Reports</h1>

    <!-- Filters Section -->
    <div class="mb-4">
        <form method="GET" action="{{ route('reports') }}">
            <div class="form-row">
                <div class="col-md-3">
                    <input type="text" name="asset_name" class="form-control" placeholder="Asset Name">
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-control">
                        <option value="">Select Maintenance Type</option>
                        <option value="preventive">Preventive</option>
                        <option value="corrective">Corrective</option>
                        <!-- Add more types as needed -->
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="start_date" class="form-control" placeholder="Start Date">
                </div>
                <div class="col-md-3">
                    <input type="date" name="end_date" class="form-control" placeholder="End Date">
                </div>
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Reports Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Asset Name</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Cost</th>
                    <th>Requested At</th>
                    <th>Authorized At</th>
                    <th>Start Date</th>
                    <th>Completion Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maintenanceReports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ $report->asset_name }}</td>
                        <td>{{ $report->description }}</td>
                        <td>{{ $report->type }}</td>
                        <td>{{ $report->cost }}</td>
                        <td>{{ $report->requested_at }}</td>
                        <td>{{ $report->authorized_at }}</td>
                        <td>{{ $report->start_date }}</td>
                        <td>{{ $report->completion_date }}</td>
                        <td>{{ $report->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-3">
        {{ $maintenanceReports->links() }}
    </div>
</div>
@endsection
