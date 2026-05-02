@extends('layouts.admin')
@section('title', 'System Settings')

@section('content')
<div class="admin-card" style="max-width: 600px;">
    <h3 class="admin-card-title" style="margin-bottom: 20px;">Global Configuration</h3>
    
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: var(--text-secondary);">System Announcement</label>
            <textarea name="system_announcement" class="form-control" style="width: 100%; min-height: 100px;" placeholder="Enter announcement to display to all users...">{{ $settings['system_announcement'] ?? '' }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: var(--text-secondary);">Maintenance Mode</label>
            <select name="maintenance_mode" class="form-control" style="width: 100%;">
                <option value="false" {{ ($settings['maintenance_mode'] ?? 'false') === 'false' ? 'selected' : '' }}>Disabled</option>
                <option value="true" {{ ($settings['maintenance_mode'] ?? 'false') === 'true' ? 'selected' : '' }}>Enabled</option>
            </select>
            <small style="color: var(--text-secondary); display: block; margin-top: 5px;">If enabled, normal users will see a maintenance page (requires middleware implementation to enforce).</small>
        </div>

        <button type="submit" class="btn btn-primary"><i class='bx bx-save'></i> Save Settings</button>
    </form>
</div>
@endsection
