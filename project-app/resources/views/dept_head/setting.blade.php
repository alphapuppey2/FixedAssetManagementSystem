@extends('layouts.app')
@section('header')
    <div class="header flex w-full justify-between pr-3 pl-3 items-center">
        <div class="title">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Settings
            </h2>
        </div>
    </div>
@endsection
@section('content')
    <div class="cont relative p-1 h-full">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="container relative p-2 grid grid-rows-1 gap-2">
            <ul class="flex border-b max-w-max border-gray-300 inline-block">
                <li class="mr-1">
                    <a class="inline-block py-2 px-4 {{ $activeTab === 'model' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500 hover:text-blue-500' }}"
                        href="{{ url('/setting?tab=model') }}">
                        Model
                    </a>
                </li>
                <li class="mr-1">
                    <a class="inline-block py-2 px-4 {{ $activeTab === 'manufacturer' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500 hover:text-blue-500' }}"
                        href="{{ url('/setting?tab=manufacturer') }}">
                        Manufacturer
                    </a>
                </li>
                <li class="mr-1">
                    <a class="inline-block py-2 px-4 {{ $activeTab === 'location' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500 hover:text-blue-500' }}"
                        href="{{ url('/setting?tab=location') }}">
                        Location
                    </a>
                </li>
                <li class="mr-1">
                    <a class="inline-block py-2 px-4 {{ $activeTab === 'category' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500 hover:text-blue-500' }}"
                        href="{{ url('/setting?tab=category') }}">
                        Category
                    </a>
                </li>
                <li class="mr-1">
                    <a class="inline-block py-2 px-4 {{ $activeTab === 'customFields' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500 hover:text-blue-500' }}"
                        href="{{ url('/setting?tab=customFields') }}">
                        Custom Field
                    </a>
                </li>
            </ul>

            <div class="tab-content relative h-[320px] overflow-y-auto">

                <table class="table table-hover">
                    <thead class="sticky top-0">
                        <tr>
                            @if ($activeTab !== 'customFields')
                                <td>Name</td>
                                <td>Description</td>
                                <td>Actions</td>
                            @else
                                <td>Name</td>
                                <td>Type</td>
                                <td>Helper Text</td>
                                <td>Action</td>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($data) && count($data) > 0)
                            @foreach ($data as $key => $dataItem)
                                <tr id="row-{{ $activeTab !== 'customFields' ? $dataItem->id : $key }}">
                                    @if ($activeTab !== 'customFields')
                                        <td class="w-64">
                                            <span class="name-text">{{ $dataItem->name }}</span>
                                            <input type="text" class="name-input" style="display: none"
                                                value="{{ $dataItem->name }}">

                                        </td>
                                        <td class="w-[50%]">
                                            <span class="desc-text">{{ $dataItem->description }}</span>
                                            <input type="text" class="desc-input" style="display: none"
                                                value="{{ $dataItem->description }}">
                                        </td>
                                        <td>
                                            <a class="btn btn-outline-primary edit-btn"
                                                data-row-id="{{ $dataItem->id }}">Edit</a>
                                            <a class="btn btn-outline-success save-btn" data-row-id="{{ $dataItem->id }}"
                                                style="display: none;">Save</a>
                                            <a class="btn btn-outline-secondary cancel-btn"
                                                data-row-id="{{ $dataItem->id }}" style="display: none;">Cancel</a>

                                            <form
                                                action="{{ route('setting.delete', ['tab' => $activeTab, 'id' => $dataItem->id]) }}"
                                                method="post" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger delete-btn">Delete</button>
                                            </form>
                                        </td>
                                    @else
                                        <td>
                                            <span class="name-text">{{ $dataItem->name }}</span>
                                            <input type="text" class="name-input" style="display: none"
                                                value="{{ $dataItem->name }}">

                                        </td>
                                        <td>
                                            <span class="type-text">{{ $dataItem->type }}</span>
                                            <input type="text" class="type-input" style="display: none"
                                                value="{{ $dataItem->type }}">
                                        </td>
                                        <td>
                                            <span class="helper-text">{{ $dataItem->helptext }}</span>
                                            <input type="text" class="helper-input" style="display: none"
                                                value="{{ $dataItem->helptext }}">
                                        </td>
                                        <td>
                                            <a class="btn btn-outline-primary edit-btn"
                                                data-row-id="{{ $key }}">Edit</a>
                                            <a class="btn btn-outline-success save-btn" data-row-id="{{ $key }}"
                                                style="display: none;">Save</a>
                                            <a class="btn btn-outline-secondary cancel-btn"
                                                data-row-id="{{ $key }}" style="display: none;">Cancel</a>

                                            <form
                                                action="{{ route('setting.delete', ['tab' => $activeTab, 'id' => $key]) }}"
                                                method="post" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger delete-btn">Delete</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="{{ $activeTab !== 'customFields' ? 3 : 4 }}">NO DATA FOUND</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="addSetting">
                <form action="{{ route('setting.create', $activeTab) }}" method="post">
                    @csrf
                    <input type="text" id="name" name="nameSet" placeholder="Name" required />
                    @if ($activeTab !== 'customFields')
                        <input type="text" id="decr" name="description" placeholder="description" required />
                    @else
                        <input type="text" id="type" name="type" placeholder="type" required />
                        <input type="text" id="helptxt" name="helptxt" placeholder="Helper Text" required />
                    @endif
                    <button type="submit" class="btn btn-primary">New Setting</button>
                </form>
            </div>
        </div>
    </div>
    <script>
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
                let activeTab = urlParams.get('tab');

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
                fetch(`/setting/update/${activeTab}/${rowId}`, {
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
