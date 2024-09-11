@extends('user.home')

@section('section')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-6">Asset Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Asset Information -->
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

            <!-- Asset Image -->
            <div class="bg-white shadow p-4 rounded-lg">
                <h3 class="text-xl font-semibold mb-4">Asset Image</h3>
                @if($retrieveData->image)
                    <img src="{{ asset('storage/' . $retrieveData->image) }}" alt="{{ $retrieveData->name }}" class="w-full h-auto rounded-lg">
                @else
                    <p>No Image Available</p>
                @endif
            </div>
        </div>

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
    </div>
@endsection
