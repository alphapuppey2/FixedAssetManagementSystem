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
    <div class="cont">
        <div class="page flex justify-end">
            <div class="paginator w-[40%]">
                {{ $assets->onEachSide(2)->links() }}
            </div>
        </div>
        <table class="table table-hover">
            <thead>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Department</th>
                <th>Depreciation</th>
                <th>Status</th>
                <th></th>
            </thead>
            <tbody id="table-body">
                @if (!$assets->isEmpty())
                    @foreach ($assets as $asset)
                        <tr>
                            <th class="align-middle" scope="col">{{ $asset->code ? $asset->code : 'NONE' }}</th>
                            <td class="align-middle">{{ $asset->name }}</td>
                            <td class="align-middle">{{ $asset->category }}</td>
                            <td class="align-middle">{{ $asset->department }}</td>
                            <td class="align-middle">{{ $asset->depreciation }}</td>
                            <td class="align-middle">{{ $asset->status }}</td>
                            <td class="w-40">
                                <div class="grp flex justify-between">
                                    <a href="{{ route('assetDetails', $asset->id) }}"
                                        class="btn btn-outline-primary">View</a>
                                    <form action="{{ route('asset.delete', $asset->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this asset?');">Delete</button>
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
                            <th class="align-middle" scope="col">${asset.code ? asset.code : 'NONE'}</th>
                            <td class="align-middle">${asset.name}</td>
                            <td class="align-middle">${asset.category}</td>
                            <td class="align-middle">${asset.salvageVal}</td>
                            <td class="align-middle">${asset.depreciation}</td>
                            <td class="align-middle">${asset.status}</td>
                            <td class="w-40">
                                <div class="grp flex justify-between">
                                    <a href="/asset/details/${asset.id}" class="btn btn-outline-primary">View</a>
                                    <form action="/asset/delete/${asset.id}" method="post">
                                        <button type="submit" class="btn btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this asset?');">Delete</button>
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
    </div>
@endsection
