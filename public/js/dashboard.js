/* ═══════════════════════════════════════════════════════════════
   Lumido Dashboard — Alpine.js data layer
   All task/list/tag data is persisted in MySQL via Laravel routes.
   Only dark-mode preference stays in localStorage.
   ═══════════════════════════════════════════════════════════════ */

document.addEventListener('alpine:init', () => {
  Alpine.data('dashboard', () => ({
    /* ── State ── */
    currentTab: 'my-day',
    tasks: [],
    lists: [],        // [{id, name, color}, ...]
    tags: [],        // [{id, name, color}, ...]
    newTaskText: '',
    newTaskList: null,      // list id (number)
    selectedTask: null,
    showListModal: false,
    showTagModal: false,
    showTaskModal: false,
    newListName: '',
    newTagName: '',
    modalTaskText: '',
    modalTaskList: null,
    modalTaskDay: 0,
    userName: '',
    popSound: null,
    darkMode: false,
    searchQuery: '',
    searchOpen: false,
    loading: false,
    filterStatus: 'all',
    sortBy: 'none',
    notifOpen: false,
    dismissedNotifs: [],
    taskColors: ['#e74c3c', '#e67e22', '#f1c40f', '#2ecc71', '#1abc9c', '#3498db', '#9b59b6', '#c4a882', '#e91e63', '#607d8b'],
    quickEmojis: ['✅', '🔥', '📚', '🕒', '⭐', '🎯', '💡', '📌', '🚀', '💪', '⚡', '🎉', '🧠', '🏆', '⏰', '🗓️', '📋', '🛠️'],
    toasts: [],

    /* ── Settings State ── */
    showSettingsModal: false,
    settingsTab: 'profile',
    userEmail: '',
    userAvatar: '',
    settingsName: '',
    settingsEmail: '',
    settingsCurrentPassword: '',
    settingsNewPassword: '',
    settingsConfirmPassword: '',
    theme: 'light',

    /* ── Crop State ── */
    showCropModal: false,
    cropImage: null,
    cropZoom: 1,
    cropX: 0,
    cropY: 0,
    cropDragging: false,
    cropDragStartX: 0,
    cropDragStartY: 0,
    cropFile: null,

    /* ── Init ── */
    async init() {
      this.userName = document.getElementById('app-user-name')?.value || 'User';
      this.userEmail = document.getElementById('app-user-email')?.value || '';
      this.userAvatar = document.getElementById('app-user-avatar')?.value || '';
      this.popSound = this.createPopSound();

      const validTabs = ['my-day', 'next-7-days', 'all-tasks', 'my-lists', 'tags'];

      // Check URL path for initial tab
      const pathParts = window.location.pathname.split('/');
      const pathTab = pathParts.length > 2 ? pathParts[2] : '';
      if (validTabs.includes(pathTab)) {
        this.currentTab = pathTab;
      }

      // Watch for changes to currentTab and update the URL
      this.$watch('currentTab', (value) => {
        const expectedPath = '/dashboard/' + value;
        if (window.location.pathname !== expectedPath && validTabs.includes(value)) {
          window.history.pushState(null, '', expectedPath);
        }
      });

      // Handle browser back/forward buttons
      window.addEventListener('popstate', () => {
        const parts = window.location.pathname.split('/');
        const tab = parts.length > 2 ? parts[2] : 'my-day';
        if (validTabs.includes(tab)) {
          this.currentTab = tab;
        } else {
          this.currentTab = 'my-day';
        }
      });

      // Restore dark mode from localStorage
      const dm = localStorage.getItem('lumido_dark');
      this.darkMode = dm === 'true';
      this.theme = this.darkMode ? 'dark' : 'light';
      this.applyDarkMode();

      // Restore dismissed notifications
      const dismissed = localStorage.getItem('lumido_dismissed_notifs');
      this.dismissedNotifs = dismissed ? JSON.parse(dismissed) : [];

      // Load user data from DB
      await this.loadBootstrap();
    },

    /* ── Settings Methods ── */
    openSettings() {
      this.settingsName = this.userName;
      this.settingsEmail = this.userEmail;
      this.settingsCurrentPassword = '';
      this.settingsNewPassword = '';
      this.settingsConfirmPassword = '';
      this.settingsTab = 'profile';
      this.showSettingsModal = true;
    },

    setTheme(newTheme) {
      this.theme = newTheme;
      this.darkMode = newTheme === 'dark';
      this.applyDarkMode();
    },

    async uploadAvatar(e) {
      const file = e.target.files[0];
      if (!file) return;

      const formData = new FormData();
      formData.append('avatar', file);

      try {
        const res = await fetch('/profile/avatar/ajax', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': this.csrfToken() },
          body: formData
        });
        const data = await res.json();
        if (data.status === 'avatar-updated') {
          this.userAvatar = data.avatar_url;
          this.showToast('Profile photo updated.');
        } else {
          this.showToast('Failed to upload photo.', true);
        }
      } catch (err) {
        this.showToast('Error uploading photo.', true);
      }
    },

    /* ── Crop Modal Methods ── */
    openCropModal(e) {
      const file = e.target.files[0];
      if (!file) return;
      this.cropFile = file;
      this.cropZoom = 1;
      this.cropX = 0;
      this.cropY = 0;
      this.showCropModal = true;

      const reader = new FileReader();
      reader.onload = (ev) => {
        this.cropImage = new Image();
        this.cropImage.onload = () => {
          this.$nextTick(() => this.initCropCanvas());
        };
        this.cropImage.src = ev.target.result;
      };
      reader.readAsDataURL(file);
    },

    initCropCanvas() {
      const canvas = document.getElementById('crop-canvas');
      if (!canvas) return;
      const ctx = canvas.getContext('2d');
      this.drawCrop(ctx, canvas);

      canvas.onmousedown = (e) => {
        this.cropDragging = true;
        this.cropDragStartX = e.clientX - this.cropX;
        this.cropDragStartY = e.clientY - this.cropY;
      };
      canvas.onmousemove = (e) => {
        if (!this.cropDragging) return;
        this.cropX = e.clientX - this.cropDragStartX;
        this.cropY = e.clientY - this.cropDragStartY;
        this.drawCrop(ctx, canvas);
      };
      canvas.onmouseup = () => { this.cropDragging = false; };
      canvas.onmouseleave = () => { this.cropDragging = false; };

      canvas.ontouchstart = (e) => {
        const t = e.touches[0];
        this.cropDragging = true;
        this.cropDragStartX = t.clientX - this.cropX;
        this.cropDragStartY = t.clientY - this.cropY;
      };
      canvas.ontouchmove = (e) => {
        if (!this.cropDragging) return;
        e.preventDefault();
        const t = e.touches[0];
        this.cropX = t.clientX - this.cropDragStartX;
        this.cropY = t.clientY - this.cropDragStartY;
        this.drawCrop(ctx, canvas);
      };
      canvas.ontouchend = () => { this.cropDragging = false; };

      canvas.onwheel = (e) => {
        e.preventDefault();
        const delta = e.deltaY > 0 ? -0.05 : 0.05;
        this.cropZoom = Math.max(1, Math.min(3, this.cropZoom + delta));
        document.getElementById('crop-zoom').value = this.cropZoom;
        this.drawCrop(ctx, canvas);
      };
    },

    drawCrop(ctx, canvas) {
      if (!this.cropImage) return;
      const w = canvas.width, h = canvas.height;
      ctx.clearRect(0, 0, w, h);
      ctx.fillStyle = '#111';
      ctx.fillRect(0, 0, w, h);

      const img = this.cropImage;
      const scale = this.cropZoom;
      const aspect = img.width / img.height;
      let drawW, drawH;
      if (aspect > 1) {
        drawH = w * scale;
        drawW = drawH * aspect;
      } else {
        drawW = w * scale;
        drawH = drawW / aspect;
      }
      const dx = (w - drawW) / 2 + this.cropX;
      const dy = (h - drawH) / 2 + this.cropY;
      ctx.drawImage(img, dx, dy, drawW, drawH);

      ctx.globalCompositeOperation = 'destination-in';
      ctx.beginPath();
      ctx.arc(w / 2, h / 2, w / 2, 0, Math.PI * 2);
      ctx.fill();
      ctx.globalCompositeOperation = 'source-over';

      ctx.strokeStyle = 'rgba(196,168,130,0.6)';
      ctx.lineWidth = 2;
      ctx.beginPath();
      ctx.arc(w / 2, h / 2, w / 2 - 1, 0, Math.PI * 2);
      ctx.stroke();
    },

    updateCropZoom(e) {
      this.cropZoom = parseFloat(e.target.value);
      const canvas = document.getElementById('crop-canvas');
      if (canvas) this.drawCrop(canvas.getContext('2d'), canvas);
    },

    closeCropModal() {
      this.showCropModal = false;
      this.cropImage = null;
      this.cropFile = null;
      if (this.$refs.avatarInput) this.$refs.avatarInput.value = '';
    },

    async applyCrop() {
      const canvas = document.getElementById('crop-canvas');
      if (!canvas) return;
      canvas.toBlob(async (blob) => {
        if (!blob) {
          this.showToast('Could not generate image.', true);
          return;
        }
        const formData = new FormData();
        formData.append('avatar', blob, 'avatar.png');
        try {
          const res = await fetch('/profile/avatar/ajax', {
            method: 'POST',
            headers: { 
              'X-CSRF-TOKEN': this.csrfToken(),
              'Accept': 'application/json'
            },
            body: formData
          });
          const data = await res.json();
          if (res.ok && data.status === 'avatar-updated') {
            this.userAvatar = data.avatar_url;
            this.showToast('Profile photo updated.');
            this.closeCropModal();
          } else {
            this.showToast(data.message || 'Failed to upload photo.', true);
          }
        } catch (err) {
          console.error(err);
          this.showToast('Error uploading photo.', true);
        }
      }, 'image/png');
    },

    async deleteAvatar() {
      try {
        const res = await fetch('/profile/avatar/ajax', {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': this.csrfToken(),
            'Accept': 'application/json'
          }
        });
        const data = await res.json();
        if (data.status === 'avatar-deleted') {
          this.userAvatar = '';
          this.showToast('Profile photo removed.');
        } else {
          this.showToast('Failed to remove photo.', true);
        }
      } catch (err) {
        this.showToast('Error removing photo.', true);
      }
    },

    async updateProfile() {
      try {
        const res = await fetch('/profile/ajax', {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.csrfToken()
          },
          body: JSON.stringify({
            name: this.settingsName,
            email: this.settingsEmail
          })
        });
        if (res.ok) {
          const data = await res.json();
          this.userName = data.user.name;
          this.userEmail = data.user.email;
          this.showToast('Profile updated successfully.');
        } else {
          const err = await res.json();
          this.showToast(err.message || 'Validation failed', true);
        }
      } catch (err) {
        this.showToast('Error updating profile.', true);
      }
    },

    async updatePassword() {
      try {
        const res = await fetch('/profile/password/ajax', {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.csrfToken()
          },
          body: JSON.stringify({
            current_password: this.settingsCurrentPassword,
            password: this.settingsNewPassword,
            password_confirmation: this.settingsConfirmPassword
          })
        });
        if (res.ok) {
          this.showToast('Password updated securely.');
          this.settingsCurrentPassword = '';
          this.settingsNewPassword = '';
          this.settingsConfirmPassword = '';
        } else {
          const err = await res.json();
          this.showToast(err.message || 'Password update failed', true);
        }
      } catch (err) {
        this.showToast('Error updating password.', true);
      }
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
          'X-CSRF-TOKEN': this.csrfToken(),
          'Accept': 'application/json',
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
        this.tags = data.tags || [];
        this.tasks = data.tasks || [];
        // Default list for new tasks
        if (!this.newTaskList && this.lists.length) {
          this.newTaskList = this.lists[0].id;
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
            o.frequency.exponentialRampToValueAtTime(600, ctx.currentTime + 0.15);
            g.gain.setValueAtTime(0.3, ctx.currentTime);
            g.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.2);
            o.start(ctx.currentTime); o.stop(ctx.currentTime + 0.2);
          } catch (e) { }
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
      const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
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

    /* ── Filter & Sort ── */
    filteredSortedTasks() {
      let t = [...this.tasks];
      if (this.filterStatus === 'active') t = t.filter(x => !x.completed);
      if (this.filterStatus === 'completed') t = t.filter(x => x.completed);
      if (this.sortBy === 'name') {
        t.sort((a, b) => a.title.localeCompare(b.title));
      } else if (this.sortBy === 'date') {
        t.sort((a, b) => (a.due_date || '9999') < (b.due_date || '9999') ? -1 : 1);
      } else if (this.sortBy === 'priority') {
        const rank = { high: 0, medium: 1, low: 2, none: 3, '': 3, undefined: 3 };
        t.sort((a, b) => (rank[a.priority] ?? 3) - (rank[b.priority] ?? 3));
      }
      // Pinned always at top
      t.sort((a, b) => (b.pinned ? 1 : 0) - (a.pinned ? 1 : 0));
      return t;
    },

    /* ── Progress ── */
    taskProgressPct(task) {
      if (!task.subtasks || task.subtasks.length === 0) return 0;
      return Math.round((task.subtasks.filter(s => s.done).length / task.subtasks.length) * 100);
    },

    /* ── Notifications ── */
    notifItems() {
      const items = [];
      const now = new Date();
      const today = this.getDateStr(0);

      this.tasks.forEach(task => {
        if (task.completed) return;

        // Reminder: has a reminder datetime that is within the next 24h or already past
        if (task.reminder) {
          const reminderTime = new Date(task.reminder);
          const diffMs = reminderTime - now;
          const diffH = diffMs / (1000 * 60 * 60);
          if (diffH <= 24) {
            const id = 'reminder-' + task.id;
            if (!this.dismissedNotifs.includes(id)) {
              const isPast = diffMs < 0;
              items.push({
                id,
                type: 'reminder',
                taskId: task.id,
                title: '⏰ Reminder: ' + task.title,
                meta: isPast
                  ? 'Was due ' + reminderTime.toLocaleString('en', { dateStyle: 'short', timeStyle: 'short' })
                  : 'At ' + reminderTime.toLocaleString('en', { dateStyle: 'short', timeStyle: 'short' }),
              });
            }
          }
        }

        // Overdue: has a past due_date and not completed
        if (task.due_date && task.due_date < today) {
          const id = 'overdue-' + task.id;
          if (!this.dismissedNotifs.includes(id)) {
            items.push({
              id,
              type: 'overdue',
              taskId: task.id,
              title: task.title,
              meta: '⚠️ Overdue since ' + new Date(task.due_date + 'T00:00:00').toLocaleDateString('en', { day: 'numeric', month: 'short' }),
            });
          }
        }

        // Due today: show for all tasks due today that aren't completed
        if (task.due_date === today) {
          const id = 'due-today-' + task.id;
          // Only show if not already shown as a reminder notification to avoid duplicates
          const alreadyReminder = items.some(it => it.taskId === task.id && it.type === 'reminder');
          if (!alreadyReminder && !this.dismissedNotifs.includes(id)) {
            items.push({
              id,
              type: 'due-today',
              taskId: task.id,
              title: task.title,
              meta: '📅 Due today',
            });
          }
        }
      });

      // Sort: overdue first, then reminders, then due-today
      const typeOrder = { overdue: 0, reminder: 1, 'due-today': 2 };
      return items.sort((a, b) => (typeOrder[a.type] ?? 9) - (typeOrder[b.type] ?? 9));
    },

    dismissNotif(id) {
      if (!this.dismissedNotifs.includes(id)) {
        this.dismissedNotifs.push(id);
        localStorage.setItem('lumido_dismissed_notifs', JSON.stringify(this.dismissedNotifs));
      }
    },

    markAllNotifRead() {
      this.notifItems().forEach(item => this.dismissNotif(item.id));
      this.notifOpen = false;
    },

    openNotif(item) {
      const task = this.tasks.find(t => t.id === item.taskId);
      if (task) {
        this.currentTab = 'all-tasks';
        this.selectTask(task);
        setTimeout(() => {
          const el = document.getElementById('task-item-' + task.id);
          if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 150);
      }
      this.dismissNotif(item.id);
      this.notifOpen = false;
    },

    async completeFromNotif(item) {
      const task = this.tasks.find(t => t.id === item.taskId);
      if (task) {
        await this.toggleTask(task);
        this.dismissNotif(item.id);
      }
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
          } catch (e) { }
        }, 150);
      } else if (r.type === 'List') {
        this.currentTab = 'my-lists';
      } else if (r.type === 'Tag') {
        this.currentTab = 'tags';
      }
      this.searchQuery = '';
      this.searchOpen = false;
    },

    /* ── Add tasks ── */
    async addQuickTask() {
      if (!this.newTaskText.trim()) return;
      const listId = this.newTaskList || this.lists[0]?.id;
      if (!listId) return;
      const task = await this.api('POST', '/todo/tasks', {
        title: this.newTaskText.trim(),
        list_id: listId,
        is_my_day: true,
        due_date: this.getDateStr(0),
      });
      if (task) this.tasks.push(task);
      this.newTaskText = '';
    },

    async addDayTask() {
      if (!this.modalTaskText.trim()) return;
      const listId = this.modalTaskList || this.lists[0]?.id;
      if (!listId) return;
      const task = await this.api('POST', '/todo/tasks', {
        title: this.modalTaskText.trim(),
        list_id: listId,
        due_date: this.getDateStr(this.modalTaskDay),
      });
      if (task) this.tasks.push(task);
      this.modalTaskText = '';
      this.showTaskModal = false;
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

        // When unchecking a task, restore its notifications
        if (newStatus === 'todo') {
          const notifKeys = ['reminder-', 'overdue-', 'due-today-'];
          notifKeys.forEach(prefix => {
            const id = prefix + task.id;
            const i = this.dismissedNotifs.indexOf(id);
            if (i !== -1) this.dismissedNotifs.splice(i, 1);
          });
          localStorage.setItem('lumido_dismissed_notifs', JSON.stringify(this.dismissedNotifs));
        }
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
      this.newListName = '';
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
      this.newTagName = '';
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
            this.tasks[idx].tag = tag.name;
            this.tasks[idx].tag_id = tag.id;
          } else {
            this.tasks[idx].tag = '';
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
      task.list = this.listNameById(task.list_id);
    },

    /* ── Update priority ── */
    async updatePriority(task) {
      await this.api('PATCH', '/todo/tasks/' + task.id, { priority: task.priority });
    },

    /* ── Update recurring ── */
    async updateRecurring(task) {
      await this.api('PATCH', '/todo/tasks/' + task.id, { recurring: task.recurring });
    },

    /* ── Update reminder ── */
    async updateReminder(task) {
      await this.api('PATCH', '/todo/tasks/' + task.id, { reminder: task.reminder });
    },

    /* ── Task Color ── */
    async updateTaskColor(task, color) {
      const res = await this.api('PATCH', '/todo/tasks/' + task.id, { color });
      if (res) {
        const idx = this.tasks.findIndex(t => t.id === task.id);
        if (idx !== -1) { this.tasks[idx].color = color; this.tasks[idx] = { ...this.tasks[idx] }; }
        if (this.selectedTask?.id === task.id) this.selectedTask = { ...this.selectedTask, color };
      }
    },

    /* ── Task Emoji ── */
    async updateTaskEmoji(task, emoji) {
      const res = await this.api('PATCH', '/todo/tasks/' + task.id, { emoji });
      if (res) {
        const idx = this.tasks.findIndex(t => t.id === task.id);
        if (idx !== -1) { this.tasks[idx].emoji = emoji; this.tasks[idx] = { ...this.tasks[idx] }; }
        if (this.selectedTask?.id === task.id) this.selectedTask = { ...this.selectedTask, emoji };
      }
    },

    /* ── Pin task ── */
    async togglePin(task) {
      const pinned = !task.pinned;
      const res = await this.api('PATCH', '/todo/tasks/' + task.id, { pinned });
      if (res) {
        const idx = this.tasks.findIndex(t => t.id === task.id);
        if (idx !== -1) { this.tasks[idx].pinned = pinned; this.tasks[idx] = { ...this.tasks[idx] }; }
        if (this.selectedTask?.id === task.id) this.selectedTask = { ...this.selectedTask, pinned };
      }
    },

    /* ── Day modal ── */
    openDayTaskModal(dayOffset) {
      this.modalTaskDay = dayOffset;
      this.modalTaskText = '';
      this.modalTaskList = this.lists[0]?.id || null;
      this.showTaskModal = true;
    },

    scrollDays(dir) {
      const el = document.getElementById('days-scroll');
      if (el) el.scrollBy({ left: dir * 240, behavior: 'smooth' });
    },

    showToast(msg, isError = false) {
      const id = Date.now();
      this.toasts.push({ id, msg, error: isError });
      setTimeout(() => {
        this.toasts = this.toasts.filter(t => t.id !== id);
      }, 4000);
    },
  }));
});
