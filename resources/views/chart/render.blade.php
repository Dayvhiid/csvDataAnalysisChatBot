<h2>{{ ucfirst($type) }} Chart: {{ $yKey }} vs {{ $xKey }}</h2>

<canvas id="chart" width="800" height="400"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chart').getContext('2d');
    const chart = new Chart(ctx, {
        type: '{{ $type }}',
        data: {
            labels: {!! json_encode($xData) !!},
            datasets: [{
                label: '{{ $yKey }}',
                data: {!! json_encode($yData) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                title: { display: true, text: 'Chart View' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
