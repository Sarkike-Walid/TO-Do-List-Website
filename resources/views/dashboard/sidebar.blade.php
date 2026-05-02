<!-- SIDEBAR -->
<aside class="dash-sidebar">
  <div class="sidebar-profile" @click="openSettings()" style="cursor: pointer;" title="Settings">
    <template x-if="userAvatar">
      <img :src="userAvatar" alt="Avatar" style="width:32px;height:32px;border-radius:50%;object-fit:cover;"/>
    </template>
    <template x-if="!userAvatar">
      <div class="sidebar-avatar" x-text="userName.charAt(0).toUpperCase()"></div>
    </template>
    <div class="sidebar-user-info">
      <h4 x-text="userName"></h4>
      <span style="font-size:11px;color:#999;font-weight:500;">Settings</span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <button class="nav-item" :class="{ active: currentTab === 'my-day' }" @click="currentTab = 'my-day'">
      <span class="nav-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg></span>
      My day
      <span class="nav-badge" x-show="taskCount() > 0" x-text="taskCount()"></span>
    </button>
    <button class="nav-item" :class="{ active: currentTab === 'next-7-days' }" @click="currentTab = 'next-7-days'">
      <span class="nav-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></span>
      Next 7 days
      <span class="nav-badge" x-show="next7Count() > 0" x-text="next7Count()"></span>
    </button>
    <button class="nav-item" :class="{ active: currentTab === 'all-tasks' }" @click="currentTab = 'all-tasks'; selectedTask = null">
      <span class="nav-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg></span>
      All my tasks
      <span class="nav-badge" x-show="allTaskCount() > 0" x-text="allTaskCount()"></span>
    </button>
  </nav>

  <div class="sidebar-section">
    <h5>My lists</h5>
    <button class="add-btn" @click="showListModal = true">+</button>
  </div>
  <template x-for="list in lists" :key="list.id">
    <div class="list-item" @click="currentTab = 'my-lists'">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
      <span x-text="list.name"></span>
      <span class="nav-badge" style="background:rgba(196,168,130,0.2);color:#8a6d4a;" x-show="listTaskCount(list.id)>0" x-text="listTaskCount(list.id)"></span>
    </div>
  </template>

  <div class="sidebar-section">
    <h5>Tags</h5>
    <button class="add-btn" @click="showTagModal = true">+</button>
  </div>
  <template x-for="tag in tags" :key="tag.id">
    <div class="tag-item" @click="currentTab = 'tags'" x-text="tag.name"></div>
  </template>

  <div class="sidebar-bottom">
    @if(auth()->check() && auth()->user()->isAdmin())
    <a href="{{ route('admin.dashboard') }}" class="nav-item" style="width:100%;color:#3b82f6;text-decoration:none;margin-bottom:5px;">
      <span class="nav-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg></span>
      Admin Panel
    </a>
    @endif
    <form method="POST" action="{{ route('logout') }}" style="margin:0">
      @csrf
      <button type="submit" class="nav-item" style="width:100%;color:#e74c3c;">
        <span class="nav-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></span>
        Log out
      </button>
    </form>
  </div>
</aside>
