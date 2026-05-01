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
</head>
<body class="dashboard-body" x-data="dashboard()">

<!-- SIDEBAR -->
<aside class="dash-sidebar">
  <div class="sidebar-profile">
    <div class="sidebar-avatar" x-text="userName.charAt(0).toUpperCase()"></div>
    <div class="sidebar-user-info">
      <h4 x-text="userName"></h4>
      <span>Free Plan</span>
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
    <form method="POST" action="{{ route('logout') }}" style="margin:0">
      @csrf
      <button type="submit" class="nav-item" style="width:100%;color:#e74c3c;">
        <span class="nav-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></span>
        Log out
      </button>
    </form>
  </div>
</aside>

<!-- MAIN -->
<div class="dash-main">

  <!-- Background orb -->
  <div class="dash-bg">
    <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
      <defs><radialGradient id="og" cx="50%" cy="50%"><stop offset="0%" stop-color="#c4a882"/><stop offset="100%" stop-color="#f7f5f0" stop-opacity="0"/></radialGradient></defs>
      <circle cx="200" cy="200" r="200" fill="url(#og)"/>
    </svg>
  </div>

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

    <!-- Bell -->
    <div class="topbar-icon" title="Notifications">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
    </div>
  </div>

  <!-- Content -->
  <div class="dash-content">

    @if (session('success'))
      <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="toast-success">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <span>{{ session('success') }}</span>
        <button @click="show = false" style="background:none;border:none;color:currentColor;cursor:pointer;margin-left:auto;">✕</button>
      </div>
    @endif

    <!-- MY DAY -->
    <div x-show="currentTab === 'my-day'" x-transition>
      <div class="greeting">
        <h1 x-text="getGreeting() + ', ' + userName + '.'"></h1>
        <p>What will you accomplish today?</p>
      </div>

      <div class="day-card">
        <div class="day-date">
          <div class="dow" x-text="getToday().toLocaleDateString('en',{weekday:'short'}).toUpperCase()"></div>
          <div class="num" x-text="getToday().getDate()"></div>
          <div class="mon" x-text="getToday().toLocaleDateString('en',{month:'long'})"></div>
        </div>
        <div style="flex:1">
          <div style="font-size:14px;color:#2a2a2a;margin-bottom:4px;">Your tasks for today</div>
          <div style="font-size:12px;color:#bbb;" x-text="taskCount() + ' remaining'"></div>
        </div>
      </div>

      <template x-for="task in todayTasks()" :key="task.id">
        <div class="task-row" :class="{ completed: task.completed }" @click="selectTask(task); currentTab='all-tasks'">
          <div class="task-check-circle" @click.stop="toggleTask(task)">
            <svg x-show="task.completed" width="10" height="8" viewBox="0 0 12 10" fill="none"><path d="M1 5l3.5 3.5L11 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div style="flex:1">
            <div class="task-meta">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
              <span x-text="task.list"></span>
            </div>
            <div class="task-title" x-text="task.title"></div>
          </div>
          <span x-show="task.tag" style="font-size:11px;color:#8a6d4a;background:rgba(196,168,130,0.1);padding:2px 10px;border-radius:10px;" x-text="task.tag"></span>
          <button @click.stop="deleteTask(task)" style="background:none;border:none;color:#ccc;cursor:pointer;font-size:14px;transition:color .15s;" onmouseover="this.style.color='#e74c3c'" onmouseout="this.style.color='#ccc'">✕</button>
        </div>
      </template>

      <div class="add-task-bar">
        <span class="plus">+</span>
        <input type="text" placeholder="Add a task for today…" x-model="newTaskText" @keydown.enter="addQuickTask()"/>
        <select x-model="newTaskList" class="task-list-select" style="width:auto;margin:0;padding:4px 8px;">
          <template x-for="l in lists" :key="l.id"><option :value="l.id" x-text="l.name"></option></template>
        </select>
        <button class="add-btn-primary" @click="addQuickTask()">Add</button>
      </div>
    </div>

    <!-- NEXT 7 DAYS -->
    <div x-show="currentTab === 'next-7-days'" x-transition>
      <div class="view-header">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#c4a882" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        <h2>Next 7 days</h2>
        <span class="pill">≡ Filter</span>
        <button class="clear-btn" @click="clearCompleted()">🗑 Clear completed</button>
        <div class="scroll-arrows">
          <button class="scroll-arrow" @click="scrollDays(-1)">←</button>
          <button class="scroll-arrow" @click="scrollDays(1)">→</button>
        </div>
      </div>
      <div class="days-scroll" id="days-scroll">
        <template x-for="i in 7" :key="i">
          <div class="day-column">
            <div class="day-col-header" x-html="getDayLabel(i-1)"></div>
            <template x-for="task in dayTasks(i-1)" :key="task.id">
              <div class="day-col-task" :style="task.completed ? 'opacity:.45' : ''">
                <div class="task-check-circle" style="width:18px;height:18px;min-width:18px;" @click.stop="toggleTask(task)">
                  <svg x-show="task.completed" width="9" height="7" viewBox="0 0 12 10" fill="none"><path d="M1 5l3.5 3.5L11 1" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
                </div>
                <div style="flex:1">
                  <div class="task-meta">My lists › <span x-text="task.list"></span></div>
                  <div class="task-title" :style="task.completed ? 'text-decoration:line-through' : ''" x-text="task.title"></div>
                </div>
                <button @click.stop="deleteTask(task)" style="position:absolute;top:6px;right:8px;background:none;border:none;color:#ccc;cursor:pointer;font-size:13px;" onmouseover="this.style.color='#e74c3c'" onmouseout="this.style.color='#ccc'">✕</button>
              </div>
            </template>
            <button class="day-col-add" @click="openDayTaskModal(i-1)">+ Add Task</button>
          </div>
        </template>
      </div>
    </div>

    <!-- ALL TASKS -->
    <div x-show="currentTab === 'all-tasks'" x-transition>
      <div class="view-header">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#c4a882" stroke-width="1.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
        <h2>All my tasks</h2>
        <button class="clear-btn" @click="clearCompleted()">🗑 Clear completed</button>
      </div>
      <div class="all-tasks-layout">
        <div class="tasks-list-pane">
          <div class="task-group-label">Priority</div>
          <template x-for="task in tasks.filter(t => t.tag)" :key="task.id">
            <div class="task-list-item" :id="'task-item-' + task.id" :class="{ selected: selectedTask && selectedTask.id === task.id }" @click="selectTask(task)">
              <div class="task-check-circle" style="width:18px;height:18px;min-width:18px;" @click.stop="toggleTask(task)">
                <svg x-show="task.completed" width="9" height="7" viewBox="0 0 12 10" fill="none"><path d="M1 5l3.5 3.5L11 1" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
              </div>
              <div>
                <div class="t-name" :style="task.completed ? 'text-decoration:line-through;opacity:.45' : ''" x-text="task.title"></div>
                <div class="t-cat" x-text="task.list"></div>
              </div>
              <span x-show="task.tag" style="margin-left:auto;font-size:11px;color:#8a6d4a;background:rgba(196,168,130,0.1);padding:2px 10px;border-radius:10px;" x-text="task.tag"></span>
            </div>
          </template>

          <div class="task-group-label" style="margin-top:12px;">Other tasks</div>
          <template x-for="task in tasks.filter(t => !t.tag)" :key="task.id">
            <div class="task-list-item" :id="'task-item-' + task.id" :class="{ selected: selectedTask && selectedTask.id === task.id }" @click="selectTask(task)">
              <div class="task-check-circle" style="width:18px;height:18px;min-width:18px;" @click.stop="toggleTask(task)">
                <svg x-show="task.completed" width="9" height="7" viewBox="0 0 12 10" fill="none"><path d="M1 5l3.5 3.5L11 1" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
              </div>
              <div>
                <div class="t-name" :style="task.completed ? 'text-decoration:line-through;opacity:.45' : ''" x-text="task.title"></div>
                <div class="t-cat" x-text="task.list"></div>
              </div>
            </div>
          </template>

          <div class="add-task-bar" style="margin-top:14px;">
            <span class="plus">+</span>
            <input type="text" placeholder="Add a new task…" x-model="newTaskText" @keydown.enter="addQuickTask()"/>
            <button class="add-btn-primary" @click="addQuickTask()">Add</button>
          </div>
        </div>

        <!-- Task detail panel -->
        <div class="task-detail-pane" x-show="selectedTask" x-transition>
          <template x-if="selectedTask">
            <div>
              <div class="detail-top-actions">
                <button class="detail-btn" @click="toggleTask(selectedTask)">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/></svg>
                  <span x-text="selectedTask.completed ? 'Mark incomplete' : 'Mark complete'"></span>
                </button>
                <button class="detail-btn danger" @click="deleteTask(selectedTask)">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                </button>
              </div>

              <div class="detail-breadcrumb">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                My lists › <span x-text="selectedTask.list"></span>
              </div>

              <div class="detail-title" x-text="selectedTask.title"></div>

              <!-- Task info card -->
              <div class="task-info-card">
                <div class="task-info-item">
                  <span class="task-info-label">Status</span>
                  <span class="task-info-value" x-text="selectedTask.completed ? '✓ Done' : '○ Todo'"></span>
                </div>
                <div class="task-info-item">
                  <span class="task-info-label">Due date</span>
                  <input type="date" x-model="selectedTask.due_date" @change="updateDueDate(selectedTask)" style="background:transparent;border:none;color:inherit;font-family:inherit;font-size:inherit;outline:none;cursor:pointer;">
                </div>
                <div class="task-info-item">
                  <span class="task-info-label">Priority</span>
                  <span class="priority-badge" :class="'priority-' + (selectedTask.priority || 'none')" x-text="selectedTask.priority || 'none'"></span>
                </div>
                <div class="task-info-item">
                  <span class="task-info-label">Subtasks</span>
                  <span class="task-info-value" x-text="subtaskProgress(selectedTask)"></span>
                </div>
              </div>

              <div class="detail-actions">
                <template x-for="tag in tags" :key="tag">
                  <span class="detail-pill" :style="selectedTask.tag === tag ? 'background:rgba(196,168,130,0.2);border-color:#c4a882' : ''" @click="assignTag(selectedTask, tag)" x-text="tag"></span>
                </template>
                <select class="task-list-select" style="width:auto;margin:0;padding:4px 10px;" x-model="selectedTask.list_id" @change="updateTaskList(selectedTask)">
                  <template x-for="l in lists" :key="l.id"><option :value="l.id" x-text="l.name"></option></template>
                </select>
              </div>

              <div class="detail-section">
                <h5>Notes</h5>
                <textarea class="detail-notes" placeholder="Add notes…" x-model="selectedTask.note" @change="updateNote(selectedTask)"></textarea>
              </div>

              <div class="detail-section">
                <h5>Subtasks <span x-text="subtaskProgress(selectedTask)"></span></h5>
                <template x-for="(sub, idx) in selectedTask.subtasks" :key="idx">
                  <div class="subtask-row">
                    <div class="task-check-circle" style="width:16px;height:16px;min-width:16px;" @click="toggleSubtask(selectedTask, idx)" :style="sub.done ? 'background:#c4a882;border-color:#c4a882' : 'border-color:#d4c4b0'">
                      <svg x-show="sub.done" width="8" height="6" viewBox="0 0 12 10" fill="none"><path d="M1 5l3.5 3.5L11 1" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                    <span :style="sub.done ? 'text-decoration:line-through;color:#bbb' : ''" x-text="sub.text"></span>
                  </div>
                </template>
                <div x-data="{ newSubtask: '' }" style="display:flex;gap:8px;margin-top:8px;">
                  <input type="text" placeholder="Add a subtask..." x-model="newSubtask" @keydown.enter="addSubtask(selectedTask, newSubtask); newSubtask=''" style="flex:1;background:#faf9f7;border:0.5px solid rgba(0,0,0,0.1);border-radius:8px;padding:6px 12px;font-size:13px;outline:none;" class="subtask-input">
                  <button class="add-btn-primary" @click="addSubtask(selectedTask, newSubtask); newSubtask=''">Add</button>
                </div>
              </div>

              <div class="detail-section">
                <h5>Attachments</h5>
                <div class="attachments-list" style="margin-bottom:10px;">
                  <template x-for="att in selectedTask.attachments" :key="att.id">
                    <div style="display:flex;align-items:center;gap:10px;padding:6px 10px;background:#faf9f7;border:0.5px solid rgba(0,0,0,0.08);border-radius:8px;margin-bottom:4px;" class="att-item">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
                      <a :href="att.url" target="_blank" x-text="att.filename" style="font-size:13px;color:#2a2a2a;text-decoration:none;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" class="att-link"></a>
                      <button @click="deleteAttachment(selectedTask, att.id)" style="background:none;border:none;color:#ccc;cursor:pointer;" onmouseover="this.style.color='#e74c3c'" onmouseout="this.style.color='#ccc'">✕</button>
                    </div>
                  </template>
                </div>
                <div style="position:relative;overflow:hidden;display:inline-block;">
                  <button class="add-btn-primary" style="pointer-events:none;">Upload File</button>
                  <input type="file" @change="uploadAttachment(selectedTask, $event)" style="position:absolute;top:0;left:0;opacity:0;width:100%;height:100%;cursor:pointer;">
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>

    <!-- MY LISTS -->
    <div x-show="currentTab === 'my-lists'" x-transition>
      <div class="view-header">
        <h2>My Lists</h2>
        <button class="clear-btn" @click="showListModal = true">+ Add list</button>
      </div>
      <div class="lists-grid">
        <template x-for="list in lists" :key="list.id">
          <div class="list-card">
            <div class="list-count" x-text="listTaskCount(list.id)"></div>
            <h4 x-text="list.name"></h4>
            <p x-text="listTaskCount(list.id) + ' tasks'"></p>
            <button @click.stop="deleteList(list.id)" style="position:absolute;bottom:12px;right:12px;background:none;border:none;color:#ccc;cursor:pointer;font-size:12px;" onmouseover="this.style.color='#e74c3c'" onmouseout="this.style.color='#ccc'">Remove</button>
          </div>
        </template>
      </div>
    </div>

    <!-- TAGS -->
    <div x-show="currentTab === 'tags'" x-transition>
      <div class="view-header">
        <h2>Tags</h2>
        <button class="clear-btn" @click="showTagModal = true">+ Add tag</button>
      </div>
      <div class="tags-grid">
        <template x-for="tag in tags" :key="tag.id">
          <div class="tag-card">
            <span x-text="tag.name"></span>
            <button @click.stop="deleteTag(tag.id)" style="background:none;border:none;color:#ccc;cursor:pointer;margin-left:6px;" onmouseover="this.style.color='#e74c3c'" onmouseout="this.style.color='#ccc'">✕</button>
          </div>
        </template>
      </div>
    </div>

  </div><!-- end dash-content -->
</div><!-- end dash-main -->

<!-- Add List Modal -->
<div class="modal-overlay" x-show="showListModal" x-transition @click.self="showListModal = false" style="display:none;">
  <div class="modal-box">
    <button class="modal-close" @click="showListModal = false">✕</button>
    <h3>New list</h3>
    <input type="text" placeholder="e.g. Shopping, Fitness…" x-model="newListName" @keydown.enter="addList()"/>
    <button class="modal-submit" @click="addList()">Create list</button>
  </div>
</div>

<!-- Add Tag Modal -->
<div class="modal-overlay" x-show="showTagModal" x-transition @click.self="showTagModal = false" style="display:none;">
  <div class="modal-box">
    <button class="modal-close" @click="showTagModal = false">✕</button>
    <h3>New tag</h3>
    <input type="text" placeholder="e.g. Priority, Urgent…" x-model="newTagName" @keydown.enter="addTag()"/>
    <button class="modal-submit" @click="addTag()">Add tag</button>
  </div>
</div>

<!-- Add Task to Day Modal -->
<div class="modal-overlay" x-show="showTaskModal" x-transition @click.self="showTaskModal = false" style="display:none;">
  <div class="modal-box">
    <button class="modal-close" @click="showTaskModal = false">✕</button>
    <h3>Add a task</h3>
    <input type="text" placeholder="Task name…" x-model="modalTaskText" @keydown.enter="addDayTask()"/>
    <select x-model="modalTaskList" class="task-list-select">
      <template x-for="l in lists" :key="l.id"><option :value="l.id" x-text="l.name"></option></template>
    </select>
    <button class="modal-submit" @click="addDayTask()">Add task</button>
  </div>
</div>

</body>
</html>
