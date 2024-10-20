@extends('layouts.app')

@section('header')
<div class="header flex w-full justify-between pr-3 pl-3 items-center">
    <div class="title">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Activity Logs</h2>
    </div>
    <div class="header-R relative mr-2 flex items-center">
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
    </div>
</div>
@endsection

@section('content')
<div class="w-full px-8 mt-4">
    <div class="flex justify-between mb-4">
        <input 
            type="text" 
            id="search" 
            placeholder="Search logs..." 
            class="border border-gray-300 rounded-lg px-4 py-2 w-1/3"
        />
    </div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="p-2 border">Activity</th>
                    <th class="p-2 border">Description</th>
                    <th class="p-2 border">User Role</th>
                    <th class="p-2 border">User ID</th>
                    <th class="p-2 border">Asset ID</th>
                    <th class="p-2 border">Request ID</th>
                    <th class="p-2 border">Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr class="hover:bg-gray-100 transition">
                        <td class="p-2 border">{{ $log->activity }}</td>
                        <td class="p-2 border">{{ $log->description }}</td>
                        <td class="p-2 border">
                            @switch($log->userType)
                                @case('admin')
                                    Admin
                                    @break
                                @case('dept_head')
                                    Department Head
                                    @break
                                @default
                                    System
                            @endswitch
                        </td>
                        <td class="p-2 border">{{ $log->user_id ?? 'System' }}</td>
                        <td class="p-2 border">{{ $log->asset_id ?? 'N/A' }}</td>
                        <td class="p-2 border">{{ $log->request_id ?? 'N/A' }}</td>
                        <td class="p-2 border">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center p-4">No activity logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links('pagination::tailwind') }}
    </div>
</div>
@endsection
