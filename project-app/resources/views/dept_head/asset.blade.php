@extends('layouts.app')
    @section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
       Asset  @yield('assetCode')
    </h2>
    @endsection

    @section('content')
    <div class="container">

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
                                        <a href="{{ route('assetDetails' , $asst->code) }}" class="btn btn-outline-primary">view</a>
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
    <nav aria-label="Page navigation example">
        <ul class="pagination">
          <li class="page-item"><a class="page-link" href="#">Previous</a></li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
      </nav>
    @endsection
