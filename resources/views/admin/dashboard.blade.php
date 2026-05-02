@extends('layouts.admin')
@section('title', 'Overview')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Total Users</div>
        <div class="stat-value">{{ $totalUsers }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Active Users (7 Days)</div>
        <div class="stat-value">{{ $activeUsers }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Total Tasks</div>
        <div class="stat-value">{{ $totalTasks }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Completed Tasks</div>
        <div class="stat-value" style="color: var(--success);">{{ $completedTasks }}</div>
    </div>
</div>

<div class="admin-card">
    <h3 class="admin-card-title" style="margin-bottom: 20px;">Task Completion Overview</h3>
    <div style="height: 300px; width: 100%; max-width: 500px;">
        <canvas id="tasksChart"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('tasksChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending'],
                datasets: [{
                    data: [{{ $completedTasks }}, {{ $pendingTasks }}],
                    backgroundColor: ['#10b981', '#f59e0b'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { color: '#f8fafc' }
                    }
                }
            }
        });
    });
</script>
@endsection
