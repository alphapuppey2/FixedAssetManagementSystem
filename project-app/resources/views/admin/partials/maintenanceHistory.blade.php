<div class="maintenance flex flex-col justify-center items-center">
    <!-- <div class="header w-full flex justify-center">
        <h1 class="text-xl font-semibold">MAINTENANCE HISTORY</h1>
    </div>
    <div class="divider w-[80%] h-[1px] border-1 border-slate-500 mt-2 mb-4"></div> -->

    <div class="tableContainer w-full overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">User</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Reason</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Cost</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Approved By</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Complete</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Date completed</th>
                </tr>
            </thead>
            <tbody>
                @if ($assetRet && count($assetRet) > 0)
                @foreach ($assetRet as $item)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">{{ isset($item->lname) || isset($item->fname) ? $item->fname . ' ' . $item->lname : "System Generated" }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $item->reason }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($item->cost, 2) }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ isset($item->authorized_fname) || isset($item->authorized_lname) ? $item->authorized_fname . ' ' . $item->authorized_lname : "N/A" }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->complete ? 'Yes' : 'No' }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->complete ? $item->complete:'N/A'}}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="4" class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                        NO MAINTENANCE HISTORY
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
