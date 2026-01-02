<template>
  <div class="sidebar" :class="{ 'collapsed': isCollapsed }">
    <div class="sidebar-header">
      <h2 v-if="!isCollapsed">Kadirov Inc</h2>
      <h2 v-else>Inc</h2>
    </div>
    
    <div class="user-profile" v-if="!isCollapsed">
      <div class="avatar">
        <img src="https://ui-avatars.com/api/?name=Ameliya&background=4A90E2&color=fff" alt="User" />
      </div>
      <div class="user-info">
        <div class="user-name">{{ user?.fullName || 'Ameliya' }}</div>
        <div class="user-email">{{ user?.email || 'ameliya.cer@gmail.com' }}</div>
      </div>
    </div>
    <div class="user-profile collapsed-profile" v-else>
      <div class="avatar small-avatar">
        <img src="https://ui-avatars.com/api/?name=Ameliya&background=4A90E2&color=fff" alt="User" />
      </div>
    </div>

    <nav class="sidebar-nav">
      <router-link to="/" class="nav-item" :class="{ active: $route.name === 'Users' }" title="Foydalanuvchilar">
        <span class="icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        </span>
        <span v-if="!isCollapsed">Foydalanuvchilar</span>
      </router-link>
      <router-link to="/companies" class="nav-item" :class="{ active: $route.name === 'Companies' }" title="Kompaniyalar">
        <span class="icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
        </span>
        <span v-if="!isCollapsed">Kompaniyalar</span>
      </router-link>
      <router-link to="/clients" class="nav-item" :class="{ active: $route.name === 'Clients' }" title="Mijozlar">
        <span class="icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </span>
        <span v-if="!isCollapsed">Mijozlar</span>
      </router-link>
      <div class="separator"></div>
      <router-link to="/settings" class="nav-item" :class="{ active: $route.name === 'Settings' }" title="Sozlamalar">
        <span class="icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        </span>
        <span v-if="!isCollapsed">Sozlamalar</span>
      </router-link>
      <a href="#" class="nav-item logout-item" @click.prevent="handleLogout" title="Chiqish">
        <span class="icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        </span>
        <span v-if="!isCollapsed">Chiqish</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <a href="#" class="close-menu" @click.prevent="toggleSidebar" :title="isCollapsed ? 'Menyuni ochish' : 'Menyuni yopish'">
        <span class="icon">
          <svg v-if="!isCollapsed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
          <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="15" y1="3" x2="15" y2="21"></line></svg>
        </span>
        <span v-if="!isCollapsed">Menyuni yopish</span>
      </a>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const emit = defineEmits(['toggle'])
const router = useRouter()
const authStore = useAuthStore()
const user = authStore.user

const isCollapsed = ref(false)

function toggleSidebar() {
  isCollapsed.value = !isCollapsed.value
  emit('toggle', isCollapsed.value)
}

function handleLogout() {
  authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  width: 250px;
  height: 100vh;
  background-color: var(--sidebar-bg);
  border-right: 1px solid var(--border-color);
  display: flex;
  flex-direction: column;
  z-index: 1000;
  transition: width 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
}

.sidebar.collapsed {
  width: 80px;
}

.sidebar-header {
  padding: 20px;
  border-bottom: 1px solid var(--border-color);
  height: 70px;
  display: flex;
  align-items: center;
}

.sidebar-header h2 {
  color: var(--primary-color);
  font-size: 20px;
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
}

.user-profile {
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 1px solid var(--border-color);
}

.collapsed-profile {
  padding: 15px;
  justify-content: center;
}

.avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  overflow: hidden;
  flex-shrink: 0;
}

.small-avatar {
  width: 40px;
  height: 40px;
}

.avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.user-info {
  flex: 1;
  overflow: hidden;
}

.user-name {
  font-weight: 600;
  color: var(--text-main);
  margin-bottom: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-email {
  font-size: 11px;
  color: var(--text-muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.sidebar-nav {
  flex: 1;
  padding: 10px 0;
  overflow-y: auto;
  overflow-x: hidden;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 20px;
  color: var(--text-main);
  text-decoration: none;
  transition: all 0.2s;
  white-space: nowrap;
  cursor: pointer;
}

.collapsed .nav-item {
  justify-content: center;
  padding: 12px 0;
}

.nav-item:hover {
  background-color: var(--nav-hover);
}

.nav-item.active {
  background-color: var(--nav-active-bg);
  color: var(--nav-active-text);
  font-weight: 600;
}

.logout-item {
  margin-top: auto;
  color: #f44336;
}

.logout-item:hover {
  background-color: #ffebee;
}

.nav-item .icon {
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 20px;
}

.separator {
  height: 1px;
  background-color: var(--border-color);
  margin: 10px 20px;
}

.collapsed .separator {
  margin: 10px 15px;
}

.sidebar-footer {
  padding: 20px;
  border-top: 1px solid var(--border-color);
}

.collapsed .sidebar-footer {
  padding: 20px 0;
  display: flex;
  justify-content: center;
}

.close-menu {
  display: flex;
  align-items: center;
  gap: 12px;
  color: var(--text-muted);
  text-decoration: none;
  font-size: 14px;
  transition: color 0.2s;
}

.close-menu:hover {
  color: var(--primary-color);
}
</style>

