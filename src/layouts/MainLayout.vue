<template>
  <div class="main-layout">
    <Sidebar @toggle="handleToggle" />
    <div class="main-content" :style="{ marginLeft: isCollapsed ? '80px' : '250px' }">
      <router-view />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Sidebar from '../components/Sidebar.vue'

const isCollapsed = ref(false)

function handleToggle(collapsed) {
  isCollapsed.value = collapsed
}
</script>

<style scoped>
.main-layout {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  padding: 20px;
  background-color: var(--bg-color);
  transition: background-color 0.3s ease;
  min-height: 100vh;
}
</style>

