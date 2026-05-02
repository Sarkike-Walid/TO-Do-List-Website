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
