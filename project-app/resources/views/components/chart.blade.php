<div>
    <canvas id="myChart"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

     // Get the data passed from the controller
     const months = @json($Amonths);
        const counts = @json($Acounts);

        // Render the chart
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line', // You can also use 'line', 'pie', etc.
            data: {
                labels: months, // X-axis labels (months)
                datasets: [{
                    label: 'Number of Records',
                    data: counts, // Y-axis data (counts)
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                },
                {
                    label: 'Maintenance',
                    data: [10,5,2,6,5,7,9,8,2,10,0,0], // Y-axis data (counts)
                    backgroundColor: 'rgba(255,228,74,0.9)',
                    borderColor: 'rgba(255,228,74)',
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

//    // Function to fetch data from the Laravel backend
// async function fetchChartData() {
//     try {
//         const response = await fetch('http://127.0.0.1:8000/asset/graph');
//         const data = await response.json();
//         return data;
//     } catch (error) {
//         console.error('Error fetching data:', error);
//     }
// }

// // Function to render the Chart
// async function renderChart() {
//     const chartData = await fetchChartData();

//     if (!chartData) {
//         console.error('No data available to render the chart.');
//         return;
//     }

//     const ctx = document.getElementById('myChart').getContext('2d');

//     const myChart = new Chart(ctx, {
//         type: 'line', // You can also use 'line', 'pie', etc.
//         data: {
//             labels: chartData.months, // X-axis labels (months)
//             datasets: [{
//                 label: 'New Assets',
//                 data: chartData.counts, // Y-axis data (counts)
//                 backgroundColor: 'rgba(75, 192, 192, 0.2)',
//                 borderColor: 'rgba(75, 192, 192, 1)',
//                 borderWidth: 1
//             }]
//         },
//         options: {
//             scales: {
//                 y: {
//                     beginAtZero: true
//                 }
//             }
//         }
//     });
// }

// // Call the renderChart function to display the chart
// renderChart();

</script>

