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
    <div class="container mt-4">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $activeTab === 'model' ? 'active' : '' }}" href="{{ url('/setting?tab=model') }}" role="tab">Model</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $activeTab === 'manufacturer' ? 'active' : '' }}" href="{{ url('/setting?tab=manufacturer') }}" role="tab">manufacturer</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $activeTab === 'location' ? 'active' : '' }}" href="{{ url('/setting?tab=location') }}" role="tab">location</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $activeTab === 'category' ? 'active' : '' }}" href="{{ url('/setting?tab=category') }}" role="tab">category</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <table>
                <thead>
                    <th>Name</th>
                    <th>description</th>
                    <th>action</th>
                </thead>
                <tbody>
                    @foreach ($data as $data)
                        <tr>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->description }}</td>
                            <td>
                                <a class="btn btn-outline-primary">Edit</a>
                                <a class="btn btn-outline-danger">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
@endsection
