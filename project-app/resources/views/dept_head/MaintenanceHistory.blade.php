@extends('layouts.app')

@section('header')
    <h2 class="font-semibold inline-block text-xl text-center text-gray-800 leading-tight flex w-24">
        Asset
    </h2>
    <div class="divider">></div>
    <h2 class="inline-block  text-center w-24">
        {{ $asset->assetCode }}

    </h2>
    <div class="divider mr-4">></div>
    <h2 class=" text-center">
        Maintenance History

    </h2>
@endsection

@section('content')
    <div class="relative w-full h-full overflow-hidden rounded-lg">
        <table class="w-full">
            <thead>
                <tr class="bg-blue-100/20 border-b">
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</td>
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">reason of
                        maintenance</td>
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</td>
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</td>
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description
                    </td>
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</td>
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion</td>
                </tr>
            </thead>
            <tbody>
                @if (isset($AssetMaintenance) && count($AssetMaintenance) > 0)
                    @php
                        echo $AssetMaintenance;
                    @endphp
                @else
                    <tr class="bg-blue-100/50">
                        <td colspan='7'
                            class="text-center px-6 py-3 text-left text-xs font-medium text-gray-400/50 uppercase tracking-wider">
                            no maintenance for this asset yet</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
