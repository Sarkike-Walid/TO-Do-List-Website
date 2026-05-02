<!-- Top bar -->
<div class="dash-topbar">
  <a href="/home" class="topbar-logo">
    <img src="{{ asset('assets/logo.png') }}" alt="Lumido" style="height:28px;width:auto;" onerror="this.style.display='none'">
    <span>Lumido</span>
  </a>

  <!-- Search -->
  <div class="topbar-search" @click.away="searchOpen = false">
    <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
    <input type="text" placeholder="Search tasks, lists, tags…" x-model="searchQuery" @input="searchOpen = searchQuery.length > 0" @focus="searchOpen = searchQuery.length > 0"/>
    <div class="search-results" x-show="searchOpen && searchQuery.length > 0" x-transition>
      <template x-if="searchResults().length === 0">
        <div class="search-empty">No results for "<span x-text="searchQuery"></span>"</div>
      </template>
      <template x-for="r in searchResults()" :key="r.id">
        <div class="search-result-item" @mousedown.prevent="openSearchResult(r)">
          <div>
            <div class="result-title" x-text="r.title"></div>
            <div class="result-meta" x-text="r.meta"></div>
          </div>
          <span class="search-result-badge" x-text="r.type"></span>
        </div>
      </template>
    </div>
  </div>

  <!-- Dark mode toggle -->
  <button class="topbar-icon dark-toggle" @click="toggleDarkMode()" :title="darkMode ? 'Switch to light mode' : 'Switch to dark mode'">
    <svg x-show="!darkMode" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
    <svg x-show="darkMode" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
  </button>

  <!-- Bell / Notifications -->
  <div class="notif-wrapper" @click.away="notifOpen = false">
    <button class="topbar-icon notif-btn" @click="notifOpen = !notifOpen" title="Notifications">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
      <span class="notif-dot" x-show="notifItems().length > 0" x-text="notifItems().length > 9 ? '9+' : notifItems().length"></span>
    </button>

    <!-- Notification Dropdown -->
    <div class="notif-panel" x-show="notifOpen" x-transition @click.stop>
      <div class="notif-panel-header">
        <h4>Notifications</h4>
        <button class="notif-clear-all" @click="markAllNotifRead()" x-show="notifItems().length > 0">Mark all read</button>
      </div>

      <template x-if="notifItems().length === 0">
        <div class="notif-empty">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.3"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
          <p>You're all caught up!</p>
        </div>
      </template>

      <div class="notif-list">
        <template x-for="item in notifItems()" :key="item.id">
          <div class="notif-item" :class="'notif-type-' + item.type" @click="openNotif(item)">
            <div class="notif-item-icon">
              <template x-if="item.type === 'reminder'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              </template>
              <template x-if="item.type === 'overdue'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              </template>
              <template x-if="item.type === 'due-today'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
              </template>
            </div>
            <div class="notif-item-body">
              <div class="notif-item-title" x-text="item.title"></div>
              <div class="notif-item-meta" x-text="item.meta"></div>
              <div class="notif-item-actions">
                <button class="notif-action-btn primary" @click.stop="completeFromNotif(item)">Done</button>
                <button class="notif-action-btn" @click.stop="openNotif(item)">View</button>
              </div>
            </div>
            <button class="notif-dismiss" @click.stop="dismissNotif(item.id)" title="Dismiss">✕</button>
          </div>
        </template>
      </div>
    </div>
  </div>
</div>
