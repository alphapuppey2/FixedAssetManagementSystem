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
                <x-text-input placeholder="Search" />
            </div>
         </div>

    </div>
    @endsection

    @section('content')
    <div class="cont">
        <table class="table table-hover bg-red-500">
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
                        <a class="cursor-pointer bg-red-500 w-screen h-4">
                            <tr>
                                <th scope="col">{{ $asst->code ? $asst->code : 'NONE' }}</th>
                                <td>{{ $asst->name }}</td>
                                <td>{{ $asst->category }}</td>
                                <td>{{ $asst->salvageVal }}</td>
                                <td>{{ $asst->depreciation }}</td>
                                <td>{{ $asst->status }}</td>
                                <td class=" w-40">
                                    <div class="grp flex justify-between">
                                        <a href="{{ route('assetDetails' , $asst->id) }}" class="btn btn-outline-primary">view</a>
                                        <x-danger-button class="btn-outline-danger">delete</x-danger-button>
                                    </div>
                                </td>
                            </tr>
                        </a>
                @endforeach
            @else
                <tr class="text-center text-gray-800">
                    <td colspan='6' style="color: rgb(177, 177, 177)" >No List</td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class="page flex justify-end">
            <div class="paginator w-[40%]">
                {{ $asset->onEachSide(2)->links() }}
            </div>
        </div>
    @endsection
