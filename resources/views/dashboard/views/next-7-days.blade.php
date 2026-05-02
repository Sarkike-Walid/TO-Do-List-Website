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
