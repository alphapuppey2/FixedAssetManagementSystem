@extends('layouts.app')

@section('header')
   <h2 class="font-semibold inline-block text-xl text-center text-gray-800 leading-tight flex w-24">
        Asset
   </h2>
   <div class="divider">></div>
   <h2 class="inline-block  text-center w-24">
    {{ $asset->assetCode }}

   </h2>
   <div class="divider mr-4">></div>
   <h2 class=" text-center">
        Maintenance History

   </h2>
@endsection

@section('content')

@endsection
