<style>
    .info:nth-child(even) {
        background-color: rgb(243, 242, 242);
    }

    .info:nth-child(odd) {
        background-color: rgb(255, 255, 255);
    }
</style>

<form id="formEdit" action="{{ route('assetDetails.edit', $data->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="flex justify-end space-x-4 mt-4">
        <!-- Dispose Button -->
        @if ($data->status !== 'disposed')
            <button id="disposeBTN" type="button"
                class="px-4 py-2 bg-red-500 text-white font-semibold rounded-md shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 transition-all duration-300"
                onclick="openDisposeModal()">
                DISPOSE
            </button>
        @endif
        <button id="editBTN" type="button"
            class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-all duration-300">
            EDIT
        </button>
        <button id="saveBTN" type="submit" form="formEdit"
            class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-green-300 transition-all duration-300 hidden">
            SAVE
        </button>
        <button id="cancelBTN" type="button"
            class="px-4 py-2 bg-red-500 text-white font-semibold rounded-md shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 transition-all duration-300 hidden">
            CANCEL
        </button>
    </div>

    {{-- Grid Layout --}}
    <div class="grid grid-cols-1 md:grid-cols-3 sm:gap-8 p-6">
        {{-- Asset Details Section --}}
        <div class="col-span-2 space-y-6">
            <div class="info flex items-center p-4">
                <label
                    class="field-label mr-4 w-32 text-xs sm:text-sm md:text-base text-gray-600 font-semibold">Name:</label>
                <div class="field-Info font-semibold view-only text-xs sm:text-sm md:text-base">{{ $data->name }}
                </div>
                <x-text-input name="name" class="edit hidden w-full border-gray-300 text-xs sm:text-sm md:text-base"
                    value="{{ $data->name }}" />
            </div>

            <div class="info flex items-center p-4">
                <label
                    class="field-label mr-4 w-32 text-xs sm:text-sm md:text-base text-gray-600 font-semibold">Category:</label>
                <div class="field-Info font-semibold view-only text-xs sm:text-sm md:text-base">{{ $data->category }}
                </div>
                <select name="category" class="edit hidden w-full border-gray-300 text-xs sm:text-sm md:text-base">
                    @foreach ($categories['ctglist'] as $category)
                        <option value="{{ $category->id }}" @selected($data->category == $category->name)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="info flex items-center p-4 ">
                <label
                    class="field-label mr-4 w-32 text-xs sm:text-sm md:text-base text-gray-600 font-semibold">Model:</label>
                <div class="field-Info font-semibold view-only text-xs sm:text-sm md:text-base">{{ $data->model }}
                </div>
                <select name="mod" class="edit hidden w-full border-gray-300 text-xs sm:text-sm md:text-base">
                    @foreach ($model['mod'] as $model)
                        <option value="{{ $model->id }}" @selected($data->model == $model->name)>
                            {{ $model->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="info flex items-center p-4 ">
                <label
                    class="field-label mr-4 w-32 text-xs sm:text-sm md:text-base text-gray-600 font-semibold">Manufacturer:</label>
                <div class="field-Info font-semibold view-only text-xs sm:text-sm md:text-base">
                    {{ $data->manufacturer }}</div>
                <select name="mcft" class="edit hidden w-full border-gray-300 text-xs sm:text-sm md:text-base">
                    @foreach ($manufacturer['mcft'] as $manufacturer)
                        <option value="{{ $manufacturer->id }}" @selected($data->manufacturer == $manufacturer->name)>
                            {{ $manufacturer->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="info flex items-center p-4 ">
                <label
                    class="field-label mr-4 w-32 text-xs sm:text-sm md:text-base text-gray-600 font-semibold">Location:</label>
                <div class="field-Info font-semibold view-only text-xs sm:text-sm md:text-base">{{ $data->location }}
                </div>
                <select name="loc" class="edit hidden w-full border-gray-300 text-xs sm:text-sm md:text-base">
                    @foreach ($location['locs'] as $location)
                        <option value="{{ $location->id }}" @selected($data->location == $location->name)>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="info flex items-center p-4 ">
                <label
                    class="field-label mr-4 w-32 text-xs sm:text-sm md:text-base text-gray-600 font-semibold">Status:</label>
                <div class="field-Info font-semibold view-only text-xs sm:text-sm md:text-base">
                    @include('components.asset-status', ['status' => $data->status])
                </div>
                <select name="status" id="status" onchange="toggleRequired(this)"
                    class="hidden w-full border-gray-300 text-base">
                    @foreach ($status['sts'] as $stat)
                        <option value="{{ $stat }}" @selected($data->status == $stat)>
                            {{ $stat === 'under_maintenance' ? 'under maintenance' : $stat }}
                        </option>
                    @endforeach
                </select>
                <div class="edit hidden field-Info font-semibold view-only text-xs sm:text-sm md:text-base">
                    @include('components.asset-status', ['status' => $data->status])
                </div>
            </div>

            <div class="info flex items-center p-4 bg-white">
                <label
                    class="field-label mr-4 w-32 text-xs sm:text-sm md:text-base text-gray-600 font-semibold">Depreciation:</label>
                <div class="field-Info font-semibold view-only text-xs sm:text-sm md:text-base">
                    {{ $data->depreciation }}</div>
                <x-text-input name="depreciation" id="depreciation" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*"
                    class="edit hidden w-full border-gray-300 text-xs sm:text-sm md:text-base"
                    value="{{ $data->depreciation }}" readonly />
            </div>

            <div class="info flex items-center p-4 ">
                <label for="purchaseCost"
                    class="field-label mr-4 w-32 text-xs sm:text-sm md:text-base text-gray-600 font-semibold">Purchase
                    Cost:</label>
                <div class="field-Info font-semibold view-only text-xs sm:text-sm md:text-base">
                    {{ $data->purchase_cost }}</div>
                <x-text-input name="purchaseCost" id="pCost" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*"
                    class="edit hidden w-full border-gray-300 text-xs sm:text-sm md:text-base cursor-disabled"
                    value="{{ $data->purchase_cost }}" />
            </div>
            <div class="info flex items-center p-4">
                <label
                    class="field-label mr-4 w-32 text-xs sm:text-sm md:text-base text-gray-600 font-semibold">Purchase
                    date:</label>
                <div class="field-Info font-semibold view-only text-xs sm:text-sm md:text-base">
                    {{ \Carbon\Carbon::parse($data->purchase_date)->format('d-m-Y') }}</div>
                <x-text-input type="text" name="purchasedDate" id="purchase_date"
                    class="edit hidden w-full border-gray-300 text-xs sm:text-sm md:text-base"
                    value="{{ \Carbon\Carbon::parse($data->purchase_date)->format('d-m-Y') }}" />
            </div>
            <div class="info flex items-center p-4 " id="salvageGroup">
                <label class="field-label mr-4 w-32 text-xs sm:text-sm md:text-base text-gray-600 font-semibold">Salvage
                    Value:</label>
                <div class="field-Info font-semibold view-only text-xs sm:text-sm md:text-base">
                    {{ $data->salvage_value }}</div>
                <x-text-input name="salvageValue" id="salvageValue" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*"
                    class="edit hidden w-full border-gray-300 text-xs sm:text-sm md:text-base"
                    value="{{ $data->salvage_value }}" />
            </div>
            <div class="info flex items-center p-4">
                <label class="field-label mr-4 w-32 text-xs sm:text-sm md:text-base text-gray-600 font-semibold">Usage
                    Lifespan:</label>
                <div class="field-Info font-semibold view-only text-xs sm:text-sm md:text-base">
                    {{ $data->usage_lifespan }} years</div>
                <x-text-input name="lifespan" id="lifespan"
                    class="edit hidden w-full border-gray-300 text-xs sm:text-sm md:text-base"
                    value="{{ $data->usage_lifespan }}" />
            </div>

            <div class="info flex items-center p-4 ">
                <label class="field-label mr-4 w-32 text-gray-600 font-semibold text-base">Assigned to:</label>
                <div class="field-Info font-semibold view-only text-base">

                    {{ isset($data->lastname) ? $data->lastname . ', ' . $data->firstname : 'N/A' }}</div>
                <select name="usrAct" id="selectUsers" class="edit hidden w-full border-gray-300 text-base">
                    @foreach ($allUserInDept as $itemOption)
                        <option value="{{ $itemOption->id }}" @selected($data->user_id == $itemOption->id)>
                            {{ $itemOption->firstname . ' ' . $itemOption->lastname }}
                        </option>
                    @endforeach
                    <option value ='' @selected(!isset($data->lastname))>Assign no one</option>

                </select>
            </div>
            <div class="info flex flex-col p-4 addInfoContainer">
                <span class="field-label mr-4 full text-gray-600 font-semibold text-base">Additional Information</span>


                @if (!empty($updatedCustomFields))
                    <div class="addInfoBox grid gap-2">
                        @foreach ($updatedCustomFields as $item)
                            <div class="extraInfo grid grid-cols-2 lg:grid-cols-[minmax(20%,50px)_auto] gap-2">
                                <div class="field-Key customField capitalize text-slate-400 flex items-center h-full">
                                    {{ ucfirst($item['name']) }}
                                </div>

                                <div class="field-Info edit view-only">
                                    {{ empty($item['value']) ? 'N/a' : $item['value'] }}</div>
                                    @if ($item['type'] === 'date')
                                    <input
                                        class="edit hidden"
                                        type="date"
                                        name="field[value][]"
                                        aria-placeholder="Value"
                                        value="{{ empty(trim($item['value'])) ? '' : \Carbon\Carbon::parse(trim($item['value']))->format('Y-m-d') }}" />
                                @else
                                    <input
                                        class="edit hidden"
                                        type="{{ $item['type'] }}"
                                        name="field[value][]"
                                        aria-placeholder="Value"
                                        value="{{ empty($item['value']) ? '' : $item['value'] }}" />
                                @endif


                                <x-text-input class="hidden" name="field[key][]" value="{{ $item['name'] }}" />
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="noneField">No Additional Information</div>
                @endif
            </div>
        </div>

        {{-- Image Section --}}
        <div class="md:flex md:flex-col gap-3 min-[300px]:grid min-[300px]:grid-cols-2 md:col-start-3 min-[300px]:row-start-1">
            <div class="imgContainer flex flex-col items-center space-y-4 shrink">
                <label class="font-semibold text-xs sm:text-sm md:text-base text-gray-700">Asset Image</label>
                <div class="imageField min-[400px]:w-40 min-[400px]:w-40 shrink border-2 border-gray-200 rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset($imagePath) }}" id="imagePreview" alt="Asset Image"
                        class="w-full h-full object-cover">
                </div>
                <label for="image" class="text-blue-500 cursor-pointer hover:underline edit hidden">
                    Select New Image
                    <input type="file" id="image" name="image" accept="image/*" class="hidden" />

                </label>
            </div>

            <div class="qrContainer flex flex-col items-center space-y-4 shrink">
                <label class="font-semibold text-xs sm:text-sm md:text-base text-gray-700">QR Code</label>
                @if ($data->qr_img)
                    <a href="{{ asset('storage/' . $data->qr_img) }}" download="{{ $data->code }}"
                        class="block  shrink">
                        <img src="{{ asset('storage/' . $data->qr_img) }}" alt="QR Code"
                            class="w-full h-full object-contain min-[400px]:w-40 min-[400px]:w-40 shrink">
                    </a>
                @else
                    <div class="QRBOX w-40 h-40 border-2 border-gray-200 rounded-lg shadow-md">
                        <img src="{{ asset($qrCodePath) }}" alt="QR Code" class="w-full h-full object-contain">
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>

<div id="disposeModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6">
        <h2 class="text-2xl font-semibold mb-4 text-left">Confirm Disposal</h2>
        <p class="text-base text-gray-700 mb-6 text-left">
            Are you sure you want to dispose this asset?
        </p>
        <div class="flex justify-end space-x-4">
            <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                onclick="closeDisposeModal()">
                No
            </button>
            <button type="button" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600"
                onclick="confirmDisposal()">
                Yes
            </button>
        </div>
    </div>
</div>

@if (session('success'))
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow">
        {{ session('success') }}
    </div>
@endif

@vite(['resources/js/flashNotification.js'])
<!-- Include Flatpickr CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<script>
    let assetId = {{ $data->id }};

    function openDisposeModal() {
        document.getElementById('disposeModal').classList.remove('hidden');
    }

    function closeDisposeModal() {
        document.getElementById('disposeModal').classList.add('hidden');
    }

    function confirmDisposal() {
        fetch(`/asset/dispose/${assetId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to dispose of the asset.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });

        closeDisposeModal();
    }

    document.getElementById('formEdit').addEventListener('submit', function (event) {
    });

</script>

<script>
    function toggleRequired(select) {
        const selectUsersInput = document.getElementById('selectUsers');
        const statusAssetInput = document.getElementById('status');
        if (select.value === 'deployed') {
            selectUsersInput.setAttribute('required', 'required'); // Set required
        } else {
            selectUsersInput.removeAttribute('required');
            selectUsersInput.value = '';
            // Remove required
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const editButton = document.getElementById('editBTN');
        const saveButton = document.getElementById('saveBTN');
        const cancelButton = document.getElementById('cancelBTN');
        const editElements = document.querySelectorAll('.edit');
        const viewElements = document.querySelectorAll('.view-only');

        // Target the specific 'edit' div inside the status section
        const statusEditDiv = document.querySelector('.info .edit.field-Info');

        // assigned to and also status to interactions
        const selectUsersInput = document.getElementById('selectUsers');
        const statusAssetInput = document.getElementById('status');

        let initialUsrActValue = selectUsersInput.value;
        let initialStatusValue = statusAssetInput.value;

        // selectUsersInput.addEventListener('change', function() {
        //     if (initialUsrActValue === '' && selectUsers.value !== '') {
        //         // Change the status to 'deployed' if usrAct changes from empty to non-empty
        //         statusAssetInput.value = 'deployed';
        //     } else {
        //         statusAssetInput.value = initialStatusValue;
        //     }
        // });

        selectUsersInput.addEventListener('change', function() {
            const selectedValue = selectUsersInput.value;

            if (selectedValue === '') {
                // If 'Assign no one' is selected, set the status to 'active'
                statusAssetInput.value = 'active';
            } else {
                // Otherwise, keep the original status value or set it to 'deployed'
                statusAssetInput.value = 'deployed';
            }
        });

        // -------------------

        editButton.addEventListener('click', () => {
            editElements.forEach(el => el.classList.remove('hidden'));
            viewElements.forEach(el => el.classList.add('hidden'));
            editButton.classList.add('hidden');
            saveButton.classList.remove('hidden');
            cancelButton.classList.remove('hidden');

            // Ensure the status edit div becomes visible in edit mode
            statusEditDiv.classList.remove('hidden');
        });

        cancelButton.addEventListener('click', () => {
            editElements.forEach(el => el.classList.add('hidden'));
            viewElements.forEach(el => el.classList.remove('hidden'));
            editButton.classList.remove('hidden');
            saveButton.classList.add('hidden');
            cancelButton.classList.add('hidden');

            // Ensure the status edit div hides in cancel mode
            statusEditDiv.classList.add('hidden');
        });

        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
    // Depreciation Calculation
    const depreciationInput = document.getElementById('depreciation');
    const purchaseCostInput = document.getElementById('pCost');
    const salvageValueInput = document.getElementById('salvageValue');
    const lifespanInput = document.getElementById('lifespan');

    // Error message element
    const errorMessage = document.createElement('div');
    errorMessage.classList.add('text-red-500');
    errorMessage.style.display = 'none'; // Initially hidden
    errorMessage.innerHTML = "Salvage value cannot exceed the purchase cost.";
    salvageValueInput.parentNode.appendChild(errorMessage); // Append the error message after the salvage value field

    function calculateDepreciation() {
        const cost = parseFloat(purchaseCostInput.value) || 0;
        const salvageValue = parseFloat(salvageValueInput.value) || 0;
        const lifespan = parseInt(lifespanInput.value) || 1;

        // Check if Salvage Value is greater than Purchase Cost
        if (salvageValue > cost) {
            salvageValueInput.classList.add('border-red-500');
            errorMessage.style.display = 'block'; // Show error message
            depreciationInput.value = "0.00"; // Prevent calculation
            return; // Stop further execution if invalid
        } else {
            salvageValueInput.classList.remove('border-red-500');
            errorMessage.style.display = 'none'; // Hide error message
        }

        // Calculate depreciation if the lifespan is greater than 0
        if (lifespan > 0) {
            const depreciation = (cost - salvageValue) / lifespan;
            depreciationInput.value = depreciation.toFixed(2);
        } else {
            depreciationInput.value = "0.00";
        }
    }

    // Add input event listeners to trigger depreciation calculation
    purchaseCostInput.addEventListener('input', calculateDepreciation);
    salvageValueInput.addEventListener('input', calculateDepreciation);
    lifespanInput.addEventListener('input', calculateDepreciation);

    // Initialize fields with default values
    // purchaseCostInput.value = "";
    // salvageValueInput.value = "";
    // lifespanInput.value = "";
    // depreciationInput.value = "";

    // Initialize Flatpickr with the desired format
    flatpickr("#purchase_date", {
        dateFormat: "d-m-Y", // Change to your preferred format
    });
</script>
