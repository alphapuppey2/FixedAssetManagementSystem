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
            </ul>

            <div class="tab-content relative h-[320px] overflow-y-auto">
                <table class="table table-hover">
                    <thead class="sticky top-0">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $dataItem)
                            <tr id="row-{{ $dataItem->id }}">
                                <td class="w-64">{{ $dataItem->name }}</td>
                                <td class="w-[50%]">
                                    <span class="desc-text">{{ $dataItem->description }}</span>
                                    <input type="text" class="desc-input" style="display: none;"
                                        value="{{ $dataItem->description }}">
                                </td>
                                <td>
                                    <a class="btn btn-outline-primary edit-btn" data-row-id="{{ $dataItem->id }}">Edit</a>
                                    <a class="btn btn-outline-success save-btn" data-row-id="{{ $dataItem->id }}"
                                        style="display: none;">Save</a>
                                    <a class="btn btn-outline-secondary cancel-btn" data-row-id="{{ $dataItem->id }}"
                                        style="display: none;">Cancel</a>

                                    <form
                                        action="{{ route('setting.delete', ['tab' => $activeTab, 'id' => $dataItem->id]) }}"
                                        method="post" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger delete-btn">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="addSetting">
                <form action="{{ route('setting.create', $activeTab) }}" method="post">
                    @csrf
                    <input type="text" id="name" name="nameSet" placeholder="Name" />
                    <input type="text" id="decr" name="description" placeholder="description" />

                    <button type="submit" class="btn btn-primary">New Setting</button>
                </form>
            </div>
        </div>

    </div>
    <script>
        document.querySelectorAll('.edit-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const rowId = this.getAttribute('data-row-id');
                const row = document.getElementById('row-' + rowId);

                row.querySelector('.desc-text').classList.toggle('hidden');
                row.querySelector('.desc-input').style.display = 'inline-block';
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

                row.querySelector('.desc-text').classList.toggle('hidden');
                row.querySelector('.desc-input').style.display = 'none';
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
                const newValue = row.querySelector('.desc-input').value;

                // Get the active tab
                const urlParams = new URLSearchParams(window.location.search);
                let activeTab = urlParams.get('tab');

                if (activeTab === null) {
                    activeTab = 'model';
                }

                // AJAX call to save the new description
                fetch(`/setting/update/${activeTab}/${rowId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            description: newValue
                        })
                    })
                    .then(response => {
                        // Check if the response is JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json(); // Parse the JSON if it's valid
                        } else {
                            throw new Error('Server returned non-JSON response');
                        }


                    })
                    .then(data => {

                        if (data.success) {
                            row.querySelector('.desc-text').textContent = newValue;
                            row.querySelector('.desc-text').style.display = 'inline-block';
                            row.querySelector('.desc-input').style.display = 'none';
                            row.querySelector('.save-btn').style.display = 'none';
                            row.querySelector('.cancel-btn').style.display = 'none';
                            row.querySelector('.edit-btn').style.display = 'inline-block';
                            row.querySelector('.delete-btn').style.display = 'inline-block';

                        } else {
                            alert('Failed to update description');
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
