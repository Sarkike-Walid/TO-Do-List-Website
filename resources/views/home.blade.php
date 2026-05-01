<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lumido — Do it with clarity</title>
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
    }

    .lumido-navbar {
      background: rgba(247,245,240,0.92) !important;
      backdrop-filter: blur(10px);
      border-bottom: 0.5px solid rgba(0,0,0,0.06);
      padding-top: 0.9rem;
      padding-bottom: 0.9rem;
      font-family: 'DM Sans', sans-serif;
    }

    .lumido-navbar .navbar-brand img {
      height: 42px;
      width: auto;
      object-fit: contain;
    }

    .logo-fallback {
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: 'Cormorant Garamond', serif;
      font-size: 22px;
      font-weight: 400;
      letter-spacing: 0.04em;
      color: #1a1a1a;
    }

    .logo-mark {
      width: 36px;
      height: 36px;
      background: #c4a882;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
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

    .lumido-navbar .nav-login:hover {
      border-color: rgba(0,0,0,0.3);
      background: rgba(0,0,0,0.03) !important;
    }

    .lumido-navbar .nav-cta {
      background: #1a1a1a !important;
      color: #f7f5f0 !important;
      border-radius: 40px;
      padding: 8px 22px !important;
      font-weight: 500;
    }

    .lumido-navbar .nav-cta:hover {
      background: #333 !important;
      color: #f7f5f0 !important;
    }

    .hero {
      min-height: 90vh;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
      padding: 4rem 2rem;
      position: relative;
      overflow: hidden;
    }

    .hero-bg-orb {
      position: absolute;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(196,168,130,0.18) 0%, transparent 70%);
      border-radius: 50%;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      pointer-events: none;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(196,168,130,0.15);
      border: 0.5px solid rgba(196,168,130,0.4);
      color: #8a6d4a;
      font-size: 12px;
      letter-spacing: 0.08em;
      padding: 6px 16px;
      border-radius: 40px;
      margin-bottom: 2.5rem;
      text-transform: uppercase;
      animation: fadeUp 0.6s ease both;
    }

    .hero h1 {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(52px, 8vw, 96px);
      font-weight: 300;
      line-height: 1.05;
      color: #1a1a1a;
      letter-spacing: -0.02em;
      max-width: 760px;
      margin-bottom: 1.5rem;
      animation: fadeUp 0.6s ease 0.1s both;
    }

    .hero h1 em { font-style: italic; color: #c4a882; }

    .hero-sub {
      font-size: 17px;
      color: #666;
      font-weight: 300;
      max-width: 440px;
      line-height: 1.7;
      margin-bottom: 3rem;
      animation: fadeUp 0.6s ease 0.2s both;
    }

    .hero-actions {
      display: flex;
      gap: 1rem;
      align-items: center;
      animation: fadeUp 0.6s ease 0.3s both;
    }

    .btn-primary {
      background: #1a1a1a;
      color: #f7f5f0;
      padding: 14px 32px;
      border-radius: 40px;
      font-size: 14px;
      font-weight: 500;
      text-decoration: none;
      font-family: 'DM Sans', sans-serif;
      transition: transform 0.2s, background 0.2s;
      cursor: pointer;
      border: none;
    }

    .btn-primary:hover { background: #333; transform: translateY(-1px); }

    .btn-ghost {
      color: #555;
      font-size: 14px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: color 0.2s;
      cursor: pointer;
      background: none;
      border: none;
      font-family: 'DM Sans', sans-serif;
    }

    .btn-ghost:hover { color: #1a1a1a; }

    .preview-section {
      padding: 2rem 3rem 5rem;
      display: flex;
      justify-content: center;
    }

    .app-preview {
      background: #fff;
      border-radius: 20px;
      border: 0.5px solid rgba(0,0,0,0.08);
      padding: 2rem;
      max-width: 520px;
      width: 100%;
      box-shadow: 0 8px 60px rgba(0,0,0,0.06);
      animation: fadeUp 0.6s ease 0.4s both;
    }

    .app-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 0.5px solid rgba(0,0,0,0.06);
    }

    .app-date { font-size: 12px; color: #aaa; letter-spacing: 0.06em; text-transform: uppercase; }
    .app-title-sm { font-family: 'Cormorant Garamond', serif; font-size: 18px; color: #1a1a1a; }

    .task-list { display: flex; flex-direction: column; gap: 10px; }

    .task-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 14px;
      border-radius: 12px;
      background: #faf9f7;
      border: 0.5px solid rgba(0,0,0,0.05);
      transition: background 0.2s;
      cursor: pointer;
    }

    .task-item:hover { background: #f3f1ec; }
    .task-item.done { opacity: 0.45; }

    .task-check {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      border: 1.5px solid #d4c4b0;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s, border-color 0.2s;
    }

    .task-item.done .task-check { background: #c4a882; border-color: #c4a882; }
    .task-check .check-icon { display: none; }
    .task-item.done .task-check .check-icon { display: block; }

    .task-text { font-size: 14px; color: #2a2a2a; }
    .task-item.done .task-text { text-decoration: line-through; color: #aaa; }

    .task-tag {
      margin-left: auto;
      font-size: 11px;
      padding: 3px 10px;
      border-radius: 20px;
      background: rgba(196,168,130,0.12);
      color: #8a6d4a;
      letter-spacing: 0.04em;
      white-space: nowrap;
    }

    .add-task {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 14px;
      padding: 10px 14px;
      border-radius: 12px;
      border: 0.5px dashed #d4c4b0;
      color: #bbb;
      font-size: 13px;
      cursor: pointer;
      transition: border-color 0.2s, color 0.2s;
      font-family: 'DM Sans', sans-serif;
      background: none;
      width: 100%;
      text-align: left;
    }

    .add-task:hover { border-color: #c4a882; color: #8a6d4a; }

    .features {
      padding: 5rem 3rem;
      max-width: 900px;
      margin: 0 auto;
    }

    .section-label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.12em;
      color: #c4a882;
      margin-bottom: 1rem;
    }

    .section-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(32px, 4vw, 48px);
      font-weight: 300;
      color: #1a1a1a;
      line-height: 1.2;
      margin-bottom: 3.5rem;
    }

    .feature-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
    }

    @media (max-width: 700px) {
      .feature-grid { grid-template-columns: 1fr; }
      nav { padding: 1rem 1.5rem; }
      .nav-links { gap: 1rem; }
      .features { padding: 3rem 1.5rem; }
      .preview-section { padding: 1rem 1.5rem 3rem; }
    }

    .feature-card {
      background: #fff;
      border-radius: 16px;
      padding: 1.75rem;
      border: 0.5px solid rgba(0,0,0,0.06);
      transition: transform 0.2s;
    }

    .feature-card:hover { transform: translateY(-3px); }

    .feature-icon {
      width: 40px;
      height: 40px;
      background: rgba(196,168,130,0.12);
      border-radius: 10px;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .feature-title { font-size: 15px; font-weight: 500; color: #1a1a1a; margin-bottom: 0.5rem; }
    .feature-desc { font-size: 13px; color: #777; line-height: 1.6; font-weight: 300; }

    .cta-section {
      margin: 2rem 3rem 5rem;
      background: #1a1a1a;
      border-radius: 24px;
      padding: 5rem 3rem;
      text-align: center;
      color: #f7f5f0;
      position: relative;
      overflow: hidden;
    }

    .cta-orb {
      position: absolute;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(196,168,130,0.12) 0%, transparent 70%);
      border-radius: 50%;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      pointer-events: none;
    }

    .cta-section h2 {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(32px, 5vw, 56px);
      font-weight: 300;
      margin-bottom: 1rem;
      position: relative;
    }

    .cta-section h2 em { color: #c4a882; font-style: italic; }
    .cta-section p { color: #888; font-size: 16px; margin-bottom: 2.5rem; font-weight: 300; position: relative; }

    .btn-light {
      background: #f7f5f0;
      color: #1a1a1a;
      padding: 14px 36px;
      border-radius: 40px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      border: none;
      font-family: 'DM Sans', sans-serif;
      transition: transform 0.2s, background 0.2s;
      position: relative;
    }

    .btn-light:hover { background: #fff; transform: translateY(-1px); }

    footer {
      text-align: center;
      padding: 2rem;
      font-size: 12px;
      color: #bbb;
      border-top: 0.5px solid rgba(0,0,0,0.06);
      letter-spacing: 0.04em;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(18px); }
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
    [data-theme="dark"] .hero h1 { color: #fff; }
    [data-theme="dark"] .hero h1 em { color: #3b9aed; }
    [data-theme="dark"] .hero-sub { color: #888; }
    [data-theme="dark"] .btn-primary { background: #3b9aed; }
    [data-theme="dark"] .btn-primary:hover { background: #2080e8; }
    [data-theme="dark"] .btn-ghost { color: #888; }
    [data-theme="dark"] .btn-ghost:hover { color: #fff; }
    [data-theme="dark"] .app-preview { background: #141820; border-color: #1e2430; box-shadow: 0 8px 60px rgba(0,0,0,0.4); }
    [data-theme="dark"] .app-header { border-bottom-color: #1e2430; }
    [data-theme="dark"] .app-title-sm { color: #fff; }
    [data-theme="dark"] .task-item { background: #0f1115; border-color: #1e2430; }
    [data-theme="dark"] .task-item:hover { background: #1a1e28; }
    [data-theme="dark"] .task-check { border-color: #333; }
    [data-theme="dark"] .task-item.done .task-check { background: #3b9aed; border-color: #3b9aed; }
    [data-theme="dark"] .task-text { color: #ddd; }
    [data-theme="dark"] .task-tag { background: rgba(59,154,237,0.15); color: #3b9aed; }
    [data-theme="dark"] .add-task { border-color: #333; }
    [data-theme="dark"] .add-task:hover { border-color: #3b9aed; color: #3b9aed; }
    [data-theme="dark"] .section-title { color: #fff; }
    [data-theme="dark"] .section-label { color: #3b9aed; }
    [data-theme="dark"] .feature-card { background: #141820; border-color: #1e2430; }
    [data-theme="dark"] .feature-title { color: #fff; }
    [data-theme="dark"] .feature-desc { color: #888; }
    [data-theme="dark"] .feature-icon { background: rgba(59,154,237,0.15); }
    [data-theme="dark"] .feature-icon svg circle, [data-theme="dark"] .feature-icon svg path { stroke: #3b9aed; fill: none; }
    [data-theme="dark"] .feature-icon svg circle[fill] { fill: #3b9aed; }
    [data-theme="dark"] .cta-section { background: #141820; }
    [data-theme="dark"] .cta-section h2 em { color: #3b9aed; }
    [data-theme="dark"] .btn-light { background: #3b9aed; color: #fff; }
    [data-theme="dark"] .btn-light:hover { background: #2080e8; }
    [data-theme="dark"] footer { border-top-color: #1e2430; }

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

    <!-- Logo left -->
    <a class="navbar-brand d-flex align-items-center p-0" href="/" style="gap: px;">
    <img src="{{ asset('assets/logo.png') }}" 
         alt="Lumido" 
         style="height: 45px; width: auto; display: block;"
         onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">

    <span style="font-family: 'DM Sans', sans-serif; font-weight: 700; font-size: 28px; color: #1a1a1a; letter-spacing: -0.8px; line-height: 1;">
        Lumido
    </span>
</a>

    <!-- Mobile toggler -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Links right -->
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

<section class="hero">
  <div class="hero-bg-orb"></div>
  <div class="hero-badge">
    <svg width="6" height="6" viewBox="0 0 6 6"><circle cx="3" cy="3" r="3" fill="#c4a882"/></svg>
    Calm productivity, redefined
  </div>
  <h1>Do less,<br>accomplish <em>more</em></h1>
  <p class="hero-sub">A mindful task manager that brings clarity to your day — one gentle step at a time.</p>
  <div class="hero-actions">
    <button class="btn-primary" onclick="window.location.href='/sign'">Start for free</button>
    <button class="btn-ghost" onclick="document.querySelector('.features').scrollIntoView({behavior:'smooth'})">
      See how it works
      <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 7h8M8 4l3 3-3 3" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
  </div>
</section>

<section class="preview-section">
  <div class="app-preview">
    <div class="app-header">
      <span class="app-date">Friday, April 3</span>
      <span class="app-title-sm">My day</span>
    </div>
    <div class="task-list">
      <div class="task-item done" onclick="toggle(this)">
        <div class="task-check"><svg class="check-icon" width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l3 3 5-6" stroke="#f7f5f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
        <span class="task-text">Morning meditation</span>
        <span class="task-tag">wellness</span>
      </div>
      <div class="task-item" onclick="toggle(this)">
        <div class="task-check"><svg class="check-icon" width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l3 3 5-6" stroke="#f7f5f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
        <span class="task-text">Review project proposal</span>
        <span class="task-tag">work</span>
      </div>
      <div class="task-item" onclick="toggle(this)">
        <div class="task-check"><svg class="check-icon" width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l3 3 5-6" stroke="#f7f5f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
        <span class="task-text">Walk in the park</span>
        <span class="task-tag">self</span>
      </div>
      <div class="task-item done" onclick="toggle(this)">
        <div class="task-check"><svg class="check-icon" width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l3 3 5-6" stroke="#f7f5f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
        <span class="task-text">Read for 20 minutes</span>
        <span class="task-tag">growth</span>
      </div>
      <div class="task-item" onclick="toggle(this)">
        <div class="task-check"><svg class="check-icon" width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l3 3 5-6" stroke="#f7f5f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
        <span class="task-text">Prepare for tomorrow</span>
        <span class="task-tag">planning</span>
      </div>
    </div>
    <button class="add-task">
      <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 2v10M2 7h10" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
      Add a new intention...
    </button>
  </div>
</section>

<section class="features">
  <p class="section-label">Why Lumido</p>
  <h2 class="section-title">Built for a quieter mind</h2>
  <div class="feature-grid">
    <div class="feature-card">
      <div class="feature-icon">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
          <circle cx="10" cy="10" r="7" stroke="#c4a882" stroke-width="1.2"/>
          <circle cx="10" cy="10" r="3" fill="#c4a882" opacity="0.4"/>
          <circle cx="10" cy="10" r="1.2" fill="#c4a882"/>
        </svg>
      </div>
      <p class="feature-title">Focus mode</p>
      <p class="feature-desc">See only what matters right now. No clutter, no overwhelm — just your next step.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
          <path d="M4 10h12M4 6h8M4 14h6" stroke="#c4a882" stroke-width="1.2" stroke-linecap="round"/>
        </svg>
      </div>
      <p class="feature-title">Gentle reminders</p>
      <p class="feature-desc">Soft nudges that encourage, not pressure. Stay on track without the anxiety.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
          <path d="M10 3C6.13 3 3 6.13 3 10s3.13 7 7 7 7-3.13 7-7-3.13-7-7-7z" stroke="#c4a882" stroke-width="1.2"/>
          <path d="M10 7v3l2 2" stroke="#c4a882" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <p class="feature-title">Daily rhythm</p>
      <p class="feature-desc">Build routines that flow naturally. Morning intentions, evening reflections.</p>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="cta-orb"></div>
  <h2>Ready to find your <em>light?</em></h2>
  <p>Join thousands finding calm in their daily flow.</p>
  <button class="btn-light" onclick="window.location.href='/sign'">Start free — no card needed</button>
</section>

<footer>
  © 2026 Lumido · Made with intention
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggle(el) {
    el.classList.toggle('done');
  }
</script>
</body>
</html>