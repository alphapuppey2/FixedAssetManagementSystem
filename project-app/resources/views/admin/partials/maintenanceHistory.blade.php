<div class="maintenance flex flex-col justify-center items-center">
    <!-- <div class="header w-full flex justify-center">
        <h1 class="text-xl font-semibold">MAINTENANCE HISTORY</h1>
    </div>
    <div class="divider w-[80%] h-[1px] border-1 border-slate-500 mt-2 mb-4"></div> -->

    {{-- <div class="tableContainer w-full overflow-x-auto"> --}}
    <div class="tableContainer w-full overflow-x-auto hidden sm:block">
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
                    <td colspan="6" class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                        NO MAINTENANCE HISTORY
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Card View for Smaller Screens -->
    <div class="sm:hidden w-full space-y-2">
        @if ($assetRet && count($assetRet) > 0)
        @foreach ($assetRet as $item)
        <div class="border border-gray-300 rounded-lg p-2 shadow-md">
            <p><strong>User:</strong> {{ isset($item->lname) || isset($item->fname) ? $item->fname . ' ' . $item->lname : "System Generated" }}</p>
            <p><strong>Reason:</strong> {{ $item->reason }}</p>
            <p><strong>Cost:</strong> {{ number_format($item->cost, 2) }}</p>
            <p><strong>Approved By:</strong> {{ isset($item->authorized_fname) || isset($item->authorized_lname) ? $item->authorized_fname . ' ' . $item->authorized_lname : "N/A" }}</p>
            <p><strong>Complete:</strong> {{ $item->complete ? 'Yes' : 'No' }}</p>
            <p><strong>Date Completed:</strong> {{ $item->complete ? $item->complete : 'N/A' }}</p>
        </div>
        @endforeach
        @else
        <div class="border border-gray-300 rounded-lg p-4 shadow-md text-center text-gray-500">
            NO MAINTENANCE HISTORY
        </div>
        @endif
    </div>
</div>
