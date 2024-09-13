{{-- Dynamically extend the correct layout based on user --}}
@if (Auth::user()->user == 'dept_head')
    @extends('layouts.dept_head') <!-- Department head layout -->
@elseif (Auth::user()->user == 'user')
    @extends('layouts.user') <!-- Regular user layout -->
@else
    @extends('layouts.app') <!-- Default or fallback layout -->
@endif

@section('image-path', asset('storage/' . ($retrieveData->image ?? 'default.png')))

@section('main-details')
<div class="bg-white shadow p-4 rounded-lg">
    <h3 class="text-xl font-semibold mb-4">General Information</h3>
    <p><strong>Code:</strong> {{ $retrieveData->code }}</p>
    <p><strong>Name:</strong> {{ $retrieveData->name }}</p>
    <p><strong>Status:</strong> {{ $retrieveData->status }}</p>
    <p><strong>Category:</strong> {{ $retrieveData->category }}</p>
    <p><strong>Department:</strong> {{ $retrieveData->department }}</p>
    <p><strong>Manufacturer:</strong> {{ $retrieveData->manufacturer }}</p>
    <p><strong>Model:</strong> {{ $retrieveData->model }}</p>
    <p><strong>Location:</strong> {{ $retrieveData->location }}</p>
    <p><strong>Cost:</strong> {{ $retrieveData->cost }}</p>
    <p><strong>Depreciation:</strong> {{ $retrieveData->depreciation }}</p>
    <p><strong>Salvage Value:</strong> {{ $retrieveData->salvageVal }}</p>
    <p><strong>Usage Lifespan:</strong> {{ $retrieveData->usage_Lifespan }}</p>
</div>
@endsection

@section('additional-info')
<div class="addInfoContainer grid grid-rows-5 grid-flow-col w-full">
    @if ($fields && count($fields) > 0)
        @foreach ($fields as $key => $value)
            <div class="extraInfo grid grid-cols-2 lg:grid-cols-[minmax(20%,50px)_20%] gap-2">
                <div class="field-Info customField uppercase text-slate-400">{{ $key }}</div>
                <div class="field-Info customField">{{ $value }}</div>
            </div>
        @endforeach
    @else
        <div class="noneField">
            No Additional Information
        </div>
    @endif
</div>
@endsection

@section('content')
<!-- Repair Request Section -->
<div class="mt-6">
    <button id="requestRepairButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Request Repair
    </button>
</div>

<!-- Repair Request Form (Initially Hidden) -->
<div id="repairRequestForm" class="bg-white shadow p-4 rounded-lg mt-6" style="display: none;">
    <h3 class="text-xl font-semibold mb-4">Reason for Repair</h3>
    <form action="{{ route('repair.request') }}" method="POST">
        @csrf
        <input type="hidden" name="asset_id" value="{{ $retrieveData->id }}">

        <div class="mb-4">
            <label for="issue_description" class="block text-sm font-medium text-gray-700">Describe the issue</label>
            <textarea id="issue_description" name="issue_description" rows="4" class="mt-1 block w-full p-2 border rounded-md shadow-sm" placeholder="Enter the issue details"></textarea>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Submit Request</button>
    </form>
</div>

<!-- JavaScript to Toggle Repair Form -->
<script>
    document.getElementById('requestRepairButton').addEventListener('click', function () {
        var repairForm = document.getElementById('repairRequestForm');
        repairForm.style.display = (repairForm.style.display === 'none') ? 'block' : 'none';
    });
</script>
@endsection
