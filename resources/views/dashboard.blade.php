<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard — Lumido</title>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"/>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="{{ asset('js/dashboard.js') }}"></script>
  <input type="hidden" id="app-user-name" value="{{ Auth::user()->name }}"/>
  <input type="hidden" id="app-user-email" value="{{ Auth::user()->email }}"/>
  <input type="hidden" id="app-user-avatar" value="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : '' }}"/>
</head>
<body class="dashboard-body" x-data="dashboard()">

@include('dashboard.sidebar')

<!-- MAIN -->
<div class="dash-main">

  <!-- Background orb -->
  <div class="dash-bg">
    <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
      <defs><radialGradient id="og" cx="50%" cy="50%"><stop offset="0%" stop-color="#c4a882"/><stop offset="100%" stop-color="#f7f5f0" stop-opacity="0"/></radialGradient></defs>
      <circle cx="200" cy="200" r="200" fill="url(#og)"/>
    </svg>
  </div>

  @include('dashboard.topbar')

  <!-- Content -->
  <div class="dash-content">

    @if (session('success'))
      <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="toast-success">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <span>{{ session('success') }}</span>
        <button @click="show = false" style="background:none;border:none;color:currentColor;cursor:pointer;margin-left:auto;">✕</button>
      </div>
    @endif

    @include('dashboard.views.my-day')
    @include('dashboard.views.next-7-days')
    @include('dashboard.views.all-tasks')
    @include('dashboard.views.my-lists')
    @include('dashboard.views.tags')

  </div><!-- end dash-content -->
</div><!-- end dash-main -->

@include('dashboard.modals')

</body>
</html>
