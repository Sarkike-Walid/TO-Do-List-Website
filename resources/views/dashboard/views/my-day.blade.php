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
    <div class="task-row" :class="{ completed: task.completed, pinned: task.pinned }"
         :style="task.color ? 'border-left: 3px solid ' + task.color : ''"
         @click="selectTask(task); currentTab='all-tasks'">
      <!-- Color dot -->
      <div x-show="task.color" class="task-color-dot" :style="'background:' + task.color"></div>
      <div class="task-check-circle" @click.stop="toggleTask(task)">
        <svg x-show="task.completed" width="10" height="8" viewBox="0 0 12 10" fill="none"><path d="M1 5l3.5 3.5L11 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </div>
      <div style="flex:1">
        <div class="task-meta">
          <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
          <span x-text="task.list"></span>
          <span x-show="task.pinned" style="color:#c4a882;margin-left:4px;" title="Pinned">📌</span>
        </div>
        <div class="task-title">
          <span x-show="task.emoji" x-text="task.emoji" style="margin-right:4px;"></span>
          <span x-text="task.title"></span>
        </div>
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
