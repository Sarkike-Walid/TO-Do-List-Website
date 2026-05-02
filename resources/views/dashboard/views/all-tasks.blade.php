<!-- ALL TASKS -->
<div x-show="currentTab === 'all-tasks'" x-transition>
  <div class="view-header">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#c4a882" stroke-width="1.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
    <h2>All my tasks</h2>
    <button class="clear-btn" @click="clearCompleted()">🗑 Clear completed</button>
  </div>
  <!-- Filter & Sort bar -->
  <div class="filter-sort-bar">
    <span class="filter-sort-label">FILTER</span>
    <button class="pill" :class="filterStatus==='all'       ? 'pill-active' : ''" @click="filterStatus='all'">All</button>
    <button class="pill" :class="filterStatus==='active'    ? 'pill-active' : ''" @click="filterStatus='active'">Active</button>
    <button class="pill" :class="filterStatus==='completed' ? 'pill-active' : ''" @click="filterStatus='completed'">Completed</button>
    <span class="filter-sort-divider"></span>
    <span class="filter-sort-label">SORT</span>
    <button class="pill" :class="sortBy==='name'     ? 'pill-active' : ''" @click="sortBy='name'">Name</button>
    <button class="pill" :class="sortBy==='date'     ? 'pill-active' : ''" @click="sortBy='date'">Date</button>
    <button class="pill" :class="sortBy==='priority' ? 'pill-active' : ''" @click="sortBy='priority'">Priority</button>
    <span class="filter-sort-count" x-text="filteredSortedTasks().length + ' tasks'"></span>
  </div>
  <div class="all-tasks-layout">
    <div class="tasks-list-pane">
      <template x-for="task in filteredSortedTasks()" :key="task.id">
        <div class="task-list-item" :id="'task-item-' + task.id"
             :class="{ selected: selectedTask && selectedTask.id === task.id, pinned: task.pinned }"
             :style="task.color ? 'border-left:3px solid '+task.color : ''"
             @click="selectTask(task)">
          <!-- Color + check -->
          <div class="task-check-circle" style="width:18px;height:18px;min-width:18px;" @click.stop="toggleTask(task)"
               :style="task.color && task.completed ? 'background:'+task.color+';border-color:'+task.color : ''">
            <svg x-show="task.completed" width="9" height="7" viewBox="0 0 12 10" fill="none"><path d="M1 5l3.5 3.5L11 1" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
          </div>
          <div style="flex:1;min-width:0;">
            <div class="t-name" :style="task.completed ? 'text-decoration:line-through;opacity:.45' : ''">
              <span x-show="task.pinned" style="font-size:12px;margin-right:3px;" title="Pinned">📌</span>
              <span x-show="task.emoji" x-text="task.emoji" style="margin-right:3px;"></span>
              <span x-text="task.title"></span>
            </div>
            <div class="t-cat" x-text="task.list"></div>
          </div>
          <span x-show="task.priority && task.priority!=='none'" class="priority-badge" :class="'priority-'+(task.priority||'none')" x-text="task.priority"></span>
          <span x-show="task.tag" style="font-size:11px;color:#8a6d4a;background:rgba(196,168,130,0.1);padding:2px 10px;border-radius:10px;" x-text="task.tag"></span>
        </div>
      </template>

      <div class="add-task-bar" style="margin-top:14px;">
        <span class="plus">+</span>
        <input type="text" placeholder="Add a new task…" x-model="newTaskText" @keydown.enter="addQuickTask()"/>
        <button class="add-btn-primary" @click="addQuickTask()">Add</button>
      </div>
    </div>

    <!-- Task detail panel -->
    <div class="task-detail-pane" x-show="selectedTask"
         x-transition:enter="detail-enter"
         x-transition:enter-start="detail-enter-start"
         x-transition:enter-end="detail-enter-end"
         x-transition:leave="detail-leave"
         x-transition:leave-start="detail-leave-start"
         x-transition:leave-end="detail-leave-end">
      <template x-if="selectedTask">
        <div>
          <div class="detail-top-actions">
            <!-- Back / close button -->
            <button class="detail-back-btn" @click="selectedTask = null" title="Close">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
              Back
            </button>
            <div style="display:flex;align-items:center;gap:8px;margin-left:auto;">
              <button class="detail-btn" @click="toggleTask(selectedTask)">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/></svg>
                <span x-text="selectedTask.completed ? 'Mark incomplete' : 'Mark complete'"></span>
              </button>
              <button class="detail-btn danger" @click="deleteTask(selectedTask)">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
              </button>
            </div>
          </div>

          <div class="detail-breadcrumb">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
            My lists › <span x-text="selectedTask.list"></span>
          </div>

          <!-- Task title with emoji -->
          <div class="detail-title">
            <span x-show="selectedTask.emoji" x-text="selectedTask.emoji" style="margin-right:6px;"></span>
            <span x-text="selectedTask.title"></span>
          </div>

          <!-- Customize Section -->
          <div class="customize-panel">
            <!-- Color Row -->
            <div class="customize-row">
              <span class="customize-label">Color</span>
              <div class="color-palette">
                <button class="color-swatch color-none" :class="{ active: !selectedTask.color }" @click="updateTaskColor(selectedTask, null)" title="No color">✕</button>
                <template x-for="c in taskColors" :key="c">
                  <button class="color-swatch" :style="'background:'+c" :class="{ active: selectedTask.color === c }" @click="updateTaskColor(selectedTask, c)" :title="c"></button>
                </template>
              </div>
            </div>
            <!-- Emoji Row -->
            <div class="customize-row">
              <span class="customize-label">Emoji</span>
              <div class="emoji-palette">
                <button class="emoji-swatch emoji-none" :class="{ active: !selectedTask.emoji }" @click="updateTaskEmoji(selectedTask, '')" title="No emoji">✕</button>
                <template x-for="e in quickEmojis" :key="e">
                  <button class="emoji-swatch" :class="{ active: selectedTask.emoji === e }" @click="updateTaskEmoji(selectedTask, e)" x-text="e"></button>
                </template>
              </div>
            </div>
            <!-- Pin Row -->
            <div class="customize-row">
              <span class="customize-label">Pin</span>
              <button class="pin-toggle-btn" :class="{ active: selectedTask.pinned }" @click="togglePin(selectedTask)">
                <span>📌</span>
                <span x-text="selectedTask.pinned ? 'Pinned to top' : 'Pin to top'"></span>
              </button>
            </div>
          </div>

          <!-- Task info card -->
          <div class="task-info-card">
            <div class="task-info-item">
              <span class="task-info-label">Status</span>
              <span class="task-info-value" :class="selectedTask.completed ? 'status-done' : 'status-todo'"
                    x-text="selectedTask.completed ? '✓ Done' : '○ Todo'"></span>
            </div>
            <div class="task-info-item">
              <span class="task-info-label">Due date</span>
              <input type="date" class="info-input" x-model="selectedTask.due_date" @change="updateDueDate(selectedTask)">
            </div>
            <div class="task-info-item">
              <span class="task-info-label">Reminder</span>
              <input type="datetime-local" class="info-input" x-model="selectedTask.reminder" @change="updateReminder(selectedTask)">
            </div>
            <div class="task-info-item">
              <span class="task-info-label">Priority</span>
              <select class="info-input" x-model="selectedTask.priority" @change="updatePriority(selectedTask)">
                <option value="none">None</option>
                <option value="low">🟢 Low</option>
                <option value="medium">🟡 Medium</option>
                <option value="high">🔴 High</option>
              </select>
            </div>
            <div class="task-info-item">
              <span class="task-info-label">Subtasks</span>
              <span class="task-info-value" x-text="subtaskProgress(selectedTask)"></span>
            </div>
          </div>

          <div class="detail-actions">
            <template x-for="tag in tags" :key="tag.id">
              <span class="detail-pill" :class="selectedTask.tag === tag.name ? 'detail-pill-active' : ''" @click="assignTag(selectedTask, tag)" x-text="tag.name"></span>
            </template>
            <select class="task-list-select" style="width:auto;margin:0;padding:4px 10px;" x-model="selectedTask.list_id" @change="updateTaskList(selectedTask)">
              <template x-for="l in lists" :key="l.id"><option :value="l.id" x-text="l.name"></option></template>
            </select>
          </div>

          <!-- Progress bar -->
          <div style="margin-bottom:18px;" x-show="selectedTask.subtasks && selectedTask.subtasks.length > 0">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
              <span style="font-size:12px;color:#aaa;font-weight:500;letter-spacing:.04em;">PROGRESS</span>
              <span style="font-size:12px;color:#8a6d4a;font-weight:600;" x-text="taskProgressPct(selectedTask) + '%'"></span>
            </div>
            <div style="height:6px;background:rgba(0,0,0,0.06);border-radius:10px;overflow:hidden;">
              <div style="height:100%;background:#c4a882;border-radius:10px;transition:width .4s ease;" :style="'width:' + taskProgressPct(selectedTask) + '%'"></div>
            </div>
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
