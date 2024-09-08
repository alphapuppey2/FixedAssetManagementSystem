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

<div class="cont">
    @if ($errors->any())
    <div class="alert alert-danger">
        {{dd($errors)}}
    </div>
@endif

        <div class="container mt-4">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $activeTab === 'model' ? 'active' : '' }}" href="{{ url('/setting?tab=model') }}"
                        role="tab">Model</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $activeTab === 'manufacturer' ? 'active' : '' }}"
                        href="{{ url('/setting?tab=manufacturer') }}" role="tab">manufacturer</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $activeTab === 'location' ? 'active' : '' }}"
                        href="{{ url('/setting?tab=location') }}" role="tab">location</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $activeTab === 'category' ? 'active' : '' }}"
                        href="{{ url('/setting?tab=category') }}" role="tab">category</a>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <table>
                    <thead>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $dataItem)
                            <tr id="row-{{ $dataItem->id }}">
                                <td>{{ $dataItem->name }}</td>
                                <td>
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
                                        method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger delete-btn">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="addSetting">
                    <form action="{{ route('setting.create', $activeTab) }}" method="post">
                        @csrf
                        <input type="text" id="name" name="nameSet" placeholder="Name" />
                        <input type="text" id="decr" name="description" placeholder="description" />

                        <button type="submit" class="btn btn-primary">New Setting</button>
                    </form>
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
                        let activeTab = urlParams.get('tab'); // This will get the value 'model'

                        if (activeTab === null) {
                            activeTab = 'model';
                        }

                        // AJAX call to save the new description
                        fetch(`/setting/update/${activeTab}/${rowId}`, { // Correct URL construction
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
