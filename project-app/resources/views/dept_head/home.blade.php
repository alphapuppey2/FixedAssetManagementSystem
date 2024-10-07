@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ 'Dashboard' }}
    </h2>
@endsection
@section('content')
    <div class="contents relative flex ">
        {{-- Cards --}}
        <div class="text-center max-w-100 flex justify-center sm:flex-col md:flex-row ">
            <div class="card  w-100">
                <div class="card-header">
                    Active
                </div>
                <div class="card-body">
                    {{ $asset['active'] }}
                </div>
            </div>
            <div class="card  w-100">
                <div class="card-header">
                    Under Maintenance
                </div>
                <div class="card-body">
                    {{ $asset['um'] }}
                </div>
            </div>
            <div class="card  w-100">
                <div class="card-header">
                    deployed
                </div>
                <div class="card-body">
                    {{ $asset['deploy'] }}
                </div>
            </div>
            <div class="card  w-100">
                <div class="card-header">
                    disposed
                </div>
                <div class="card-body">
                    {{ $asset['dispose'] }}
                </div>

            </div>
        </div>
        {{-- Recent Activity --}}

        <div class="w-[50%]">
            <canvas id="myChart"></canvas>
        </div>
    @endsection
    @section('JS')
        <script>
            const ctx = document.getElementById('myChart');

            const labels = ["Active", 'Under Maintenance', 'Deployed', 'deposed'];
            const data = {
                labels: labels,
                datasets: [{
                        label: 'My First Dataset',
                        data: ['2024-10-6', '2024-11-6', '2024-12-6', '2024-1-6'],
                        fill: false,
                        borderColor: '#0e8900',
                        tension: 0.1
                    },
                    {
                        label: 'My second Dataset',
                        data: ['2024-10-6', '2024-11-6', '2024-12-6', '2024-1-6'],
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }
                ]
            };

            new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endsection
