<div class="flex justify-center items-center h-full">
    <div class="tableContainer w-full overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">User</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Lend Date</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Return Date</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Assigned by</th>
                </tr>
            </thead>
            <tbody>
                @if ($usageLogsAsset && count($usageLogsAsset) > 0)
                @foreach ($usageLogsAsset as $item)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">{{ optional($item->assetUserBy)->lastname ?? 'N/A'}}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $item->date_acquired }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $item->date_returned ?? "N/A"  }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{optional($item->assignedBy)->lastname ?? 'N/A' }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="6" class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                        NO MAINTENANCE HISTORY
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
</div>
