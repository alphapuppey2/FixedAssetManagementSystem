<!-- resources/views/dept_head/createMaintenance.blade.php -->
@extends('layouts.app')

@section('header')
    <h2 class="my-3 font-semibold text-2xl text-black-800 leading-tight">Create New Maintenance</h2>
@endsection

@section('content')
    <div class="px-6 py-4">
        <form action="" method="POST">
            @csrf
            <div class="grid grid-cols-3 gap-6">
                <!-- Asset Code, Asset Name, Model -->
                <div class="col-span-1 grid grid-cols-1 gap-4">
                    <div>
                        <label for="asset_code" class="block text-sm font-medium text-gray-700">Asset Code</label>
                        <select name="asset_code" id="asset_code" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">TST-0001</option>
                        </select>
                    </div>

                    <div>
                        <label for="asset_name" class="block text-sm font-medium text-gray-700">Asset Name</label>
                        <select name="asset_name" id="asset_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Nameless</option>
                        </select>
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                        <select name="model" id="model" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Model 1</option>
                        </select>
                    </div>
                </div>

                <!-- Category, Location, Manufacturer -->
                <div class="col-span-1 grid grid-cols-1 gap-4">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="laptop">Laptop</option>
                        </select>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <select name="location" id="location" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="Cebu - Plant 1">Cebu - Plant 1</option>
                        </select>
                    </div>

                    <div>
                        <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
                        <select name="manufacturer" id="manufacturer" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="HP">HP</option>
                        </select>
                    </div>
                </div>

                <!-- Image -->
                <div class="col-span-1 grid grid-cols-1 gap-4">
                    <div class="col-span-1 flex items-center justify-center">
                        <img src="https://via.placeholder.com/150" alt="Asset Image" class="rounded-md shadow-md">
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-3 gap-6 mt-6">
                <!-- Cost -->
                <div class="col-span-2 grid grid-cols-1 gap-4">
                    <div>
                        <label for="cost" class="block text-sm font-medium text-gray-700">Cost</label>
                        <input type="text" id="cost" name="cost" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="10,000.00 PHP">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 mt-6">
                <!-- Frequency -->
                <div class="col-span-1">
                    <label for="frequency" class="block text-sm font-medium text-gray-700">Frequency</label>
                    <select name="frequency" id="frequency" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="custom">Custom</option>
                    </select>
                </div>

                <!-- Repeat every -->
                <div class="col-span-1">
                    <label for="repeat" class="block text-sm font-medium text-gray-700">Repeat every</label>
                    <div class="flex items-center space-x-2">
                        <input type="number" id="repeat" name="repeat" class="block w-1/3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="5">
                        <select name="interval" id="interval" class="block w-2/3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="days">Days</option>
                            <option value="days">Weeks</option>
                            <option value="days">Months</option>
                            <option value="days">Years</option>
                        </select>
                    </div>
                </div>

                <!-- Ends -->
                <div class="col-span-3">
                    <label for="ends" class="block text-sm font-medium text-gray-700">Ends</label>
                    <div class="py-3">
                        <div>
                            <input type="radio" id="never" name="ends" value="never" class="mr-2">
                            <label for="never" class="text-gray-700">Never</label>
                        </div>
                        <div>
                            <input type="radio" id="after" name="ends" value="after" class="mr-2">
                            <label for="after" class="text-gray-700">After</label>
                            <input type="number" id="occurrence" name="occurrence" class="ml-2 w-16 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="5">
                            <span class="text-gray-700">occurrences</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save and Cancel buttons -->
            <div class="flex justify-end mt-6 space-x-4">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600">Save</button>
                <a href="{{ route('maintenance_sched') }}" class="px-4 py-2 bg-red-500 text-white rounded-md shadow hover:bg-red-600">Cancel</a>
            </div>
        </form>
    </div>
@endsection
