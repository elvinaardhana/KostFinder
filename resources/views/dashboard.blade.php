<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container py-12">
        <div class="card shadow">
            <div class="card-header">
                <h3 class="card-title">Data</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="alert alert-primary" role="alert">
                            <h4><i class="fa-solid fa-location-dot"></i> Total Points</h4>
                            <p style="font-size: 32pt">{{ $total_points }}</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="alert alert-success" role="alert">
                            <h4><i class="fa-solid fa-route"></i> Total Polylines</h4>
                            <p style="font-size: 32pt">{{ $total_polylines }}</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="alert alert-warning" role="alert">
                            <h4><i class="fa-solid fa-draw-polygon"></i> Total Polygons</h4>
                            <p style="font-size: 32pt">{{ $total_polygons }}</p>
                        </div>
                    </div>
                </div>

                <hr>
                <p>Anda login sebagai <b>{{ Auth::user()->name }}</b> dengan email <i>{{ Auth::user()->email }}</i></p>
                </hr>
            </div>
        </div>

        <!-- Chart Container Card -->
        <div class="card shadow mt-4">
            <div class="card-header">
                <h3 class="card-title">Jumlah Tempat Ibadah</h3>
            </div>
            <div class="card-body">
                <canvas id="religionChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Load Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart.js Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('religionChart').getContext('2d');
            var religionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Masjid', 'Gereja Kristen', 'Gereja Katolik', 'Klenteng', 'Pura', 'Vihara'],
                    datasets: [{
                        label: 'Jumlah Tempat Ibadah',
                        data: [
                            {{ $religion_counts['islam'] }},
                            {{ $religion_counts['kristen'] }},
                            {{ $religion_counts['katolik'] }},
                            {{ $religion_counts['konghucu'] }},
                            {{ $religion_counts['hindu'] }},
                            {{ $religion_counts['buddha'] }}
                        ],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
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
        });
    </script>
</x-app-layout>
