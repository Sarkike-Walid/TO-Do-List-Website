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
