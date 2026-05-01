<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Features — Lumido</title>
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

    .features-hero {
      padding: 6rem 2rem 4rem;
      text-align: center;
      position: relative;
    }

    .features-hero-orb {
      position: absolute;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(196,168,130,0.15) 0%, transparent 70%);
      border-radius: 50%;
      top: 0%; left: 50%;
      transform: translate(-50%, -20%);
      pointer-events: none;
      z-index: -1;
    }

    .hero-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(40px, 6vw, 72px);
      font-weight: 300;
      color: #1a1a1a;
      line-height: 1.1;
      margin-bottom: 1.5rem;
      animation: fadeUp 0.6s ease both;
    }

    .hero-title em { font-style: italic; color: #c4a882; }

    .hero-subtitle {
      font-size: 18px;
      color: #666;
      font-weight: 300;
      max-width: 600px;
      margin: 0 auto 4rem;
      line-height: 1.6;
      animation: fadeUp 0.6s ease 0.1s both;
    }

    .feature-section {
      padding: 5rem 2rem;
      max-width: 1100px;
      margin: 0 auto;
    }

    .feature-row {
      display: flex;
      align-items: center;
      gap: 5rem;
      margin-bottom: 8rem;
    }

    .feature-row.reverse {
      flex-direction: row-reverse;
    }

    .feature-content {
      flex: 1;
    }

    .feature-visual {
      flex: 1;
      position: relative;
    }

    .visual-box {
      background: #fff;
      border-radius: 24px;
      padding: 2.5rem;
      box-shadow: 0 12px 50px rgba(0,0,0,0.05);
      border: 0.5px solid rgba(0,0,0,0.08);
      position: relative;
    }

    .visual-box::before {
      content: '';
      position: absolute;
      top: -15px; left: -15px;
      width: 100%; height: 100%;
      border-radius: 24px;
      border: 1px solid rgba(196,168,130,0.3);
      z-index: -1;
    }
    
    .feature-row.reverse .visual-box::before {
      left: auto; right: -15px;
    }

    .f-icon {
      width: 48px;
      height: 48px;
      background: rgba(196,168,130,0.12);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.5rem;
      color: #c4a882;
    }

    .f-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 36px;
      font-weight: 400;
      color: #1a1a1a;
      margin-bottom: 1rem;
    }

    .f-desc {
      font-size: 16px;
      color: #666;
      line-height: 1.7;
      font-weight: 300;
    }

    .f-list {
      margin-top: 1.5rem;
      list-style: none;
    }

    .f-list li {
      margin-bottom: 0.75rem;
      font-size: 14px;
      color: #444;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .f-list li svg {
      color: #c4a882;
      flex-shrink: 0;
    }

    /* Mock visuals */
    .mock-task {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px;
      border-radius: 12px;
      background: #faf9f7;
      border: 0.5px solid rgba(0,0,0,0.05);
      margin-bottom: 10px;
    }
    .mock-task-check {
      width: 20px; height: 20px; border-radius: 50%;
      border: 1.5px solid #d4c4b0;
    }
    .mock-task-check.checked { background: #c4a882; border-color: #c4a882; }
    .mock-task-text { font-size: 14px; color: #2a2a2a; flex: 1;}
    .mock-task.checked .mock-task-text { text-decoration: line-through; color: #aaa; }
    .mock-tag { font-size: 11px; padding: 3px 10px; border-radius: 20px; background: rgba(196,168,130,0.12); color: #8a6d4a; }

    .mock-notif {
      background: #1a1a1a;
      color: #f7f5f0;
      border-radius: 16px;
      padding: 16px 20px;
      display: flex;
      align-items: flex-start;
      gap: 12px;
      margin-bottom: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .mock-notif-icon { color: #c4a882; }
    .mock-notif-title { font-size: 14px; font-weight: 500; margin-bottom: 4px; }
    .mock-notif-desc { font-size: 12px; color: #aaa; }

    .mock-folder {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px;
      border-radius: 8px;
      border-bottom: 1px solid rgba(0,0,0,0.04);
      font-size: 14px;
      color: #333;
    }
    .mock-folder:last-child { border-bottom: none; }

    @media (max-width: 900px) {
      .feature-row, .feature-row.reverse {
        flex-direction: column;
        gap: 3rem;
      }
    }

    footer {
      text-align: center;
      padding: 2rem;
      font-size: 12px;
      color: #bbb;
      border-top: 0.5px solid rgba(0,0,0,0.06);
      letter-spacing: 0.04em;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
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

    [data-theme="dark"] .hero-title { color: #fff; }
    [data-theme="dark"] .hero-title em { color: #3b9aed; }
    [data-theme="dark"] .hero-subtitle { color: #888; }
    [data-theme="dark"] .f-title { color: #fff; }
    [data-theme="dark"] .f-desc { color: #888; }
    [data-theme="dark"] .f-list li { color: #ddd; }
    [data-theme="dark"] .f-list li svg { color: #3b9aed; }
    [data-theme="dark"] .f-icon { background: rgba(59,154,237,0.15); color: #3b9aed; }

    [data-theme="dark"] .visual-box { background: #141820; border-color: #1e2430; box-shadow: 0 12px 50px rgba(0,0,0,0.4); }
    [data-theme="dark"] .visual-box::before { border-color: rgba(59,154,237,0.3); }

    [data-theme="dark"] .mock-task { background: #0f1115; border-color: #1e2430; }
    [data-theme="dark"] .mock-task-text { color: #ddd; }
    [data-theme="dark"] .mock-task-check { border-color: #333; }
    [data-theme="dark"] .mock-task-check.checked { background: #3b9aed; border-color: #3b9aed; }
    [data-theme="dark"] .mock-tag { background: rgba(59,154,237,0.15); color: #3b9aed; }

    [data-theme="dark"] .mock-folder { color: #ddd; border-bottom-color: #1e2430; }
    [data-theme="dark"] .mock-folder svg { stroke: #3b9aed; }
    
    [data-theme="dark"] .mock-notif { background: #0f1115; border: 1px solid #1e2430; color: #ddd; box-shadow: 0 10px 30px rgba(0,0,0,0.4); }
    [data-theme="dark"] .mock-notif-icon { color: #3b9aed; }
    [data-theme="dark"] .mock-notif-title { color: #fff; }
    
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

<section class="features-hero">
  <div class="features-hero-orb"></div>
  <h1 class="hero-title">Features designed for <em>clarity</em></h1>
  <p class="hero-subtitle">Every tool in Lumido is purposefully crafted to reduce noise and help you focus on what truly matters today.</p>
</section>

<section class="feature-section">

  <!-- Task Management -->
  <div class="feature-row">
    <div class="feature-content">
      <div class="f-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 11 12 14 22 4"></polyline>
          <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
        </svg>
      </div>
      <h2 class="f-title">Mindful Task Management</h2>
      <p class="f-desc">Capture intentions as they arise. Lumido's task management is fluid and uncluttered, allowing you to prioritize with simple drag-and-drop actions.</p>
      <ul class="f-list">
        <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Quick-add tasks with natural language</li>
        <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Break large goals into gentle sub-steps</li>
        <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Focus mode hides everything but the current task</li>
      </ul>
    </div>
    <div class="feature-visual">
      <div class="visual-box">
        <div class="mock-task checked">
          <div class="mock-task-check checked"></div>
          <span class="mock-task-text">Morning journaling</span>
          <span class="mock-tag">habit</span>
        </div>
        <div class="mock-task">
          <div class="mock-task-check"></div>
          <span class="mock-task-text">Draft Q3 strategy document</span>
          <span class="mock-tag">work</span>
        </div>
        <div class="mock-task">
          <div class="mock-task-check"></div>
          <span class="mock-task-text">Call the dentist</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Organization -->
  <div class="feature-row reverse">
    <div class="feature-content">
      <div class="f-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
        </svg>
      </div>
      <h2 class="f-title">Effortless Organization</h2>
      <p class="f-desc">Group your tasks by projects, areas of life, or energy levels. Keep work separate from personal life, or view everything holistically.</p>
      <ul class="f-list">
        <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Custom tags and color coding</li>
        <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Infinite nesting for complex projects</li>
        <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Archiving to keep your active workspace clean</li>
      </ul>
    </div>
    <div class="feature-visual">
      <div class="visual-box">
        <div class="mock-folder">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#c4a882" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          Daily Routines
        </div>
        <div class="mock-folder">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8a6d4a" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
          Client Projects
        </div>
        <div class="mock-folder">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8a6d4a" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
          Home Renovation
        </div>
      </div>
    </div>
  </div>

  <!-- Reminders & Notifications -->
  <div class="feature-row">
    <div class="feature-content">
      <div class="f-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
      </div>
      <h2 class="f-title">Gentle Reminders</h2>
      <p class="f-desc">Never miss a beat, but never feel overwhelmed. Lumido's notifications are designed to be helpful nudges rather than demanding alarms.</p>
      <ul class="f-list">
        <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Time and location-based alerts</li>
        <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Quiet hours to ensure peace of mind</li>
        <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Daily digests instead of constant pings</li>
      </ul>
    </div>
    <div class="feature-visual">
      <div class="visual-box" style="background: transparent; border: none; box-shadow: none; padding: 0;">
        <div class="visual-box::before" style="display:none;"></div>
        <div class="mock-notif">
          <div class="mock-notif-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
          </div>
          <div>
            <div class="mock-notif-title">Upcoming Focus Block</div>
            <div class="mock-notif-desc">In 15 minutes, you planned to write. Time to wrap up and grab some tea.</div>
          </div>
        </div>
        <div class="mock-notif" style="opacity: 0.7; transform: scale(0.95); transform-origin: top;">
          <div class="mock-notif-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          </div>
          <div>
            <div class="mock-notif-title">Evening Review</div>
            <div class="mock-notif-desc">You completed 5 tasks today. Great work!</div>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>

<footer>
  © 2026 Lumido · Made with intention
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>