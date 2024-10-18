@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-6">Search Results for "{{ $query }}"</h2>

    @if($users->isNotEmpty())
        <h3 class="text-xl font-semibold mt-4">Users</h3>
        <ul class="list-disc list-inside">
            @foreach ($users as $user)
                <li>{{ $user->firstname }} {{ $user->lastname }} - {{ $user->email }}</li>
            @endforeach
        </ul>
    @endif

    @if($assets->isNotEmpty())
        <h3 class="text-xl font-semibold mt-4">Assets</h3>
        <ul class="list-disc list-inside">
            @foreach ($assets as $asset)
                <li>{{ $asset->name }} ({{ $asset->code }})</li>
            @endforeach
        </ul>
    @endif

    @if($maintenanceRecords->isNotEmpty())
        <h3 class="text-xl font-semibold mt-4">Maintenance Records</h3>
        <ul class="list-disc list-inside">
            @foreach ($maintenanceRecords as $record)
                <li>{{ $record->description }} - {{ $record->type }}</li>
            @endforeach
        </ul>
    @endif

    @if($users->isEmpty() && $assets->isEmpty() && $maintenanceRecords->isEmpty())
        <p class="text-gray-600 mt-6">No results found.</p>
    @endif
</div>
@endsection
