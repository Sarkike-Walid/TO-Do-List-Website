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

<!-- Settings Modal -->
<div class="modal-overlay" x-show="showSettingsModal" x-transition @click.self="showSettingsModal = false" style="display:none;z-index:999;">
  <div class="modal-box settings-modal" style="max-width:500px;padding:30px;">
    <button class="modal-close" @click="showSettingsModal = false">✕</button>
    <h3 style="margin-bottom:20px;">Settings</h3>
    
    <div class="settings-tabs">
      <button :class="{ active: settingsTab === 'profile' }" @click="settingsTab = 'profile'">Profile</button>
      <button :class="{ active: settingsTab === 'security' }" @click="settingsTab = 'security'">Security</button>
      <button :class="{ active: settingsTab === 'appearance' }" @click="settingsTab = 'appearance'">Appearance</button>
    </div>

    <!-- Profile Tab -->
    <div x-show="settingsTab === 'profile'" class="settings-panel">
      <div class="avatar-upload-area" @click="$refs.avatarInput.click()">
        <template x-if="userAvatar">
          <img :src="userAvatar" alt="Avatar"/>
        </template>
        <template x-if="!userAvatar">
          <div class="avatar-placeholder" x-text="userName.charAt(0).toUpperCase()"></div>
        </template>
        <div class="avatar-overlay"><span>Change</span></div>
      </div>
      <input type="file" x-ref="avatarInput" accept="image/*" style="display:none;" @change="openCropModal($event)"/>
      
      <!-- Delete Photo Button -->
      <template x-if="userAvatar">
        <button class="delete-photo-btn" @click="deleteAvatar()">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
          Remove photo
        </button>
      </template>

      <div class="form-group">
        <label>Username</label>
        <input type="text" x-model="settingsName" class="settings-input"/>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" x-model="settingsEmail" class="settings-input"/>
      </div>
      <button class="modal-submit" @click="updateProfile()">Save Profile</button>
    </div>

    <!-- Security Tab -->
    <div x-show="settingsTab === 'security'" class="settings-panel" style="display:none;">
      <div class="form-group">
        <label>Current Password</label>
        <input type="password" x-model="settingsCurrentPassword" class="settings-input"/>
      </div>
      <div class="form-group">
        <label>New Password</label>
        <input type="password" x-model="settingsNewPassword" class="settings-input"/>
      </div>
      <div class="form-group">
        <label>Confirm New Password</label>
        <input type="password" x-model="settingsConfirmPassword" class="settings-input"/>
      </div>
      <button class="modal-submit" @click="updatePassword()">Update Password</button>
    </div>

    <!-- Appearance Tab -->
    <div x-show="settingsTab === 'appearance'" class="settings-panel" style="display:none;">
      <div class="theme-options">
        <div class="theme-option" :class="{ active: theme === 'light' }" @click="setTheme('light')">
          <div class="theme-preview light-preview"></div>
          <span>Light</span>
        </div>
        <div class="theme-option" :class="{ active: theme === 'dark' }" @click="setTheme('dark')">
          <div class="theme-preview dark-preview"></div>
          <span>Dark</span>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Crop/Zoom Avatar Modal -->
<div class="modal-overlay" x-show="showCropModal" x-transition @click.self="closeCropModal()" style="display:none;z-index:1000;">
  <div class="modal-box crop-modal" style="max-width:440px;padding:30px;">
    <button class="modal-close" @click="closeCropModal()">✕</button>
    <h3 style="margin-bottom:16px;">Crop your photo</h3>
    
    <div class="crop-container" id="crop-container">
      <canvas id="crop-canvas" width="300" height="300"></canvas>
    </div>
    
    <div class="crop-controls">
      <div class="zoom-control">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
        <input type="range" id="crop-zoom" min="1" max="3" step="0.05" value="1" @input="updateCropZoom($event)"/>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
      </div>
      <p class="crop-hint">Drag to reposition • Scroll to zoom</p>
    </div>
    
    <button class="modal-submit" @click="applyCrop()">Save Photo</button>
  </div>
</div>
