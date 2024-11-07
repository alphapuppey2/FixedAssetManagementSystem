@extends('layouts.app')
@section('header')
    {{-- <div class="header flex w-full justify-between pr-3 pl-3 items-center"> --}}
    <div class="header flex flex-wrap w-full justify-between pr-3 pl-3 items-center">
        <div class="title">
            {{-- <h2 class="font-semibold text-xl text-gray-800 leading-tight"> --}}
            <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
                Settings
            </h2>
        </div>
    </div>
@endsection
@section('content')

    <div class="cont relative p-2 md:p-1 h-full">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        @if (Auth::user()->usertype === 'admin')
            <!-- Department Dropdown for Admin -->
            <div class="deptDropdown w-64 mb-4 flex items-center gap-2">
                <label for="departments" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select name="dept" id="departments" required
                    class="block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Department</option>
                    @foreach ($allDepartments as $department)
                        <option value="{{ $department->id }}"
                            {{ $department->id == $selectedDepartmentID ? 'selected' : '' }}>{{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="container relative h-full p-2 flex flex-col gap-2">
            {{-- <div class="flex justify-between"> --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center"> <!-- Added flex-wrap -->
                {{-- <ul class="flex border-b max-w-max border-gray-300 inline-block"> --}}
                <!-- Tabs with department_id -->
                <ul class="flex flex-wrap border-b max-w-full md:max-w-max border-gray-300">
                    <li class="mr-1">
                        <a class="inline-block py-2 px-4 text-sm {{ $activeTab === 'model' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500 hover:text-blue-500' }}"
                            href="{{ url('/admin/setting?tab=model&department_id=' . $selectedDepartmentID) }}">
                            Model
                        </a>
                    </li>
                    <li class="mr-1">
                        <a class="inline-block py-2 px-4 text-sm {{ $activeTab === 'manufacturer' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500 hover:text-blue-500' }}"
                            href="{{ url('/admin/setting?tab=manufacturer&department_id=' . $selectedDepartmentID) }}">
                            Manufacturer
                        </a>
                    </li>
                    <li class="mr-1">
                        <a class="inline-block py-2 px-4 text-sm {{ $activeTab === 'location' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500 hover:text-blue-500' }}"
                            href="{{ url('/admin/setting?tab=location&department_id=' . $selectedDepartmentID) }}">
                            Location
                        </a>
                    </li>
                    <li class="mr-1">
                        <a class="inline-block py-2 px-4 text-sm {{ $activeTab === 'category' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500 hover:text-blue-500' }}"
                            href="{{ url('/admin/setting?tab=category&department_id=' . $selectedDepartmentID) }}">
                            Category
                        </a>
                    </li>
                    <li class="mr-1">
                        <a class="inline-block py-2 px-4 text-sm {{ $activeTab === 'customFields' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500 hover:text-blue-500' }}"
                            href="{{ url('/admin/setting?tab=customFields&department_id=' . $selectedDepartmentID) }}">
                            Custom Field
                        </a>
                    </li>
                </ul>
                <!-- Button to Open the Modal -->
                <button onclick="openModal()" {{-- class="px-3 py-1 h-10 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 focus:outline-none flex items-center"> --}}
                    class="px-2 md:px-3 py-1 h-10 bg-blue-950 text-white rounded-md shadow hover:bg-blue-950/80 focus:outline-none flex items-center mt-2 md:mt-0">
                    New {{ $activeTab !== 'customFields' ? ucfirst($activeTab) : ucfirst('custom fields') }}</button>


                <!-- Include the Modal -->
                @include('admin.modal.newSettingModal')
            </div>


            {{-- <div class="tab-content relative h-full bg-white border border-white rounded-lg overflow-y-auto"> --}}
            <div class="tab-content relative h-full bg-white border border-white rounded-lg overflow-y-auto mt-2">
                <!-- Added margin -->

                <table class="w-full gap-2">
                    <thead class="bg-gray-100 ">
                        <tr>
                            @if ($activeTab !== 'customFields')
                                {{-- <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> --}}
                                <td
                                    class="px-2 md:px-6 py-2 md:py-3 text-left sm:text-xs md:text-md font-medium text-gray-500 uppercase tracking-wider">
                                    Name</td>
                                {{-- <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> --}}
                                <td
                                    class="px-2 md:px-6 py-2 md:py-3 text-left sm:text-xs md:text-md font-medium text-gray-500 uppercase tracking-wider">
                                    Description</td>
                                {{-- <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> --}}
                                <td
                                    class="px-2 md:px-6 py-2 md:py-3 text-left sm:text-xs md:text-md font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</td>
                            @else
                                {{-- <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> --}}
                                <td
                                    class="px-2 md:px-6 py-2 md:py-3 text-left sm:text-xs md:text-md font-medium text-gray-500 uppercase tracking-wider">
                                    Name</td>
                                {{-- <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> --}}
                                <td
                                    class="px-2 md:px-6 py-2 md:py-3 text-left sm:text-xs md:text-md font-medium text-gray-500 uppercase tracking-wider">
                                    Type</td>
                                {{-- <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> --}}
                                <td
                                    class="px-2 md:px-6 py-2 md:py-3 text-left sm:text-xs md:text-md font-medium text-gray-500 uppercase tracking-wider">
                                    Helper Text</td>
                                {{-- <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> --}}
                                <td
                                    class="px-2 md:px-6 py-2 md:py-3 text-left sm:text-xs md:text-md font-medium text-gray-500 uppercase tracking-wider">
                                    Action</td>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($data) && count($data) > 0)
                            @foreach ($data as $key => $dataItem)
                                <tr class="" id="row-{{ $activeTab !== 'customFields' ? $dataItem->id : $key }}">
                                    @if ($activeTab !== 'customFields')
                                        <td
                                            class="w-64 px-3 py-3 text-left sm:text-xs md:text-md font-medium text-gray-500 tracking-wider">
                                            <span class="name-text">{{ $dataItem->name }}</span>
                                            <input type="text"
                                                class="name-input px-3 py-2 text-left text-xs font-medium text-gray-500 tracking-wider"
                                                style="display: none" value="{{ $dataItem->name }}">

                                        </td>
                                        <td
                                            class="px-2 py-3 text-left sm:text-xs md:text-md font-medium text-gray-500 tracking-wider">
                                            <span class="desc-text ">{{ $dataItem->description }}</span>
                                            <input type="text"
                                                class="desc-input px-3 py-2 text-left text-xs font-medium text-gray-500 tracking-wider"
                                                style="display: none" value="{{ $dataItem->description }}">
                                        </td>
                                        <td
                                            class="flex gap-1 px-2 py-3 text-left sm:text-xs md:text-md font-medium text-gray-500 tracking-wider">
                                            <a class="bg-blue-950 text-white px-3 py-2 rounded-md transition duration-300 ease-in-out hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer edit-btn"
                                                data-row-id="{{ $dataItem->id }}">Edit</a>
                                            <a class="bg-blue-950 text-white px-3 py-2 rounded-md transition duration-300 ease-in-out hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer save-btn"
                                                data-row-id="{{ $dataItem->id }}" style="display: none;">Save</a>
                                            <a class="bg-red-400 text-white px-3 py-2 rounded-md transition duration-300 ease-in-out hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 cursor-pointer cancel-btn"
                                                data-row-id="{{ $dataItem->id }}" style="display: none;">Cancel</a>

                                            <form
                                                action="{{ route('admin.setting.delete', ['tab' => $activeTab, 'id' => $dataItem->id]) }}"
                                                method="post" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    class="bg-red-400 text-white px-3 py-2 rounded-md transition duration-300 ease-in-out hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 delete-btn">Delete</button>
                                            </form>
                                        </td>
                                    @else
                                        <td
                                            class="px-2 py-3 text-left sm:text-xs md:text-md font-medium text-gray-500 tracking-wider">
                                            <span class="name-text">{{ $dataItem['name'] }}</span>
                                            <input type="text" class="name-input" style="display: none"
                                                value="{{ $dataItem['name'] }}">

                                        </td>
                                        <td
                                            class="px-2 py-3 text-left text-xs sm:text-xs md:text-md font-medium text-gray-500 tracking-wider">
                                            <span class="type-text ">{{ $dataItem["type"] }}</span>
                                            <select name="type" class="type-input" style="display: none">
                                                <option value="number">Number</option>
                                                <option value="text">Text</option>
                                                <option value="date">Date</option>
                                            </select>
                                            {{-- <input type="text" class="type-input" style="display: none"
                                                value="{{ $dataItem->type }}"> --}}
                                        </td>
                                        <td
                                            class="px-2 py-3 text-left sm:text-xs md:text-md font-medium text-gray-500 tracking-wider">
                                            <span class="helper-text">{{ $dataItem["helptext"] }}</span>

                                            <input type="text" class="helper-input" style="display: none"
                                                value="{{ $dataItem["helptext"] }}">
                                        </td>
                                        <td
                                            class="flex py-2 text-left sm:text-xs md:text-md font-medium text-gray-500 tracking-wider">
                                            <a class="bg-blue-950 text-white px-3 py-2 rounded-md transition duration-300 ease-in-out hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer edit-btn"
                                                data-row-id="{{ $key }}">Edit</a>
                                            <a class="bg-blue-950 text-white px-3 py-2 rounded-md transition duration-300 ease-in-out hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 save-btn"
                                                data-row-id="{{ $key }}" style="display: none;">Save</a>
                                            <a class="bg-red-400 text-white px-3 py-2 rounded-md transition duration-300 ease-in-out hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 cancel-btn"
                                                data-row-id="{{ $key }}" style="display: none;">Cancel</a>

                                            <div class="ml-1">
                                                <form
                                                    action="{{ route('admin.setting.delete', ['tab' => $activeTab, 'id' => $key]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="bg-red-400 text-white px-3 py-2 rounded-md transition duration-300 ease-in-out hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 delete-btn">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="{{ $activeTab !== 'customFields' ? 3 : 4 }}"
                                    class="px-2 md:px-6 py-2 md:py-3 text-left text-xs md:text-sm font-medium text-gray-500 text-center uppercase tracking-wider ">
                                    NO DATA FOUND</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('departments').addEventListener('change', function() {
            const departmentID = this.value;
            const activeTab = new URLSearchParams(window.location.search).get('tab') || 'model';
            const url = new URL(window.location.href);
            url.searchParams.set('department_id', departmentID);
            url.searchParams.set('tab', activeTab);
            window.location.href = url.toString();
        });

        document.querySelectorAll('.edit-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const urlParams = new URLSearchParams(window.location.search);
                let activeTab = urlParams.get('tab');
                const rowId = this.getAttribute('data-row-id');
                const row = document.getElementById('row-' + rowId);

                if (activeTab !== 'customFields') {
                    // Show and toggle name and description inputs for non-customFields
                    row.querySelector('.name-text').classList.toggle('hidden');
                    row.querySelector('.name-input').style.display = 'inline-block';

                    row.querySelector('.desc-text').classList.toggle('hidden');
                    row.querySelector('.desc-input').style.display = 'inline-block';
                } else {
                    // Show and toggle name, type, and helper inputs for customFields
                    row.querySelector('.name-text').classList.toggle('hidden');
                    row.querySelector('.name-input').style.display = 'inline-block';

                    row.querySelector('.type-text').classList.toggle('hidden');
                    row.querySelector('.type-input').style.display = 'inline-block';

                    row.querySelector('.helper-text').classList.toggle('hidden');
                    row.querySelector('.helper-input').style.display = 'inline-block';
                }

                row.querySelector('.save-btn').style.display = 'inline-block';
                row.querySelector('.cancel-btn').style.display = 'inline-block';
                row.querySelector('.delete-btn').classList.toggle('hidden');
                this.style.display = 'none';
            });
        });

        document.querySelectorAll('.cancel-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const rowId = this.getAttribute('data-row-id');
                const row = document.getElementById('row-' + rowId);

                const urlParams = new URLSearchParams(window.location.search);
                let activeTab = urlParams.get('tab');

                if (activeTab !== 'customFields') {
                    // Hide name and description inputs for non-customFields
                    row.querySelector('.name-text').classList.toggle('hidden');
                    row.querySelector('.name-input').style.display = 'none';

                    row.querySelector('.desc-text').classList.toggle('hidden');
                    row.querySelector('.desc-input').style.display = 'none';
                } else {
                    // Hide name, type, and helper inputs for customFields
                    row.querySelector('.name-text').classList.toggle('hidden');
                    row.querySelector('.name-input').style.display = 'none';

                    row.querySelector('.type-text').classList.toggle('hidden');
                    row.querySelector('.type-input').style.display = 'none';

                    row.querySelector('.helper-text').classList.toggle('hidden');
                    row.querySelector('.helper-input').style.display = 'none';
                }
                row.querySelector('.save-btn').style.display = 'none';
                row.querySelector('.edit-btn').style.display = 'inline-block';
                row.querySelector('.delete-btn').classList.toggle('hidden');
                this.style.display = 'none';
            });
        });

        document.querySelectorAll('.save-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const rowId = this.getAttribute('data-row-id');
                const row = document.getElementById('row-' + rowId);
                const urlParams = new URLSearchParams(window.location.search);
                let activeTab = urlParams.get('tab') ?? 'model';

                const nameValue = row.querySelector('.name-input').value;
                let loader;

                if (activeTab !== 'customFields') {
                    // Get name and description values for non-customFields
                    const descValue = row.querySelector('.desc-input').value;
                    loader = {
                        name: nameValue,
                        description: descValue
                    };
                } else {
                    // Get name, type, and helper values for customFields
                    const typeValue = row.querySelector('.type-input').value;
                    const helpValue = row.querySelector('.helper-input').value;
                    loader = {
                        name: nameValue,
                        type: typeValue,
                        helptext: helpValue
                    };
                }

                // AJAX call to save the updated data
                fetch(`/admin/setting/update/${activeTab}/${rowId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(loader)
                    })
                    .then(response => {
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            throw new Error('Server returned non-JSON response');
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            if (activeTab !== 'customFields') {
                                // Update text for non-customFields
                                row.querySelector('.name-text').textContent = nameValue;
                                row.querySelector('.desc-text').textContent = loader.description;
                                row.querySelector('.desc-text').classList.toggle('hidden');
                                row.querySelector('.name-text').classList.toggle('hidden');

                                row.querySelector('.name-input').style.display = 'none';
                                row.querySelector('.desc-input').style.display = 'none';
                            } else {
                                // Update text for customFields
                                row.querySelector('.name-text').textContent = loader.name;
                                row.querySelector('.type-text').textContent = loader.type;
                                row.querySelector('.helper-text').textContent = loader.helptext;
                                row.querySelector('.name-text').classList.toggle('hidden');
                                row.querySelector('.type-text').classList.toggle('hidden');
                                row.querySelector('.helper-text').classList.toggle('hidden');

                                row.querySelector('.name-input').style.display = 'none';
                                row.querySelector('.type-input').style.display = 'none';
                                row.querySelector('.helper-input').style.display = 'none';
                            }

                            row.querySelector('.save-btn').style.display = 'none';
                            row.querySelector('.cancel-btn').style.display = 'none';
                            row.querySelector('.edit-btn').style.display = 'inline-block';
                            row.querySelector('.delete-btn').style.display = 'inline-block';
                            console.log(data)

                        } else {
                            alert('Failed to update.');
                            console.log(data)
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            });
        });
    </script>
   </div>
</div>
@endsection
