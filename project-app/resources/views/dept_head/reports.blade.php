@extends('layouts.app')

@section('header')
    <h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Asset Reports</h2>
@endsection

@section('content')
<div class="px-6 py-4">
    <div class="flex justify-between items-center mb-4">
        <div class="search-container flex items-center w-full">
            <form action="{{ route('report') }}" method="GET" class="w-full flex">
                <x-search-input class="w-72" />
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-md">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Code</th>
                    <th class="px-6 py-3 text-left">Purchase Date</th>
                    <th class="px-6 py-3 text-left">Lifespan (years)</th>
                    <th class="px-6 py-3 text-left">Salvage Value</th>
                    <th class="px-6 py-3 text-left">Purchase Cost</th>
                    <th class="px-6 py-3 text-left">Depreciation</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Category</th>
                    <th class="px-6 py-3 text-left">Manufacturer</th>
                    <th class="px-6 py-3 text-left">Location</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($assets as $asset)
                    <tr>
                        <td class="px-6 py-4">{{ $asset->name }}</td>
                        <td class="px-6 py-4">{{ $asset->code }}</td>
                        <td class="px-6 py-4">{{ $asset->purchase_date }}</td>
                        <td class="px-6 py-4">{{ $asset->usage_lifespan }}</td>
                        <td class="px-6 py-4">{{ $asset->salvage_value }}</td>
                        <td class="px-6 py-4">{{ $asset->purchase_cost }}</td>
                        <td class="px-6 py-4">{{ $asset->depreciation }}</td>
                        <td class="px-6 py-4">{{ $asset->status }}</td>
                        <td class="px-6 py-4">{{ optional($asset->category)->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ optional($asset->manufacturer)->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ optional($asset->location)->name ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-4">No assets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $assets->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
