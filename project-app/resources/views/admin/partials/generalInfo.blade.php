<form id="formEdit" action="{{ route('adminAssetDetails.edit', $data->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="flex justify-end space-x-4 mt-4">
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


    <div class="grid grid-cols-3 gap-8 p-6">
        <div class="col-span-2 space-y-6">
            {{-- Asset Name --}}
            <div class="info flex items-center p-4 bg-white">
                <label class="field-label mr-4 w-32 text-gray-600 font-semibold text-base">Name:</label>
                <div class="field-Info font-semibold view-only text-base">{{ $data->name }}</div>
                <x-text-input name="name" class="edit hidden w-full border-gray-300 text-base" value="{{ $data->name }}" />
            </div>

            {{-- Category --}}
            <div class="info flex items-center p-4 bg-gray-100">
                <label class="field-label mr-4 w-32 text-gray-600 font-semibold text-base">Category:</label>
                <div class="field-Info font-semibold view-only text-base">{{ $data->category }}</div>
                <select name="category" class="edit hidden w-full border-gray-300 text-base">
                    @foreach ($categories['ctglist'] as $category)
                    <option value="{{ $category->id }}" @selected($data->category == $category->name)>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Model --}}
            <div class="info flex items-center p-4 bg-white">
                <label class="field-label mr-4 w-32 text-gray-600 font-semibold text-base">Model:</label>
                <div class="field-Info font-semibold view-only text-base">{{ $data->model }}</div>
                <select name="mod" class="edit hidden w-full border-gray-300 text-base">
                    @foreach ($model['mod'] as $model)
                    <option value="{{ $model->id }}" @selected($data->model == $model->name)>
                        {{ $model->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Manufacturer --}}
            <div class="info flex items-center p-4 bg-gray-100">
                <label class="field-label mr-4 w-32 text-gray-600 font-semibold text-base">Manufacturer:</label>
                <div class="field-Info font-semibold view-only text-base">{{ $data->manufacturer }}</div>
                <select name="mcft" class="edit hidden w-full border-gray-300 text-base">
                    @foreach ($manufacturer['mcft'] as $manufacturer)
                    <option value="{{ $manufacturer->id }}" @selected($data->manufacturer == $manufacturer->name)>
                        {{ $manufacturer->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Location --}}
            <div class="info flex items-center p-4 bg-white">
                <label class="field-label mr-4 w-32 text-gray-600 font-semibold text-base">Location:</label>
                <div class="field-Info font-semibold view-only text-base">{{ $data->location }}</div>
                <select name="loc" class="edit hidden w-full border-gray-300 text-base">
                    @foreach ($location['locs'] as $location)
                    <option value="{{ $location->id }}" @selected($data->location == $location->name)>
                        {{ $location->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="info flex items-center p-4 bg-gray-100">
                <label class="field-label mr-4 w-32 text-gray-600 font-semibold text-base">Status:</label>
                <div class="field-Info font-semibold view-only text-base">
                    @include('components.asset-status', ['status' => $data->status])
                </div>
                <select name="status" class="edit hidden w-full border-gray-300 text-base">
                    @foreach ($status['sts'] as $stat)
                    <option value="{{ $stat }}" @selected($data->status == $stat)>{{ $stat === "under_maintenance" ? 'under maintenance' : $stat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Last Used By --}}
            <div class="info flex items-center p-4 bg-white">
                <label class="field-label mr-4 w-32 text-gray-600 font-semibold text-base">Last Used By:</label>
                <div class="field-Info font-semibold view-only text-base">{{ $data->lastname . ', ' . $data->firstname }}</div>
                <select name="usrAct" class="edit hidden w-full border-gray-300 text-base">
                    <option value="">Select a user</option>
                </select>
            </div>
        </div>


        {{-- Right Column: Asset Image and QR Code --}}
        <div class="col-span-1 space-y-8">
            {{-- Asset Image --}}
            <div class="imgContainer flex flex-col items-center space-y-4">
                <label class="font-semibold text-gray-700">Asset Image</label>
                <div class="imageField w-40 h-40 relative flex justify-center items-center border-2 border-gray-200 rounded-lg shadow-md overflow-hidden">
                    <img
                        src="{{ asset($imagePath) }}"
                        id="imagePreview"
                        alt="Asset Image"
                        class="w-full h-full object-cover">
                </div>

                <label for="image" class="text-blue-500 cursor-pointer hover:underline edit hidden">
                    Select New Image
                    <x-text-input type="file" id="image" name="image" class="hidden" />
                </label>
            </div>

            {{-- QR Code --}}
            <div class="qrContainer flex flex-col items-center space-y-4">
                <label class="font-semibold text-gray-700">QR Code</label>
                @if($data->qr_img)
                <a href="{{ asset('storage/' . $data->qr_img) }}" download="{{ $data->code }}" class="block w-40 h-40">
                    <img src="{{ asset('storage/' . $data->qr_img) }}" alt="QR Code" class="w-full h-full object-contain">
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

@if (session('success'))
<div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow">
    {{ session('success') }}
</div>
@endif

@vite(['resources/js/flashNotification.js'])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButton = document.getElementById('editBTN');
        const saveButton = document.getElementById('saveBTN');
        const cancelButton = document.getElementById('cancelBTN');
        const editElements = document.querySelectorAll('.edit');
        const viewElements = document.querySelectorAll('.view-only');

        editButton.addEventListener('click', () => {
            editElements.forEach(el => el.classList.remove('hidden'));
            viewElements.forEach(el => el.classList.add('hidden'));
            editButton.classList.add('hidden');
            saveButton.classList.remove('hidden');
            cancelButton.classList.remove('hidden');
        });

        cancelButton.addEventListener('click', () => {
            editElements.forEach(el => el.classList.add('hidden'));
            viewElements.forEach(el => el.classList.remove('hidden'));
            editButton.classList.remove('hidden');
            saveButton.classList.add('hidden');
            cancelButton.classList.add('hidden');
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
</script>
