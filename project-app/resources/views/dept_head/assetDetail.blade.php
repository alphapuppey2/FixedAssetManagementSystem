@extends('layouts.app')

@php
    // Retrieve asset data and fallback image
    $data = $retrieveData ?? null;
    $imagePath = $data->image ?? 'images/defaultICON.png';
    $qrCodePath = $data->qr ?? 'images/defaultQR.png'; // Fallback QR code if not available
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
    <h2 class="font-semibold text-xl text-gray-800 leading-tight flex w-24">
        <a href="{{ route('back') }}">Asset</a>
        <div class="direct ml-5">></div>
    </h2>
    <h2 class="assetID font-semibold text-xl w-24">
        {{ $data->code ?? 'No Code' }}
    </h2>
    <button id="editBTN" type="submit" class="text-blue-500 text-[12px]">EDIT</button>
    <button id="saveBTN" type="submit" form="formEdit" class="text-blue-500 mr-2 text-[12px] hidden">SAVE</button>
    <button id="cancelBTN" class="text-blue-500 text-[12px] mr-2 hidden">CANCEL</button>
@endsection

@section('content')
    <div class="w-full h-full">
        @if ($errors->any())
            <div class="err">
                INVALID
            </div>
        @endif
        <form id="formEdit" action="{{ route('assetDetails.edit', $data->id) }}"
            class="details relative w-full min-h-full grid grid-row-[1fr_minmax(50%,100px)] gap-2" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Image Section --}}
            <div class="imgContainer w-[100%] pb-4 flex justify-center items-center md:col-span-2">
                <div class="imagepart overflow-hidden relative p-3">
                    <div class="imageField w-32 h-32 relative flex justify-center">
                        <div class="field-Info w-32 h-32 border-3 rounded-md transition ease-in ease-out" for="image">
                            <img src="{{ $imagePath ? asset('storage/' . $imagePath) : asset('images/no-image.jpg') }}"
                                id="imageviewOnly"
                                class="absolute top-1/2 left-1/2 w-auto h-full transform -translate-x-1/2 -translate-y-1/2 object-cover"
                                alt="Asset Image">
                        </div>
                        <label
                            class="edit hidden w-32 h-32 border-3 rounded-md hover:border-4 hover:border-blue-400 transition ease-in ease-out"
                            for="image">
                            <img src="{{ $imagePath ? asset('storage/' . $imagePath) : asset('images/no-image.jpg') }}"
                                id="imageDisplay"
                                class="absolute top-1/2 left-1/2 w-auto h-full transform -translate-x-1/2 -translate-y-1/2 object-cover"
                                alt="Asset Image">
                        </label>
                    </div>
                    <x-text-input type="file" id="image" name='image' class="hidden" />
                </div>

                {{-- QR Code Section --}}
                <div class="qrContainer flex flex-col items-center">
                    <div class="QRBOX w-24 h-24">
                        <img src="{{ asset('storage/' . $qrCodePath) }}" alt="QR Code"
                            class="w-full h-full object-contain">
                    </div>
                    <a href="{{ asset('storage/' . $qrCodePath) }}" download="{{ $data->code }}">Download QR Code</a>
                </div>
            </div>

            {{-- Main Asset Details --}}
            <div class="leftC">
                <div class="mainDetail lg:grid lg:grid-rows-6 max-sm:grid-cols-1 grid-flow-col gap-2">
                    {{-- Asset name, cost, depreciation, etc. --}}
                    <div id="name" class="info flex flex-wrap items-center">
                        <div class="field-label mr-3 capitalize text-slate-400 inline-block">name</div>
                        <div class="field-Info font-semibold inline-block">{{ $data->name }}</div>
                        <x-text-input class="text-sm edit hidden inline-block" name='name'
                            value="{{ $data->name }}" />
                    </div>
                    <div class="info flex pb-1 items-center">
                        <div class="field-label mr-3 capitalize text-slate-400">Cost</div>
                        <div class="field-Info font-semibold">{{ $data->cost }}</div>
                        <x-text-input inputmode="decimal" id="cost" class="edit hidden" pattern="[0-9]*[.,]?[0-9]*"
                            name="cost" required value="{{ $data->cost }}" />
                    </div>
                    <div class="info flex pb-1 items-center">
                        <div class="field-label mr-3 capitalize text-slate-400">Depreciation</div>
                        <div class="field-Info font-semibold" id="depreciation-value">{{ $data->depreciation }}</div>
                        <x-text-input inputmode="decimal" id="depreciation" class="edit hidden" pattern="[0-9]*[.,]?[0-9]*"
                            name="depreciation" required value="{{ $data->depreciation }}" />
                    </div>
                    <div class="info flex pb-1 items-center">
                        <div class="field-label mr-3 capitalize text-slate-400">Salvage Value</div>
                        <div class="field-Info font-semibold">{{ $data->salvageVal }}</div>
                        <x-text-input inputmode="decimal" id="salvageVal" class="edit hidden" pattern="^-?\d+(\.\d{1,2})?$"
                            name="salvageVal" required value="{{ $data->salvageVal }}" />
                    </div>
                    <div class="info flex pb-1 items-center">
                        <div class="field-label mr-3 capitalize text-slate-400">Category</div>
                        <div class="field-Info font-semibold">{{ $data->category }}</div>
                        <div class="form-group edit hidden">
                            <select name="category" id="category" class="w-full">
                                @foreach ($categories['ctglist'] as $category)
                                    <option value={{ $category->id }} @selected($data->category == $category->name)>{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex pb-1 items-center">
                        <label class="field-label mr-3 capitalize text-slate-400">Lifespan</label>
                        <div class="field-Info font-semibold">{{ $data->usage_Lifespan }}</div>
                        <x-text-input class="text-sm edit hidden" id="usage_Lifespan" name="usage"
                            value="{{ $data->usage_Lifespan }}" />
                    </div>
                    <div class="info flex pb-1 items-center">
                        <div class="field-label mr-3 capitalize text-slate-400">Model</div>
                        <div class="field-Info font-semibold">{{ $data->model }}</div>
                        <div class="form-group edit hidden">
                            <select name="mod" id="mod" class="w-full flex flex-col">
                                @foreach ($model['mod'] as $model)
                                    <option value={{ $model->id }} @selected($data->model == $model->name)>{{ $model->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex pb-1 items-center">
                        <div class="field-label mr-3 capitalize text-slate-400">Manufacturer</div>
                        <div class="field-Info font-semibold">{{ $data->manufacturer }}</div>
                        <div class="form-group edit hidden">
                            <select name="mcft" id="mcft" class="w-full">
                                @foreach ($manufacturer['mcft'] as $manufacturer)
                                    <option value={{ $manufacturer->id }} @selected($data->manufacturer == $manufacturer->name)>
                                        {{ $manufacturer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex pb-1 items-center">
                        <label class="field-label mr-3 capitalize text-slate-400">Location</label>
                        <div class="field-Info font-semibold">{{ $data->location }}</div>
                        <div class="form-group edit hidden">
                            <select name="loc" id="loc" class="w-full">
                                @foreach ($location['locs'] as $location)
                                    <option value={{ $location->id }} @selected($data->location == $location->name)>{{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex pb-1 items-center">
                        <div class="field-label mr-3 capitalize text-slate-400">Status</div>
                        <div class="field-Info font-semibold">{{ $data->status }}</div>
                        <div class="form-group edit hidden">
                            <select name="status" id="status" class="w-full">
                                @foreach ($status['sts'] as $stats)
                                    <option value="{{ $stats }}" @selected($data->status == $stats)>{{ $stats }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex pb-1 items-center">
                        <div class="field-label mr-3 capitalize text-slate-400">Last Used</div>
                        <div class="form-group edit">NONE</div>

                        <div class="relative edit hidden">
                            <input type="text" id="autocomplete-input"
                                   class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                                   autocomplete="off" value="">
                            <div id="suggestions" class="absolute left-0 w-full mt-1 bg-white border border-gray-300 rounded shadow-lg hidden max-h-60 overflow-y-auto z-10">
                                <!-- Suggestions will appear here -->
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Additional Information --}}
                <div class="MoreInfo">
                    <div class="addInformation">
                        <div class="title font-bold m-2 text-[15px] opacity-50 capitalize">
                            Additional information
                            <div class="divider w-20 h-[2px] bg-slate-400 opacity-50 mb-2 mt-2"></div>
                        </div>
                        <div class="addInfoContainer grid grid-rows-5 grid-flow-col w-full">
                            @if ($fields)
                                @foreach ($fields as $key => $value)
                                    <div class="extraInfo grid grid-cols-2 lg:grid-cols-[minmax(20%,50px)_20%] gap-2">
                                        <div class="field-Key customField capitalize text-slate-400">{{ $key }}
                                        </div>
                                        <div class="field-Info customField">{{ $value }}</div>
                                        <x-text-input class="hidden" name="field[key][]"
                                            value="{{ $key }}" />
                                        <x-text-input class="edit hidden" name="field[value][]"
                                            value="{{ $value }}" />
                                    </div>
                                @endforeach
                            @else
                                <div class="noneField">No Additional Information</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Maintenance History Section --}}
            <div class="rightC flex flex-col">
                <div class="maintenance flex flex-col justify-center items-center">
                    <div class="header w-full flex justify-between">
                        <h1>MAINTENANCE HISTORY</h1>
                        <a href="{{ route('asset.history', $data->id) }}" class="text-[12px] text-blue-500"> VIEW ALL</a>
                    </div>
                    <div class="divider w-full h-[1px] border-1 border-slate-500 mt-2 mb-2"></div>
                    <div class="tableContainer">
                        <table>
                            <thead>
                                <th>User</th>
                                <th>Reason</th>
                                <th>Cost</th>
                                <th>Complete</th>
                            </thead>
                            <tbody>
                                @if (isset($assetRet) && count($assetRet) > 0)
                                    @foreach ($assetRet as $item)
                                        <tr>
                                            <td>{{ $item->lname . ' , ' . $item->fname }}</td>
                                            <td>{{ $item->reason }}</td>
                                            <td>{{ $item->cost }}</td>
                                            <td class="text-slate-400">{{ $item->complete }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan='4' class="text-center">NO MAINTENANCE HISTORY</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
        </form>
    </div>

    @vite(['resources/js/displayImage.js', 'resources/js/updateDetails.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            //depcreciation calculation

            const costInput = document.getElementById('cost');
            const salvageValInput = document.getElementById('salvageVal');
            const lifespanInput = document.getElementById('usage_Lifespan');
            const depreciationValue = document.getElementById('depreciation-value');

            function calculateDepreciation() {
    const cost = parseFloat(costInput.value) || 0;
    const salvageVal = parseFloat(salvageValInput.value) || 0;
    const lifespan = parseFloat(lifespanInput.value) || 1; // Prevent division by zero

    const depreciation = (cost - salvageVal) / lifespan;
    // depreciationValue.textContent = depreciation.toFixed(2); // Update the displayed value

    // Update the hidden input field with the calculated depreciation
    const depreciationInput = document.getElementById('depreciation');
    if (depreciationInput) {
        depreciationInput.value = depreciation.toFixed(2);
    }

    console.log(`Depreciation calculated: ${depreciation.toFixed(2)}`); // Debugging output
}


        // Event listeners to trigger calculation on input change
        costInput.addEventListener('input', calculateDepreciation);
        salvageValInput.addEventListener('input', calculateDepreciation);
        lifespanInput.addEventListener('input', calculateDepreciation);

            const input = document.getElementById('autocomplete-input');
            const suggestions = document.getElementById('suggestions');

            // Function to fetch suggestions
            function fetchSuggestions(query = '') {
                fetch(`/asset/user/autocomplete?query=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        suggestions.innerHTML = ''; // Clear previous suggestions

                        if (Array.isArray(data) && data.length) {
                            suggestions.style.display = 'block';
                            // Limit to showing at least 4 suggestions
                            const usersToShow = data.slice(0, 4);
                            usersToShow.forEach(item => {
                                const fullName = `${item.firstname} ${item.middlename} ${item.lastname}`;
                                const suggestionItem = document.createElement('a');
                                suggestionItem.className = 'block p-2 hover:bg-gray-200 cursor-pointer';
                                suggestionItem.textContent = fullName;
                                suggestionItem.href = '#';
                                suggestionItem.addEventListener('click', function() {
                                    input.value = fullName;
                                    suggestions.style.display = 'none';
                                });
                                suggestions.appendChild(suggestionItem);
                            });
                        } else {
                            suggestions.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching autocomplete suggestions:', error);
                    });
            }

            // Fetch suggestions when the input is focused
            input.addEventListener('focus', function() {
                fetchSuggestions(); // Fetch without query to show default users
            });

            // Fetch suggestions when typing in the input field
            input.addEventListener('keyup', function() {
                const query = input.value;
                if (query.length >= 2) {
                    fetchSuggestions(query);
                }
            });

            // Hide dropdown when clicked outside
            document.addEventListener('click', function(event) {
                if (!input.contains(event.target) && !suggestions.contains(event.target)) {
                    suggestions.style.display = 'none';
                }
            });
        });
    </script>
@endsection
