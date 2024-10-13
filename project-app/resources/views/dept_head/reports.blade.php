@extends('layouts.app')

@section('header')
    <h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Asset Reports</h2>
@endsection

@section('content')
<div class="px-6 py-4">
    <!-- Top Section -->
    <div class="flex justify-between items-center mb-4">
        <!-- Search Bar -->
        <div class="flex items-center w-1/2">
            <form action="" method="GET" class="w-full flex">
                <input type="hidden" name="tab" value="{{ request('tab', 'assets') }}">
                <input type="text" name="query" placeholder="Search assets..." value="{{ request('query') }}"
                    class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </form>
        </div>

        <!-- Right Section: Download Icon and Create Button -->
        <div class="flex items-center space-x-4">
            <a href="#" class="p-2 text-black hover:text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                </svg>
            </a>
            <a href="#" class="px-3 py-1 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Custom Report
            </a>
        </div>
    </div>

    <div class="flex justify-between items-center mb-4">
        <!-- Rows per page dropdown -->
        <div class="flex items-center">
            <form action="" method="GET" class="flex items-center space-x-2">
                <input type="hidden" name="tab" value="{{ request('tab', 'assets') }}">
                <input type="hidden" name="query" value="{{ request('query') }}">

                <label for="rows_per_page" class="text-gray-700">Rows per page:</label>

                <div class="relative">
                    <select id="rows_per_page" name="rows_per_page"
                        class="appearance-none w-16 border border-gray-300 rounded-md px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer"
                        onchange="this.form.submit()">
                        <option value="10" {{ request('rows_per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('rows_per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('rows_per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Pagination -->
        <div class="ml-auto">
            <nav aria-label="Page navigation">
                <ul class="inline-flex -space-x-px">
                    <li>
                        <a href="{{ request()->fullUrlWithQuery(['page' => max(request('page', 1) - 1, 1)]) }}"
                           class="px-3 py-2 border rounded-l-md hover:bg-gray-100">
                            Previous
                        </a>
                    </li>
                    @for ($i = 1; $i <= 3; $i++)
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}"
                               class="px-3 py-2 border hover:bg-gray-100">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor
                    <li>
                        <a href="{{ request()->fullUrlWithQuery(['page' => request('page', 1) + 1]) }}"
                           class="px-3 py-2 border rounded-r-md hover:bg-gray-100">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Asset Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-md">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lifespan (years)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salvage Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Cost</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Depreciation</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manufacturer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($assets as $asset)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $asset->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $asset->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $asset->purchase_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $asset->usage_lifespan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $asset->salvageVal }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $asset->cost }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $asset->depreciation }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $asset->status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $asset->category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $asset->manufacturer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $asset->location->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-4 text-gray-500">No assets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
