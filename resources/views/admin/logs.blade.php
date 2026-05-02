@extends('layouts.admin')
@section('title', 'Activity Logs')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="admin-card-title">System Activity</h3>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Date/Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->user ? $log->user->name : 'System/Deleted User' }}</td>
                    <td><span class="badge badge-user">{{ $log->action }}</span></td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                    <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{ $logs->links('pagination::bootstrap-5') }}
</div>
@endsection
