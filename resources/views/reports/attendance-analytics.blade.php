@extends('layouts.app')

@section('title', '📊 Attendance Analytics')

@push('styles')
<style>
    body {
        background-image: url('/images/background2.png');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        font-family: 'Poppins', sans-serif;
    }

    .glass {
        background-color: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    canvas {
        background-color: rgba(255, 255, 255, 0.03);
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="relative min-h-screen bg-gray-900 text-white">
    <div class="absolute inset-0 bg-cover bg-center opacity-80 z-0"
         style="background-image: url('{{ asset('images/background2.png') }}');"></div>

    <div class="relative z-10 max-w-6xl mx-auto p-6 font-[Poppins]">
        <h1 class="text-3xl font-bold mb-6">📊 Attendance Analytics</h1>

        {{-- Optional filters can go here --}}

        <div class="grid gap-6 md:grid-cols-2">
            {{-- Attendance % Per Class --}}
            <div class="glass shadow rounded-xl p-6">
                <h2 class="text-xl font-semibold mb-4">📈 Attendance % Per Class</h2>
                <canvas id="attendanceChart" class="w-full h-60"></canvas>
            </div>

            {{-- Most Active Students --}}
            <div class="glass shadow rounded-xl p-6">
                <h2 class="text-xl font-semibold mb-4">🏆 Most Active Students</h2>
                <canvas id="studentChart" class="w-full h-60"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Attendance chart
    const attendanceChart = new Chart(
        document.getElementById('attendanceChart'),
        {
            type: 'bar',
            data: {
                labels: {!! json_encode($classes->pluck('title')) !!},
                datasets: [{
                    label: 'Attendance %',
                    data: {!! json_encode($classes->pluck('attendance_percent')) !!},
                    backgroundColor: 'rgba(96,165,250,0.7)', // blue-400
                    borderColor: 'rgba(59,130,246,1)',       // blue-500
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                plugins: {
                    legend: { labels: { color: 'white' } }
                },
                scales: {
                    x: { ticks: { color: 'white' }, grid: { color: 'rgba(255,255,255,0.1)' }},
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { color: 'white' },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    }
                }
            }
        }
    );

    // Student chart
    const studentChart = new Chart(
        document.getElementById('studentChart'),
        {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($activeStudents->pluck('user.name')) !!},
                datasets: [{
                    label: 'Attendance Count',
                    data: {!! json_encode($activeStudents->pluck('attendance_count')) !!},
                    backgroundColor: [
                        'rgba(96,165,250,0.7)',
                        'rgba(147,197,253,0.7)',
                        'rgba(191,219,254,0.7)',
                        'rgba(219,234,254,0.7)',
                        'rgba(239,246,255,0.7)'
                    ],
                    borderColor: 'rgba(59,130,246,1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        labels: { color: 'white' }
                    }
                }
            }
        }
    );
</script>
@endpush
