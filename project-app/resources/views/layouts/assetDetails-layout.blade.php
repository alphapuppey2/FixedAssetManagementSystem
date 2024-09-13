<!-- resources/views/layouts/assetDetails-layout.blade.php -->

@extends('layouts.app')

@section('title', 'Asset Details')

@section('content')
<div id="asset-details">
    {{-- Image and QR Code Section --}}
    <div class="imgContainer w-[100%] pb-4 flex justify-center items-center md:col-span-2">
        <div class="imagepart overflow-hidden relative p-3">
            <div class="imageField w-32 h-32 relative flex justify-center">
                <div class="field-Info w-32 h-32 border-3 rounded-md transition ease-in ease-out">
                    <img src="@yield('image-path')" id="imageviewOnly"
                        class="absolute top-1/2 left-1/2 w-auto h-full transform -translate-x-1/2 -translate-y-1/2 object-cover"
                        alt="default">
                </div>
            </div>
        </div>

        {{-- QR Code --}}
        <div class="qrContainer flex flex-col items-center">
            <div class="QRBOX w-24 h-24 bg-red-300"></div>
            <a href="#" target="_blank" rel="noopener noreferrer">Print QR Code</a>
        </div>
    </div>

    {{-- Main Asset Details Section --}}
    <div class="leftC">
        <div class="mainDetail lg:grid lg:grid-rows-6 max-sm:grid-cols-1 grid-flow-col gap-2">
            @yield('main-details')
        </div>

        {{-- Additional Information --}}
        <div class="MoreInfo">
            <div class="addInformation">
                <div class="title font-bold m-2 text-[15px] opacity-50 uppercase">
                    Additional information
                    <div class="divider w-20 h-[2px] bg-slate-400 opacity-50 mb-2 mt-2"></div>
                </div>
                <div class="addInfoContainer grid grid-rows-5 grid-flow-col w-full">
                    @yield('additional-info')
                </div>
            </div>
        </div>
    </div>

    {{-- Maintenance History --}}
    <div class="rightC flex flex-col">
        <div class="maintenance flex flex-col justify-center items-center">
            <div class="header w-full flex justify-between">
                <h1>Maintenance History</h1>
                <a href="#" class="text-[12px] text-blue-500">VIEW ALL</a>
            </div>
            <div class="divider w-full h-[1px] border-1 border-slate-500 mt-2 mb-2"></div>
            <table class="w-full">
                <thead>
                    <th>Work Description</th>
                    <th>Date</th>
                    <th>Date Completed</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <tr>
                        <td colspan='4' class="text-center">NO MAINTENANCE HISTORY</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
