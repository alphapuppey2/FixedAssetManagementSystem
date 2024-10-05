@extends('layouts.app')
@section('header')
    <div class="header flex w-full justify-between pr-3 pl-3 items-center">
        <div class="title">
            <a href="{{ asset('asset') }}">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Asset
                </h2>
            </a>
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

    <div id="dataModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>No data available!</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="ccAL relative flex flex-col bg-white border rounded-lg w-full h-full overflow-hidden p-[2px]">
        <div class="tableContainer overflow-auto rounded-md h-full w-full">
            <table class="w-full">
                <thead class="p-5 bg-gray-100 border-b">
                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        code</th>
                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        name</th>
                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        category</th>
                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Salvage Value</th>
                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Depreciation</th>
                    <th class="py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        status</th>
                    <th class="px-6 py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    </th>
                </thead>
                <tbody id="table-body">
                    @if (!$asset->isEmpty())
                        @foreach ($asset as $asst)
                            <tr>
                                <th class="align-middle text-center text-sm text-gray-900  " scope="col">
                                    {{ $asst->code ? $asst->code : 'NONE' }}</th>
                                <td class="align-middle text-center text-sm text-gray-900 py-2 text-balance">
                                    {{ $asst->name }}
                                </td>
                                <td class="align-middle text-center text-sm text-gray-900 py-2 ">{{ $asst->category }}
                                </td>
                                <td class="align-middle text-center text-sm text-gray-900 py-2 ">
                                    {{ $asst->salvageVal }}</td>
                                <td class="align-middle text-center text-sm text-gray-900 py-2 ">
                                    {{ $asst->depreciation }}</td>
                                <td class="align-middle text-center text-sm text-gray-900 py-2 ">{{ $asst->status }}
                                </td>
                                <td class="w-40">
                                    <div class="grp flex gap-2 justify-center">
                                        <a href="{{ route('assetDetails', $asst->id) }}"
                                            class="btn btn-outline-primary py-[2px] px-2">view</a>
                                        <form action="{{ route('asset.delete', $asst->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger py-[2px] px-2"
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
        <div class="page flex justify-between px-4 py-3">
            <div class="paginator">
                @if ($asset instanceof \Illuminate\Pagination\LengthAwarePaginator || $asset instanceof \Illuminate\Pagination\Paginator)
                    <div class="">
                        <!-- Number of Items Loaded -->
                        <div class="text-gray-600">
                            Showing <span class="font-semibold">{{ $asset->firstItem() }}</span> to <span
                                class="font-semibold">{{ $asset->lastItem() }}</span> of <span
                                class="font-semibold">{{ $asset->total() }}</span> items
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
    </div>
    @if (session('success'))
        <div id="toast" class="absolute bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('success') }}
        </div>
    @endif
    @vite(['resources/js/flashNotification.js'])
    <script>
        <!-- Modal HTML
        -->
    <div id="dataModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>No data available!</p>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function fetchData() {
            // Assuming you're using vanilla JavaScript
            fetch('/check-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token for Laravel
                    },
                    body: JSON.stringify({
                        id: 1 // You can send the required parameters here
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'no_data') {
                        // Show modal if no data
                        openModal();
                    } else if (data.status === 'has_data') {
                        // Redirect to create form page if data exists
                        window.location.href = '/create-form';
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Function to open modal
        function openModal() {
            document.getElementById('dataModal').style.display = 'block';
        }

        // Function to close modal
        function closeModal() {
            document.getElementById('dataModal').style.display = 'none';
        }

        // Trigger fetchData on page load or specific action
        window.onload = fetchData;


        document.getElementById('searchFilt').addEventListener('keyup', function() {
            let query = this.value;

            fetch(`/asset/search/row?search=${query}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    let tableBody = document.getElementById('table-body');
                    tableBody.innerHTML = ''; // Clear current table rows

                    if (data.length === 0) {
                        // Display "Asset not found" if no results are found
                        let noResultsRow = `
    <tr class="text-center text-gray-800">
        <td colspan="7" style="color: rgb(177, 177, 177)">Asset not found</td>
    </tr>
    `;
                        tableBody.innerHTML = noResultsRow;
                    } else {
                        // Populate new table rows based on the search results
                        data.forEach(asset => {
                            let row = `
    <tr>
        <th class="align-middle" scope="col">${asset.code ? asset.code : 'NONE'}</th>
        <td class="align-middle">${asset.name}</td>
        <td class="align-middle">${asset.category}</td>
        <td class="align-middle">${asset.salvageVal}</td>
        <td class="align-middle">${asset.depreciation}</td>
        <td class="align-middle">${asset.status}</td>
        <td class="w-40">
            <div class="grp flex justify-between">
                <a href="/assetDetails/${asset.id}" class="btn btn-outline-primary py-[2px] px-2">view</a>
                <form action="/asset/delete/${asset.id}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-outline-danger py-[2px] px-2"
                        onclick="return confirm('Are you sure you want to delete this asset?');">delete</button>
                </form>
            </div>
        </td>
    </tr>
    `;
                            tableBody.innerHTML += row;
                        });
                    }
                })
                .catch(error => console.log('Error:', error));
        });
    </script>

@endsection
