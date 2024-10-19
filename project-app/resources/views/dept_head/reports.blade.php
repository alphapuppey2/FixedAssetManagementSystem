@extends('layouts.app')

@section('header')
    <h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Asset Reports</h2>
@endsection

@section('content')
<div class="px-6 py-4">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center w-1/2">
            <form action="" method="GET" class="w-full flex">
                <input type="text" name="query" placeholder="Search assets..." value="{{ request('query') }}"
                    class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </form>
        </div>

        <div class="flex items-center space-x-4">
            <a href="#" onclick="showAssetFilterModal()"
               class="px-3 py-1 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Customs
            </a>
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
    </div>
</div>

<!-- Include the custom filter modal -->
@include('dept_head.modal.assetCustomFilter')

@endsection

@section('scripts')
<script>
    function showAssetFilterModal() {
        document.getElementById('assetFilterModal').classList.remove('hidden');
    }

    function hideAssetFilterModal() {
        document.getElementById('assetFilterModal').classList.add('hidden');
    }

    function populateTable(data) {
    const tableBody = document.querySelector('tbody');
    tableBody.innerHTML = ''; // Clear existing rows

    data.forEach(asset => {
        const row = document.createElement('tr');
        Object.values(asset).forEach(value => {
            const cell = document.createElement('td');
            cell.textContent = value || 'N/A'; // Handle null values
            row.appendChild(cell);
        });
        tableBody.appendChild(row);
    });
}

</script>

@endsection
