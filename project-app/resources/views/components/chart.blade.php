<div>
    <canvas id="myChart"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
      // Accessing the PHP variables passed to the component
    const weeks = @json($weeks);
    const activeCounts = @json($activeCounts);
    const maintenanceCounts = @json($maintenanceCounts);

    const ctx = document.getElementById('myChart').getContext('2d');
    const assetStatusChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: weeks,
            datasets: [
                {
                    label: 'Active Assets',
                    data: activeCounts,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false
                },
                {
                    label: 'Under Maintenance Assets',
                    data: maintenanceCounts,
                    borderColor: 'rgba(255, 165, 0, 1)',
                    backgroundColor: 'rgba(255, 165, 0, 0.2)',
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true // Ensures the y-axis starts at 0
                }
            }
        }
    });
</script>
