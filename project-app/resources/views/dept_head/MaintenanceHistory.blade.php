@extends('layouts.app')

@section('header')
    <a href="{{ route('back') }}">
        <h2 class="font-semibold inline-block text-xl text-center text-gray-800 leading-tight flex w-24">
            Asset
        </h2>
    </a>
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
    <div class="relative w-full h-full overflow-hidden bg-white rounded-lg shadow-md">
        <table class="w-full">
            <thead>
                <tr class="bg-blue-100/50 border-b">
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</td>
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">reason of
                        maintenance</td>
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</td>
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</td>
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description
                    </td>
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</td>
                    <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion
                    </td>
                </tr>
            </thead>
            <tbody>

                @if (isset($assetRet) && count($assetRet) > 0)
                    @foreach ($assetRet as $logs)
                        <tr>
                            <td class="text-left px-6 py-3 text-left text-xs font-medium text-slate-900 tracking-wider">{{ $logs->lname.' , '.$logs->fname }}</td>
                            <td class="text-left px-6 py-3 text-left text-xs font-medium text-slate-900 tracking-wider">{{ $logs->reason }}</td>
                            <td class="text-left px-6 py-3 text-left text-xs font-medium text-slate-900 tracking-wider">{{ $logs->type }}</td>
                            <td class="text-left px-6 py-3 text-left text-xs font-medium text-slate-900 tracking-wider">{{ $logs->cost }}</td>
                            <td class="text-left px-6 py-3 text-left text-xs font-medium text-slate-900 tracking-wider">{{ $logs->description }}</td>
                            <td class="text-left px-6 py-3 text-left text-xs font-medium text-slate-900 tracking-wider">{{ str_replace('_', ' ', $logs->status) }}</td>
                            <td class="text-left px-6 py-3 text-left text-xs font-medium text-gray-400 tracking-wider">{{ $logs->complete }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr class="">
                        <td colspan='7'
                            class="text-left px-6 py-3 text-center text-xs font-medium text-gray-400 tracking-wider">
                            No maintenance history</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
