<!-- resources/views/user/assetDetail.blade.php -->
@extends('user.home')

@section('section')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-6">Asset Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Asset Information -->
            <div class="bg-white shadow p-4 rounded-lg">
                <h3 class="text-xl font-semibold mb-4">General Information</h3>
                <p><strong>ID:</strong> {{ $retrieveData->id }}</p>
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
                <p><strong>Created At:</strong> {{ $retrieveData->created_at }}</p>
                <p><strong>Updated At:</strong> {{ $retrieveData->updated_at }}</p>
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

        <!-- Custom Fields -->
        @if($fields)
            <div class="bg-white shadow p-4 rounded-lg mt-6">
                <h3 class="text-xl font-semibold mb-4">Additional Information</h3>
                <ul>
                    @foreach($fields as $key => $value)
                        <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
