<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login — Lumido</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <script>
    if (localStorage.getItem('lumido_dark') === 'true') {
      document.documentElement.setAttribute('data-theme', 'dark');
    }
    function toggleTheme() {
      if (document.documentElement.getAttribute('data-theme') === 'dark') {
        document.documentElement.removeAttribute('data-theme');
        localStorage.setItem('lumido_dark', 'false');
      } else {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('lumido_dark', 'true');
      }
    }
  </script>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: #f7f5f0;
      color: #2a2a2a;
      overflow-x: hidden;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .lumido-navbar {
      background: rgba(247,245,240,0.92) !important;
      backdrop-filter: blur(10px);
      border-bottom: 0.5px solid rgba(0,0,0,0.06);
      padding-top: 0.9rem;
      padding-bottom: 0.9rem;
    }

    .lumido-navbar .nav-link {
      font-size: 14px;
      font-weight: 400;
      color: #555 !important;
      letter-spacing: 0.02em;
      padding: 8px 16px !important;
      border-radius: 40px;
      transition: color 0.2s, background 0.2s;
    }

    .lumido-navbar .nav-link:hover {
      color: #1a1a1a !important;
      background: rgba(0,0,0,0.04);
    }

    .lumido-navbar .nav-login {
      border: 1px solid rgba(0,0,0,0.15);
      color: #333 !important;
    }

    .lumido-navbar .nav-cta {
      background: #1a1a1a !important;
      color: #f7f5f0 !important;
      border-radius: 40px;
      padding: 8px 22px !important;
      font-weight: 500;
    }

    .auth-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 4rem 2rem;
      position: relative;
    }

    .auth-orb {
      position: absolute;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(196,168,130,0.15) 0%, transparent 70%);
      border-radius: 50%;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      pointer-events: none;
      z-index: 0;
    }

    .auth-card {
      background: #fff;
      border-radius: 24px;
      padding: 3.5rem 3rem;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 12px 40px rgba(0,0,0,0.04);
      border: 0.5px solid rgba(0,0,0,0.08);
      position: relative;
      z-index: 1;
      animation: fadeUp 0.5s ease both;
    }

    .auth-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 36px;
      font-weight: 300;
      color: #1a1a1a;
      margin-bottom: 0.5rem;
      text-align: center;
    }

    .auth-sub {
      text-align: center;
      font-size: 14px;
      color: #777;
      margin-bottom: 2.5rem;
      font-weight: 300;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: #888;
      margin-bottom: 0.5rem;
      display: block;
    }

    .form-control {
      background: #faf9f7;
      border: 1px solid rgba(0,0,0,0.08);
      border-radius: 12px;
      padding: 14px 16px;
      font-size: 14px;
      color: #2a2a2a;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
      outline: none;
      border-color: #c4a882;
      box-shadow: 0 0 0 4px rgba(196,168,130,0.1);
      background: #fff;
    }

    .forgot-link {
      font-size: 12px;
      color: #8a6d4a;
      text-decoration: none;
      float: right;
      margin-top: 6px;
      transition: color 0.2s;
    }

    .forgot-link:hover {
      color: #c4a882;
    }

    .btn-auth {
      background: #1a1a1a;
      color: #f7f5f0;
      padding: 16px;
      border-radius: 40px;
      font-size: 14px;
      font-weight: 500;
      width: 100%;
      border: none;
      cursor: pointer;
      transition: background 0.2s, transform 0.2s;
      margin-top: 1rem;
    }

    .btn-auth:hover {
      background: #333;
      transform: translateY(-1px);
    }

    .auth-footer {
      text-align: center;
      margin-top: 2rem;
      font-size: 14px;
      color: #666;
    }

    .auth-footer a {
      color: #1a1a1a;
      font-weight: 500;
      text-decoration: none;
      border-bottom: 1px solid rgba(0,0,0,0.2);
      padding-bottom: 1px;
      transition: border-color 0.2s;
    }

    .auth-footer a:hover {
      border-color: #1a1a1a;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Dark Mode Overrides */
    [data-theme="dark"] body { background: #0f1115; color: #ddd; }
    [data-theme="dark"] .lumido-navbar { background: rgba(15,17,21,0.92) !important; border-bottom: 0.5px solid #1e2430; }
    [data-theme="dark"] .navbar-brand span { color: #fff !important; }
    [data-theme="dark"] .nav-link { color: #888 !important; }
    [data-theme="dark"] .nav-link:hover { color: #fff !important; background: rgba(255,255,255,0.05); }
    [data-theme="dark"] .nav-login { border-color: rgba(255,255,255,0.15); color: #ddd !important; }
    [data-theme="dark"] .nav-cta { background: #3b9aed !important; color: #fff !important; }
    [data-theme="dark"] .nav-cta:hover { background: #2080e8 !important; }
    
    [data-theme="dark"] .auth-card { background: #141820; border-color: #1e2430; box-shadow: 0 12px 40px rgba(0,0,0,0.4); }
    [data-theme="dark"] .auth-title { color: #fff; }
    [data-theme="dark"] .auth-sub { color: #888; }
    [data-theme="dark"] .form-control { background: #0f1115; border-color: #1e2430; color: #ddd; }
    [data-theme="dark"] .form-control:focus { border-color: #3b9aed; box-shadow: 0 0 0 4px rgba(59,154,237,0.1); background: #0f1115; }
    [data-theme="dark"] .btn-auth { background: #3b9aed; color: #fff; }
    [data-theme="dark"] .btn-auth:hover { background: #2080e8; }
    [data-theme="dark"] .forgot-link { color: #3b9aed; }
    [data-theme="dark"] .forgot-link:hover { color: #2080e8; }
    [data-theme="dark"] .auth-footer { color: #888; }
    [data-theme="dark"] .auth-footer a { color: #ddd; border-color: rgba(255,255,255,0.2); }
    [data-theme="dark"] .auth-footer a:hover { border-color: #fff; color: #fff; }
    
    .error-msg {
      color: #e74c3c;
      font-size: 12px;
      margin-top: 6px;
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    .theme-toggle { background: none; border: none; cursor: pointer; color: #555; padding: 8px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: 0.2s; }
    .theme-toggle:hover { background: rgba(0,0,0,0.04); color: #1a1a1a; }
    [data-theme="dark"] .theme-toggle { color: #888; }
    [data-theme="dark"] .theme-toggle:hover { background: rgba(255,255,255,0.05); color: #fff; }
    .moon-icon { display: none; }
    [data-theme="dark"] .sun-icon { display: none; }
    [data-theme="dark"] .moon-icon { display: block; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg lumido-navbar sticky-top">
  <div class="container-xl px-5">
    <a class="navbar-brand d-flex align-items-center p-0" href="/home" style="gap: 10px;">
      <img src="{{ asset('assets/logo.png') }}" alt="Lumido" style="height: 45px; width: auto; display: block;" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
      <span style="font-family: 'DM Sans', sans-serif; font-weight: 700; font-size: 28px; color: #1a1a1a; letter-spacing: -0.8px; line-height: 1;">Lumido</span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navMenu">
      <ul class="navbar-nav align-items-center gap-1">
        <li class="nav-item"><a class="nav-link" href="home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="Features">Features</a></li>
        <li class="nav-item"><a class="nav-link nav-login" href="login">Login</a></li>
        <li class="nav-item"><a class="nav-link nav-cta" href="sign">Get started</a></li>
        <li class="nav-item">
          <button class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark Mode">
            <svg class="sun-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
            <svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
          </button>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="auth-container">
  <div class="auth-orb"></div>
  <div class="auth-card">
    <h1 class="auth-title">Welcome back</h1>
    <p class="auth-sub">Continue your journey of mindful productivity.</p>
    
    <form action="{{ route('login') }}" method="POST">
      @csrf
      <div class="form-group">
        <label class="form-label">Email address</label>
        <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
        @error('email') <div class="error-msg">{{ $message }}</div> @enderror
      </div>
      
      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
        <div style="clear:both;"></div>
        @error('password') <div class="error-msg">{{ $message }}</div> @enderror
      </div>
      
      <button type="submit" class="btn-auth">Sign In</button>
    </form>
    
    <div class="auth-footer">
      Don't have an account? <a href="sign">Create one</a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>