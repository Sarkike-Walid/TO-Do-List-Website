@extends('layouts.admin')
@section('title', 'User Management')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="admin-card-title">All Users</h3>
        <form action="{{ route('admin.users') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="" class="avatar-sm">
                            @else
                                <div class="avatar-sm" style="background: var(--border); display:flex; align-items:center; justify-content:center;">
                                    <i class='bx bx-user' style="color:var(--text-secondary)"></i>
                                </div>
                            @endif
                            <span>{{ $user->name }}</span>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex">
                            @if($user->email !== 'admin@lumido.com')
                            <form action="{{ route('admin.users.role', $user) }}" method="POST" onsubmit="confirmAction(event, 'Change role for {{ addslashes($user->name) }}?')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="role" value="{{ $user->role === 'admin' ? 'user' : 'admin' }}">
                                <button type="submit" class="btn btn-sm btn-secondary">
                                    Toggle Role
                                </button>
                            </form>
                            @else
                                <span class="badge badge-admin" style="margin-right: 10px;"><i class='bx bxs-crown'></i> Super Admin</span>
                            @endif
                            @if($user->id !== auth()->id() && $user->email !== 'admin@lumido.com')
                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="confirmAction(event, 'Are you sure you want to delete {{ addslashes($user->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{ $users->links('pagination::bootstrap-5') }}
</div>
@endsection
