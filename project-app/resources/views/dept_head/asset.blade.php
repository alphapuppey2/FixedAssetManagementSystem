@extends('layouts.app')
@section('header')
    <div class="header flex w-full justify-between pr-3 pl-3 items-center">
        <div class="title">
            <a href="{{asset('asset')}}">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Asset
                </h2>
            </a>
        </div>
        <div class="header-R flex items-center">
            <button id="openModalBtn">
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
                        <!-- <div class="text-gray-600">
                            Showing <span class="font-semibold">{{ $asset->firstItem() }}</span> to <span
                                class="font-semibold">{{ $asset->lastItem() }}</span> of <span
                                class="font-semibold">{{ $asset->total() }}</span> items
                        </div> -->

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

    @include('dept_head.modal.modalImportAsset')

    @if (session('success'))
        <div id="toast" class="absolute bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('success') }}
        </div>
    @endif
    @vite(['resources/js/flashNotification.js'])
    <script>
        // Function to open the modal
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
        }

        // Function to close the modal
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
        }

        // Function to handle modal close when clicking outside the modal
        function closeModalOnClickOutside(modalId, event) {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        }

        // Function to handle asset search
        function handleSearch(inputId, tableBodyId) {
            const input = document.getElementById(inputId);
            const tableBody = document.getElementById(tableBodyId);

            input.addEventListener('keyup', function () {
                const query = input.value;

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
                    tableBody.innerHTML = ''; // Clear current table rows

                    if (data.length === 0) {
                        const noResultsRow = `
                            <tr class="text-center text-gray-800">
                                <td colspan="7" style="color: rgb(177, 177, 177)">Asset not found</td>
                            </tr>
                        `;
                        tableBody.innerHTML = noResultsRow;
                    } else {
                        data.forEach(asset => {
                            const row = `
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
                                                <button type="submit" class="btn btn-outline-danger py-[2px] px-2" onclick="return confirm('Are you sure you want to delete this asset?');">delete</button>
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
        }

        // DOMContentLoaded event to initialize all event listeners
        document.addEventListener('DOMContentLoaded', function () {
            const modalId = 'importModal';
            
            // Modal open and close event listeners
            document.getElementById('openModalBtn').addEventListener('click', () => openModal(modalId));
            document.getElementById('closeModalBtn').addEventListener('click', () => closeModal(modalId));
            window.addEventListener('click', (e) => closeModalOnClickOutside(modalId, e));

            // Initialize search functionality
            handleSearch('searchFilt', 'table-body');
        });
    </script>


@endsection
