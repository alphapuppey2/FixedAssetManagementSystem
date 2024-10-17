@extends('layouts.app')

@php
$data = $retrieveData ?? null;
$imagePath = $data->asst_img ? 'storage/' . $data->asst_img : 'images/no-image.png';
$qrCodePath = $data->qr_img ? 'storage/' . $data->qr_img : 'images/defaultQR.png';
@endphp

@section('header')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="flex items-center justify-between w-full">
    <div class="flex items-center space-x-2">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex">
            <a href="{{ route('back') }}">Asset</a>
            <div class="direct ml-2">></div>
        </h2>
        <h2 class="assetID font-semibold text-xl">{{ $data->code ?? 'No Code' }}</h2>
    </div>
</div>
@endsection

@section('content')
<div class="w-full h-full">
    {{-- Tabs --}}
    <div class="tabs-container border-b-2 mb-4 flex space-x-4">
        <button
            class="tab-btn pb-2 border-b-2 transition-all duration-300 ease-in-out border-transparent"
            data-target="#generalInfo">
            General Information
        </button>
        <button
            class="tab-btn pb-2 border-b-2 transition-all duration-300 ease-in-out border-transparent"
            data-target="#maintenanceHistory">
            Maintenance History
        </button>
        <button
            class="tab-btn pb-2 border-b-2 transition-all duration-300 ease-in-out border-transparent"
            data-target="#usageLog">
            Usage Log
        </button>
    </div>

    {{-- Tab Content --}}
    <div id="generalInfo" class="tab-content">
        @include('admin.partials.generalInfo', [
        'data' => $data,
        'categories' => $categories,
        'model' => $model,
        'manufacturer' => $manufacturer,
        'location' => $location,
        'status' => $status
        ])
    </div>

    <div id="maintenanceHistory" class="tab-content hidden">
        @include('admin.partials.maintenanceHistory', ['assetRet' => $assetRet])
    </div>

    <div id="usageLog" class="tab-content hidden">
        @include('admin.partials.usageLog')
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        // Set the first tab as active on load
        tabButtons[0].classList.add('border-blue-500', 'text-blue-600');
        tabButtons[0].classList.remove('border-transparent');
        tabContents[0].classList.remove('hidden');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Reset all tabs to inactive state
                tabButtons.forEach(btn => {
                    btn.classList.remove('border-blue-500', 'text-blue-600');
                    btn.classList.add('border-transparent');
                });

                // Hide all content
                tabContents.forEach(content => content.classList.add('hidden'));

                // Activate the clicked tab
                button.classList.add('border-blue-500', 'text-blue-600');
                button.classList.remove('border-transparent');

                // Show corresponding content
                const target = document.querySelector(button.dataset.target);
                target.classList.remove('hidden');
            });
        });
    });
</script>
@endsection