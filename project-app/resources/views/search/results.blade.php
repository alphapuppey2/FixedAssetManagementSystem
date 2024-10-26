@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-3xl font-bold mb-8 text-center">Search Results for "{{ $query }}"</h2>

    <div class="grid gap-8">
        @if (Auth::user()->usertype === 'admin')
            <!-- Users Section -->
            <div class="bg-white shadow-md rounded-lg p-6 border">
                <div class="flex items-center mb-4">
                <x-icons.user-list-icon />
                    <h3 class="text-xl font-semibold text-gray-700">Users</h3>
                </div>
                @if($users->isEmpty())
                    <p class="text-gray-500">No users found.</p>
                @else
                    <ul class="list-none space-y-2">
                        @foreach ($users as $user)
                            <li>
                                <a href="{{ route('searchUsers', ['query' => $user->firstname]) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition font-medium">
                                    {{ $user->firstname }} {{ $user->lastname }} - {{ $user->email }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <!-- Assets Section -->
        <div class="bg-white shadow-md rounded-lg p-6 border">
            <div class="flex items-center mb-4">
                <x-icons.receipticon />
                <h3 class="text-xl font-semibold text-gray-700">Assets</h3>
            </div>
            @if($assets->isEmpty())
                <p class="text-gray-500">No assets found.</p>
            @else
                <ul class="list-none space-y-2">
                    @foreach ($assets as $asset)
                        <li>
                            <a href="{{ Auth::user()->usertype === 'admin' 
                                        ? route('adminAssetDetails', $asset->code) 
                                        : route('assetDetails', $asset->code) }}" 
                               class="text-green-600 hover:text-green-800 transition font-medium">
                                {{ $asset->name }} ({{ $asset->code }})
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Maintenance Section -->
        <div class="bg-white shadow-md rounded-lg p-6 border">
            <div class="flex items-center mb-4">
                <x-icons.wrench-icon />
                <h3 class="text-xl font-semibold text-gray-700">Maintenance</h3>
            </div>
            @if($request->isEmpty())
                <p class="text-gray-500">No maintenance records found.</p>
            @else
                <ul class="list-none space-y-2">
                    <li>
                        <a href="{{ Auth::user()->usertype === 'admin' 
                                    ? route('adminMaintenance') 
                                    : route('maintenance') }}" 
                           class="text-yellow-600 hover:text-yellow-800 transition font-medium">
                            View Maintenance Requests
                        </a>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
