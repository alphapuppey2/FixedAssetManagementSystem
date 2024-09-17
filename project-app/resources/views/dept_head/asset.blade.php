@extends('layouts.app')
@section('header')
    <div class="header flex w-full justify-between pr-3 pl-3 items-center">
        <div class="title">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Asset
            </h2>
        </div>
        <div class="header-R flex items-center">
            <button>
                <span>
                    <x-icons.importIcon />
                </span>
            </button>
            <button>
                <span>
                    <x-icons.exportIcon />
                </span>
            </button>

            <div class="searchBox">
                <x-text-input name="search" id="searchFilt" placeholder="Search" />
            </div>
        </div>

    </div>
@endsection

@section('content')
        <div class="ccAL relative flex flex-col h-full">
            <div class="page flex justify-between py-2">
                <div class="paginator">
                    @if($asset instanceof \Illuminate\Pagination\LengthAwarePaginator || $asset instanceof \Illuminate\Pagination\Paginator)
                <div class="">
                    <!-- Number of Items Loaded -->
                    <div class="text-gray-600">
                        Showing <span class="font-semibold">{{ $asset->firstItem() }}</span> to <span class="font-semibold">{{ $asset->lastItem() }}</span> of <span class="font-semibold">{{ $asset->total() }}</span> items
                    </div>

                    <!-- Pagination Buttons -->
                    <div class="">
                        <div class="text-gray-500">
                            {{ $asset->appends(['query' => request()->query('query')])->links() }}
                        </div>
                    </div>
                </div>
            @endif
                </div>
            </div>
            <div class="tableContaine bg-white border h-full rounded-md overflow-hidden">
                <table class="w-full">
                    <thead class="p-3 bg-gray-100 border-b">
                        <th class="px-6 py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            code</th>
                        <th class="px-6 py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            name</th>
                        <th class="px-6 py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            category</th>
                        <th class="px-6 py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Salvage Value</th>
                        <th class="px-6 py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Depreciation</th>
                        <th class="px-6 py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            status</th>
                        <th class="px-6 py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        </th>
                    </thead>
                    <tbody>
                        @if (!$asset->isEmpty())
                            @foreach ($asset as $asst)
                                <tr class="border-b">
                                    <th class="align-middle text-center text-sm text-gray-900 px-6 py-3 " scope="col">
                                        {{ $asst->code ? $asst->code : 'NONE' }}</th>
                                    <td class="align-middle text-center text-sm text-gray-900 px-6 py-3 text-balance">{{ $asst->name }}
                                    </td>
                                    <td class="align-middle text-center text-sm text-gray-900 px-6 py-3 ">{{ $asst->category }}
                                    </td>
                                    <td class="align-middle text-center text-sm text-gray-900 px-6 py-3 ">
                                        {{ $asst->salvageVal }}</td>
                                    <td class="align-middle text-center text-sm text-gray-900 px-6 py-3 ">
                                        {{ $asst->depreciation }}</td>
                                    <td class="align-middle text-center text-sm text-gray-900 px-6 py-3 ">{{ $asst->status }}
                                    </td>
                                    <td class="w-40">
                                        <div class="grp flex gap-2 justify-center">
                                            <a href="{{ route('assetDetails', $asst->id) }}"
                                                class="btn btn-outline-primary">view</a>
                                            <form action="{{ route('asset.delete', $asst->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this asset?');">delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="text-center text-gray-800">
                                <td colspan='7' style="color: rgb(177, 177, 177)">No List</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @if (session('success'))
            <div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
                {{ session('success') }}
            </div>
        @endif
        @vite(['resources/js/flashNotification.js'])
        <script>
            document.getElementById('searchFilt').addEventListener('keyup', function() {
                let query = this.value;

                fetch(`/assets/search?search=${query}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        let tableBody = document.getElementById('table-body');
                        tableBody.innerHTML = ''; // Clear current table rows

                        // Populate new table rows based on the search results
                        data.forEach(asset => {
                            let row = `<tr>
                <th class="align-middle" scope="col">{{ $asst->code ? $asst->code : 'NONE' }}</th>
                            <td class="align-middle">{{ $asst->name }}</td>
                            <td class="align-middle">{{ $asst->category }}</td>
                            <td class="align-middle">{{ $asst->salvageVal }}</td>
                            <td class="align-middle">{{ $asst->depreciation }}</td>
                            <td class="align-middle">{{ $asst->status }}</td>
                            <td class="w-40">
                                <div class="grp flex justify-between">
                                    <a href="{{ route('assetDetails', $asst->id) }}"
                                        class="btn btn-outline-primary">view</a>
                                    <form action="{{ route('asset.delete', $asst->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this asset?');">delete</button>
                                    </form>
                                </div>
                            </td>
            </tr>`;
                            tableBody.innerHTML += row;
                        });
                    })
                    .catch(error => console.log('Error:', error));
            });
        </script>
    @endsection
