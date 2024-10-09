@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ 'Dashboard' }}
    </h2>
@endsection
@section('content')
    <div class="flex flex-col gap-2 h-full w-full">
        {{-- Cards --}}
        <div class="text-center max-w-100 flex justify-center sm:flex-col md:flex-row gap-2">
            @foreach ($asset as $key => $item)
                <x-cards :title="$key === 'um' ? 'under maintenance' : $key" :counts="$item"></x-cards>
            @endforeach
        </div>
        {{-- Recent Activity --}}
        <div class="container grid grid-cols-[minmax(300px,600px)_1fr] gap-2">
            <div class="chartArea">

                @include('components.chart', ['months' => $Amonths, 'counts' => $Acounts ])
            </div>
            <div class="RecentNew overflow-hidden flex flex-col w-full rounded-md h-full bg-white shadow-md">
                <span class="font-bold capitalize text-lg bg-blue-100 text-center h-8 border-b w-full">
                    New Assets
                </span>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Name</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Code</th>
                                <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Date Acquired</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($newAssetCreated) && !$newAssetCreated->isEmpty())
                           @foreach ($newAssetCreated as $item)
                            <tr>
                                <td class="px-6 py-3 text-center text-sm font-medium text-gray-700">{{ $item->name }}</td>
                                <td class="px-6 py-3 text-center text-sm font-medium text-gray-700">{{ $item->code }}</td>
                                <td class="px-6 py-3 text-center text-sm font-medium text-gray-700">{{ $item->created_at }}</td>
                            </tr>
                           @endforeach

                            @else
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 bg-gray-200/50">No New Assets</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
