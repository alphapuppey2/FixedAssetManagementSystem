@props(["header"])

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
           Dashboard
        </h2>
    </x-slot>

    <div class="contents">
        <div class="content1">
            <div class="CardGroup">
                {{-- Cards --}}
                <div class="container text-center">
                   <div class="row gx-5">
                    <div class="card col">
                        <div class="card-header">
                            Asset
                        </div>
                        <div class="card-body">
                            500
                        </div>
                    </div>
                    <div class="card col">
                        <div class="card-header">
                            Asset
                        </div>
                        <div class="card-body">
                            500
                        </div>
                    </div>
                    <div class="card col">
                        <div class="card-header">
                            Asset
                        </div>
                        <div class="card-body">
                            500
                        </div>
                    </div>
                    <div class="card col">
                        <div class="card-header">
                            Asset
                        </div>
                        <div class="card-body">
                            500
                        </div>
                    </div>
                   </div>
                </div>
            </div>
            {{-- Recent Activity --}}
            <div class="table">

            </div>
        </div>
    </div>
</x-app-layout>
