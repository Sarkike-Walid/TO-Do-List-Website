@extends('layouts.admin')
@section('title', 'Global Tasks Management')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="admin-card-title">All Tasks</h3>
        <form action="{{ route('admin.tasks') }}" method="GET" class="d-flex">
            <select name="status" class="form-control">
                <option value="">All Statuses</option>
                <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }}>Pending</option>
                <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Completed</option>
            </select>
            <input type="text" name="search" class="form-control" placeholder="Search tasks" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Task</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->user_name }} ({{ $task->user_email }})</td>
                    <td>
                        <span class="badge badge-{{ $task->status }}">{{ ucfirst($task->status) }}</span>
                    </td>
                    <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($task->created_at)->format('M d, Y') }}</td>
                    <td>
                        <form action="{{ route('admin.tasks.delete', $task->id) }}" method="POST" onsubmit="confirmAction(event, 'Are you sure you want to delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class='bx bx-trash'></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{ $tasks->links('pagination::bootstrap-5') }}
</div>
@endsection
