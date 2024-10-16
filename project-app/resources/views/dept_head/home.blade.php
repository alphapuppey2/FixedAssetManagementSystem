@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center md:text-left">
        {{ 'Dashboard' }}
    </h2>
@endsection

@section('content')
    <div class="flex flex-col gap-4 h-full w-full">
        {{-- Cards Section --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 p-2">
            @foreach ($asset as $key => $item)
                <x-cards :title="$key === 'under_maintenance' ? 'under maintenance' : $key" :counts="$item" class="text-center"  />
            @endforeach
        </div>

        {{-- Recent Activity Section --}}
        <div class="container grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_500px] gap-4 p-2">
            {{-- Chart Area --}}
            <div class="chartArea bg-white rounded-xl shadow-md p-4">
                <x-chart 
                    :weeks="$Amonths" 
                    :activeCounts="$Acounts" 
                    :maintenanceCounts="$UMcounts" 
                />
            </div>

            {{-- Recent New Assets Table --}}
            <div class="RecentNew flex flex-col w-full bg-white rounded-md shadow-md overflow-hidden">
                <span class="font-bold uppercase text-lg bg-blue-100 text-center py-2 border-b">
                    New Assets
                </span>
                <div class="overflow-auto max-h-80">
                    <table class="min-w-full">
                        <thead class="border-b">
                            <tr>
                                <th class="text-center text-sm font-medium text-gray-700 px-4 py-2">Name</th>
                                <th class="text-center text-sm font-medium text-gray-700 px-4 py-2">Code</th>
                                <th class="text-center text-sm font-medium text-gray-700 px-4 py-2">Date Acquired</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($newAssetCreated) && !$newAssetCreated->isEmpty())
                                @foreach ($newAssetCreated as $item)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="text-center text-sm font-medium text-gray-700 px-4 py-2">{{ $item->name }}</td>
                                        <td class="text-center text-sm font-medium text-gray-700 px-4 py-2">{{ $item->code }}</td>
                                        <td class="text-center text-sm font-medium text-gray-700 px-4 py-2">{{ $item->created_at }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center text-gray-500 bg-gray-200/50 px-4 py-2">
                                        No New Assets
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
