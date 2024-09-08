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
                {{ $asset->onEachSide(2)->links() }}
            </div>
        </div>
        <table class="table table-hover">
            <thead>
                <th>code</th>
                <th>name</th>
                <th>category</th>
                <th>Salvage Value</th>
                <th>Depreciation</th>
                <th>status</th>
                <th></th>
            </thead>
            <tbody>
                @if (!$asset->isEmpty())
                @foreach ($asset as $asst )

                            <tr>
                                <th class="align-middle" scope="col">{{ $asst->code ? $asst->code : 'NONE' }}</th>
                                <td class="align-middle">{{ $asst->category }}</td>
                                <td class="align-middle">{{ $asst->name }}</td>
                                <td class="align-middle">{{ $asst->salvageVal }}</td>
                                <td class="align-middle">{{ $asst->depreciation }}</td>
                                <td class="align-middle">{{ $asst->status }}</td>
                                <td class="w-40">
                                    <div class="grp flex justify-between">
                                        <a href="{{ route('assetDetails' , $asst->id) }}" class="btn btn-outline-primary">view</a>
                                        <form action="{{ route('asset.delete', $asst->id) }}"    method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this asset?');">delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                @endforeach
            @else
                <tr class="text-center text-gray-800">
                    <td colspan='7' style="color: rgb(177, 177, 177)" >No List</td>
                </tr>
            @endif
            </tbody>
        </table>
        @if(session('success'))
        <div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('success') }}
        </div>
    @endif
        @vite(['resources/js/flashNotification.js'])
    @endsection
