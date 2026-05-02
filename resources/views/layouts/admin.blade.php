<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ToDo List</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</head>
<body>

    <aside class="admin-sidebar">
        <div class="brand">AdminPanel</div>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class='bx bxs-dashboard'></i> Overview
            </a>
            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class='bx bxs-user-detail'></i> Users
            </a>
            <a href="{{ route('admin.tasks') }}" class="{{ request()->routeIs('admin.tasks') ? 'active' : '' }}">
                <i class='bx bx-task'></i> Tasks
            </a>
            <a href="{{ route('admin.logs') }}" class="{{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                <i class='bx bx-list-ul'></i> Logs
            </a>
            <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class='bx bxs-cog'></i> Settings
            </a>
            <a href="{{ route('dashboard') }}" style="margin-top: 20px; border-top: 1px solid var(--border); padding-top: 20px;">
                <i class='bx bx-arrow-back'></i> Back to App
            </a>
        </nav>
    </aside>

    <main class="admin-main">
        <header class="admin-header">
            <div class="admin-header-title">@yield('title')</div>
            <div class="admin-user">
                <button onclick="toggleAdminTheme()" style="background:none;border:none;cursor:pointer;color:var(--text-secondary);font-size:20px;display:flex;align-items:center;padding:0;">
                    <i class='bx bx-moon'></i>
                </button>
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm"><i class='bx bx-log-out'></i> Logout</button>
                </form>
            </div>
        </header>

        <div class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="{{ asset('js/admin.js') }}"></script>
    <script>
        function toggleAdminTheme() {
            const current = localStorage.getItem('theme') || 'light';
            const newTheme = current === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', newTheme);
            if (newTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.removeAttribute('data-theme');
            }
        }
    </script>

    <!-- Custom Confirm Modal -->
    <div id="custom-confirm-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
        <div class="admin-card" style="width: 380px; padding: 30px; text-align: center; margin: 0; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <i class='bx bx-error-circle' style="font-size: 60px; color: var(--danger); margin-bottom: 15px; opacity: 0.8;"></i>
            <h3 style="margin-bottom: 10px; font-family: 'Cormorant Garamond', serif; font-size: 26px; font-weight: 600;">Are you sure?</h3>
            <p id="custom-confirm-message" style="color: var(--text-secondary); margin-bottom: 30px; font-size: 15px;"></p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" class="btn btn-secondary" onclick="closeConfirmModal()" style="flex: 1; justify-content: center; padding: 10px;">Cancel</button>
                <button type="button" class="btn btn-danger" id="custom-confirm-btn" style="flex: 1; justify-content: center; padding: 10px; background-color: var(--danger); color: #fff;">Yes, confirm</button>
            </div>
        </div>
    </div>

    <script>
        let formToSubmit = null;
        
        function confirmAction(event, message) {
            event.preventDefault();
            formToSubmit = event.target;
            document.getElementById('custom-confirm-message').innerText = message;
            document.getElementById('custom-confirm-modal').style.display = 'flex';
        }

        function closeConfirmModal() {
            document.getElementById('custom-confirm-modal').style.display = 'none';
            formToSubmit = null;
        }

        document.getElementById('custom-confirm-btn').addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
