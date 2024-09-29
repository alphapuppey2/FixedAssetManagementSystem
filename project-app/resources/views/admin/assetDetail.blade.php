@extends('layouts.app')

@section('header')
    <div class="header flex w-full justify-between pr-3 pl-3 items-center">
        <div class="title">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Asset List
            </h2>
        </div>
        <div class="header-R flex items-center">
            <!-- Department filter dropdown -->
            <form method="GET" action="{{ route('admin.assetList') }}" class="flex space-x-4">
                <select name="dept" class="border border-gray-300 rounded px-2 py-1" onchange="this.form.submit()">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ request('dept') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
@endsection

@section('content')
    <div class="cont">
        <form method="GET" action="{{ route('admin.assetList') }}">
            <!-- Rows per page dropdown -->
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center space-x-2">
                    <label for="perPage">Rows per page: </label>
                    <select name="perPage" id="perPage" class="border border-gray-300 rounded px-2 py-1 w-16" onchange="this.form.submit()">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Department</th>
                <th>Depreciation</th>
                <th>Status</th>
                <th>Action</th>
            </thead>
            <tbody>
                @if (!$assets->isEmpty())
                    @foreach ($assets as $asset)
                        <tr>
                            <td>{{ $asset->code ?? 'NONE' }}</td>
                            <td>{{ $asset->name }}</td>
                            <td>{{ $asset->category }}</td>
                            <td>{{ $asset->department }}</td>
                            <td>{{ $asset->depreciation }}</td>
                            <td>{{ $asset->status }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('assetDetails', $asset->id) }}" class="btn btn-outline-primary">View</a>
                                    <form action="{{ route('asset.delete', $asset->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?');">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="text-center text-gray-800">
                        <td colspan="7">No assets found</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="paginator w-[40%]">
            {{ $assets->appends(['perPage' => $perPage])->links() }}
        </div>
    </div>
@endsection
