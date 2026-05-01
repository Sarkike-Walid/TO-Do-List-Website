/* ═══════════════════════════════════════════════════════════════
   Lumido Dashboard — Alpine.js data layer
   All task/list/tag data is persisted in MySQL via Laravel routes.
   Only dark-mode preference stays in localStorage.
   ═══════════════════════════════════════════════════════════════ */

document.addEventListener('alpine:init', () => {
  Alpine.data('dashboard', () => ({
    /* ── State ── */
    currentTab:    'my-day',
    tasks:         [],
    lists:         [],        // [{id, name, color}, ...]
    tags:          [],        // [{id, name, color}, ...]
    newTaskText:   '',
    newTaskList:   null,      // list id (number)
    selectedTask:  null,
    showListModal: false,
    showTagModal:  false,
    showTaskModal: false,
    newListName:   '',
    newTagName:    '',
    modalTaskText: '',
    modalTaskList: null,
    modalTaskDay:  0,
    userName:      '',
    popSound:      null,
    darkMode:      false,
    searchQuery:   '',
    searchOpen:    false,
    loading:       false,

    /* ── Init ── */
    async init() {
      this.userName = document.getElementById('app-user-name')?.value || 'User';
      this.popSound = this.createPopSound();

      // Restore dark mode from localStorage (UI preference, not data)
      const dm = localStorage.getItem('lumido_dark');
      this.darkMode = dm === 'true';
      this.applyDarkMode();

      // Load user data from DB
      await this.loadBootstrap();
    },

    /* ── API helpers ── */
    csrfToken() {
      return document.querySelector('meta[name="csrf-token"]')?.content || '';
    },

    async api(method, url, body = null) {
      const opts = {
        method,
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN':  this.csrfToken(),
          'Accept':        'application/json',
        },
      };
      if (body) opts.body = JSON.stringify(body);
      const res = await fetch(url, opts);
      if (!res.ok) {
        console.error('API error', method, url, res.status);
        return null;
      }
      return res.json();
    },

    /* ── Bootstrap ── */
    async loadBootstrap() {
      this.loading = true;
      const data = await this.api('GET', '/todo/bootstrap');
      if (data) {
        this.lists = data.lists || [];
        this.tags  = data.tags  || [];
        this.tasks = data.tasks || [];
        // Default list for new tasks
        if (!this.newTaskList && this.lists.length) {
          this.newTaskList  = this.lists[0].id;
          this.modalTaskList = this.lists[0].id;
        }
      }
      this.loading = false;
    },

    /* ── Dark mode ── */
    toggleDarkMode() {
      this.darkMode = !this.darkMode;
      localStorage.setItem('lumido_dark', this.darkMode);
      this.applyDarkMode();
    },

    applyDarkMode() {
      document.documentElement.setAttribute('data-theme', this.darkMode ? 'dark' : 'light');
    },

    /* ── Audio ── */
    createPopSound() {
      return {
        play: () => {
          try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const o = ctx.createOscillator();
            const g = ctx.createGain();
            o.connect(g); g.connect(ctx.destination);
            o.type = 'sine';
            o.frequency.setValueAtTime(800, ctx.currentTime);
            o.frequency.exponentialRampToValueAtTime(1200, ctx.currentTime + 0.05);
            o.frequency.exponentialRampToValueAtTime(600,  ctx.currentTime + 0.15);
            g.gain.setValueAtTime(0.3,  ctx.currentTime);
            g.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.2);
            o.start(ctx.currentTime); o.stop(ctx.currentTime + 0.2);
          } catch(e) {}
        }
      };
    },

    /* ── Greeting / date helpers ── */
    getGreeting() {
      const h = new Date().getHours();
      if (h < 12) return 'Good morning';
      if (h < 17) return 'Good afternoon';
      return 'Good evening';
    },
    getToday() { return new Date(); },

    getDayLabel(offset) {
      const d = new Date(); d.setDate(d.getDate() + offset);
      const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
      if (offset === 0) return '<strong>Today</strong> ' + days[d.getDay()];
      if (offset === 1) return '<strong>Tomorrow</strong> ' + days[d.getDay()];
      return '<strong>' + days[d.getDay()] + '</strong>';
    },

    getDateStr(offset) {
      const d = new Date(); d.setDate(d.getDate() + offset);
      return d.toISOString().split('T')[0];
    },

    /* ── Filtered task views ── */
    todayTasks() {
      return this.tasks.filter(t => t.due_date === this.getDateStr(0) || t.is_my_day);
    },
    dayTasks(offset) {
      return this.tasks.filter(t => t.due_date === this.getDateStr(offset));
    },
    taskCount() {
      return this.todayTasks().filter(t => !t.completed).length;
    },
    next7Count() {
      let c = 0;
      for (let i = 0; i < 7; i++) c += this.dayTasks(i).filter(t => !t.completed).length;
      return c;
    },
    allTaskCount() {
      return this.tasks.filter(t => !t.completed).length;
    },
    listTaskCount(listId) {
      return this.tasks.filter(t => t.list_id === listId).length;
    },
    listNameById(id) {
      return this.lists.find(l => l.id === id)?.name ?? '';
    },

    /* ── Search ── */
    searchResults() {
      if (!this.searchQuery || this.searchQuery.trim().length < 1) return [];
      const q = this.searchQuery.toLowerCase();
      const results = [];
      this.tasks.forEach(t => {
        if (t.title.toLowerCase().includes(q)) {
          results.push({ id: 'task-' + t.id, title: t.title, meta: 'Task in ' + t.list, type: 'Task', ref: t });
        }
      });
      this.lists.forEach(l => {
        if (l.name.toLowerCase().includes(q)) {
          results.push({ id: 'list-' + l.id, title: l.name, meta: this.listTaskCount(l.id) + ' tasks', type: 'List', ref: null });
        }
      });
      this.tags.forEach(tag => {
        if (tag.name.toLowerCase().includes(q)) {
          results.push({ id: 'tag-' + tag.id, title: tag.name, meta: 'Tag', type: 'Tag', ref: null });
        }
      });
      return results.slice(0, 8);
    },

    openSearchResult(r) {
      if (r.type === 'Task' && r.ref) {
        this.currentTab = 'all-tasks';
        const t = this.tasks.find(x => x.id === r.ref.id);
        this.selectTask(t || r.ref);
        setTimeout(() => { 
          try {
            const el = document.getElementById('task-item-' + (t ? t.id : r.ref.id));
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
          } catch(e) {}
        }, 150);
      } else if (r.type === 'List') {
        this.currentTab = 'my-lists';
      } else if (r.type === 'Tag') {
        this.currentTab = 'tags';
      }
      this.searchQuery = '';
      this.searchOpen  = false;
    },

    /* ── Add tasks ── */
    async addQuickTask() {
      if (!this.newTaskText.trim()) return;
      const listId = this.newTaskList || this.lists[0]?.id;
      if (!listId) return;
      const task = await this.api('POST', '/todo/tasks', {
        title:     this.newTaskText.trim(),
        list_id:   listId,
        is_my_day: true,
        due_date:  this.getDateStr(0),
      });
      if (task) this.tasks.push(task);
      this.newTaskText = '';
    },

    async addDayTask() {
      if (!this.modalTaskText.trim()) return;
      const listId = this.modalTaskList || this.lists[0]?.id;
      if (!listId) return;
      const task = await this.api('POST', '/todo/tasks', {
        title:    this.modalTaskText.trim(),
        list_id:  listId,
        due_date: this.getDateStr(this.modalTaskDay),
      });
      if (task) this.tasks.push(task);
      this.modalTaskText  = '';
      this.showTaskModal  = false;
    },

    /* ── Toggle task completion ── */
    async toggleTask(task) {
      const newStatus = task.completed ? 'todo' : 'done';
      const updated = await this.api('PATCH', '/todo/tasks/' + task.id, { status: newStatus });
      if (updated) {
        const idx = this.tasks.findIndex(t => t.id === task.id);
        if (idx !== -1) this.tasks[idx] = updated;
        if (this.selectedTask?.id === task.id) this.selectedTask = updated;
        if (newStatus === 'done' && this.popSound) this.popSound.play();
      }
    },

    selectTask(task) {
      this.selectedTask = task;
    },

    /* ── Lists ── */
    async addList() {
      if (!this.newListName.trim()) return;
      const list = await this.api('POST', '/todo/lists', { name: this.newListName.trim() });
      if (list) {
        this.lists.push(list);
        if (!this.newTaskList) { this.newTaskList = list.id; this.modalTaskList = list.id; }
      }
      this.newListName  = '';
      this.showListModal = false;
    },

    async deleteList(id) {
      const res = await this.api('DELETE', '/todo/lists/' + id);
      if (res) {
        this.lists = this.lists.filter(l => l.id !== id);
        this.tasks = this.tasks.filter(t => t.list_id !== id);
      }
    },

    /* ── Tags ── */
    async addTag() {
      if (!this.newTagName.trim()) return;
      const tag = await this.api('POST', '/todo/tags', { name: this.newTagName.trim() });
      if (tag && !this.tags.find(t => t.id === tag.id)) this.tags.push(tag);
      this.newTagName  = '';
      this.showTagModal = false;
    },

    async deleteTag(id) {
      const res = await this.api('DELETE', '/todo/tags/' + id);
      if (res) {
        this.tags = this.tags.filter(t => t.id !== id);
        // Remove tag from tasks
        this.tasks.forEach(t => { if (t.tag_id === id) { t.tag = ''; t.tag_id = null; } });
      }
    },

    /* ── Delete task ── */
    async deleteTask(task) {
      const res = await this.api('DELETE', '/todo/tasks/' + task.id);
      if (res) {
        this.tasks = this.tasks.filter(t => t.id !== task.id);
        if (this.selectedTask?.id === task.id) this.selectedTask = null;
      }
    },

    /* ── Clear completed ── */
    async clearCompleted() {
      const completed = this.tasks.filter(t => t.completed);
      for (const t of completed) await this.api('DELETE', '/todo/tasks/' + t.id);
      this.tasks = this.tasks.filter(t => !t.completed);
      this.selectedTask = null;
    },

    /* ── Subtasks ── */
    async addSubtask(task, name) {
      if (!name || !name.trim()) return;
      const sub = await this.api('POST', '/todo/tasks/' + task.id + '/subtasks', { title: name.trim() });
      if (sub) {
        const idx = this.tasks.findIndex(t => t.id === task.id);
        if (idx !== -1) {
          this.tasks[idx].subtasks.push({ id: sub.id, text: sub.title, done: sub.is_completed });
          if (this.selectedTask?.id === task.id) this.selectedTask = this.tasks[idx];
        }
      }
    },

    async toggleSubtask(task, idx) {
      const sub = task.subtasks[idx];
      const updated = await this.api('PATCH', '/todo/subtasks/' + sub.id, { is_completed: !sub.done });
      if (updated) {
        sub.done = updated.is_completed;
      }
    },

    subtaskProgress(task) {
      if (!task.subtasks || task.subtasks.length === 0) return '0/0';
      const done = task.subtasks.filter(s => s.done).length;
      return done + '/' + task.subtasks.length;
    },

    /* ── Attachments ── */
    async uploadAttachment(task, event) {
      const file = event.target.files[0];
      if (!file) return;
      
      const formData = new FormData();
      formData.append('file', file);
      
      const res = await fetch('/todo/tasks/' + task.id + '/attachments', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': this.csrfToken(), 'Accept': 'application/json' },
        body: formData
      });
      
      if (res.ok) {
        const att = await res.json();
        const idx = this.tasks.findIndex(t => t.id === task.id);
        if (idx !== -1) {
          if (!this.tasks[idx].attachments) this.tasks[idx].attachments = [];
          this.tasks[idx].attachments.unshift(att);
          if (this.selectedTask?.id === task.id) this.selectedTask = this.tasks[idx];
        }
      }
      event.target.value = '';
    },

    async deleteAttachment(task, attId) {
      const res = await this.api('DELETE', '/todo/attachments/' + attId);
      if (res) {
        const idx = this.tasks.findIndex(t => t.id === task.id);
        if (idx !== -1) {
          this.tasks[idx].attachments = this.tasks[idx].attachments.filter(a => a.id !== attId);
          if (this.selectedTask?.id === task.id) this.selectedTask = this.tasks[idx];
        }
      }
    },

    /* ── Assign tag to task ── */
    async assignTag(task, tag) {
      const res = await this.api('POST', '/todo/tasks/' + task.id + '/tags/' + tag.id);
      if (res) {
        const idx = this.tasks.findIndex(t => t.id === task.id);
        if (idx !== -1) {
          if (res.attached) {
            this.tasks[idx].tag    = tag.name;
            this.tasks[idx].tag_id = tag.id;
          } else {
            this.tasks[idx].tag    = '';
            this.tasks[idx].tag_id = null;
          }
          if (this.selectedTask?.id === task.id) this.selectedTask = this.tasks[idx];
        }
      }
    },

    /* ── Update task notes (debounced via direct PATCH) ── */
    async updateNote(task) {
      await this.api('PATCH', '/todo/tasks/' + task.id, { note: task.note });
    },

    /* ── Update due date ── */
    async updateDueDate(task) {
      if (task.due_date && task.due_date !== this.getDateStr(0)) {
        task.is_my_day = false;
      } else if (task.due_date === this.getDateStr(0)) {
        task.is_my_day = true;
      }
      await this.api('PATCH', '/todo/tasks/' + task.id, { 
        due_date: task.due_date,
        is_my_day: task.is_my_day
      });
    },

    /* ── Update task list ── */
    async updateTaskList(task) {
      await this.api('PATCH', '/todo/tasks/' + task.id, { list_id: task.list_id });
      // Update the list name shown
      task.list = this.listNameById(task.list_id);
    },

    /* ── Day modal ── */
    openDayTaskModal(dayOffset) {
      this.modalTaskDay  = dayOffset;
      this.modalTaskText = '';
      this.modalTaskList = this.lists[0]?.id || null;
      this.showTaskModal = true;
    },

    scrollDays(dir) {
      const el = document.getElementById('days-scroll');
      if (el) el.scrollBy({ left: dir * 240, behavior: 'smooth' });
    },
  }));
});
