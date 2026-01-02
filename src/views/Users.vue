<template>
  <div class="users-page">
    <div class="page-header">
      <h1>Foydalanuvchilar</h1>
      <button @click="openAddModal" class="btn-primary">Foydalanuvchi qo'shish</button>
    </div>

    <div v-if="successMessage" class="success-message">
      {{ successMessage }}
    </div>

    <div class="filters">
      <input 
        type="text" 
        v-model="searchName" 
        placeholder="Foydalanuvchi qidirish"
        class="search-input"
      />
      <input 
        type="text" 
        v-model="searchEmail" 
        placeholder="Email qidirish"
        class="search-input"
      />
      <input 
        type="text" 
        v-model="searchActivity" 
        placeholder="So'nggi faollikni qidirish"
        class="search-input"
      />
    </div>

    <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>Ism</th>
            <th>Email</th>
            <th>Parol</th>
            <th>So'nggi faollik</th>
            <th>Amallar</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in filteredUsers" :key="user.id">
            <td>
              <div class="user-cell">
                <img :src="getAvatarUrl(user.fullName)" :alt="user.fullName" class="avatar-small" />
                <span>{{ user.fullName }}</span>
              </div>
            </td>
            <td>{{ user.email }}</td>
            <td>••••••••</td>
            <td>{{ formatDate(user.lastLoginAt || user.createdAt) }}</td>
            <td>
              <button @click="openEditModal(user)" class="btn-edit">✏️</button>
              <button @click="openDeleteModal(user)" class="btn-delete">🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <UserModal 
      v-if="showModal"
      :user="selectedUser"
      :isEdit="isEdit"
      @close="closeModal"
      @saved="handleSaved"
    />

    <DeleteModal
      v-if="showDeleteModal"
      :item="selectedUser"
      itemName="foydalanuvchi"
      @confirm="handleDelete"
      @cancel="closeDeleteModal"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../services/api'
import UserModal from '../components/UserModal.vue'
import DeleteModal from '../components/DeleteModal.vue'

const users = ref([])
const searchName = ref('')
const searchEmail = ref('')
const searchActivity = ref('')
const showModal = ref(false)
const showDeleteModal = ref(false)
const selectedUser = ref(null)
const isEdit = ref(false)
const successMessage = ref('')

const filteredUsers = computed(() => {
  return users.value.filter(user => {
    const nameMatch = !searchName.value || user.fullName?.toLowerCase().includes(searchName.value.toLowerCase())
    const emailMatch = !searchEmail.value || user.email?.toLowerCase().includes(searchEmail.value.toLowerCase())
    const activityMatch = !searchActivity.value || getDateSearchText(user.lastLoginAt || user.createdAt).includes(searchActivity.value.toLowerCase())
    return nameMatch && emailMatch && activityMatch
  })
})

function getAvatarUrl(name) {
  return `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=4A90E2&color=fff`
}

function formatDate(date) {
  if (!date) return '-'
  const d = new Date(date)
  const now = new Date()
  const diff = now - d
  const minutes = Math.floor(diff / 60000)
  const hours = Math.floor(diff / 3600000)
  const days = Math.floor(diff / 86400000)
  
  if (minutes < 60) return `${minutes} minut oldin`
  if (hours < 24) return `${hours} soat oldin`
  if (days < 30) return `${days} kun oldin`
  return d.toLocaleDateString('uz-UZ', { year: 'numeric', month: 'short', day: 'numeric' })
}

function getDateSearchText(date) {
  if (!date) return ''
  const dateObj = new Date(date)
  const isoString = Number.isNaN(dateObj.getTime()) ? '' : dateObj.toISOString()
  return [
    formatDate(date),
    dateObj.toLocaleDateString('uz-UZ', { year: 'numeric', month: 'short', day: 'numeric' }),
    isoString
  ].join(' ').toLowerCase()
}

async function loadUsers() {
  try {
    const response = await api.get('/users')
    // API Platform javobini to'g'ri parse qilish
    if (Array.isArray(response.data)) {
      users.value = response.data
    } else if (response.data && response.data['hydra:member']) {
      users.value = response.data['hydra:member']
    } else if (response.data && Array.isArray(response.data)) {
      users.value = response.data
    } else {
      users.value = []
    }
  } catch (error) {
    console.error('Error loading users:', error)
    users.value = []
  }
}

function openAddModal() {
  selectedUser.value = null
  isEdit.value = false
  showModal.value = true
}

function openEditModal(user) {
  selectedUser.value = { ...user }
  isEdit.value = true
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  selectedUser.value = null
}

function openDeleteModal(user) {
  selectedUser.value = user
  showDeleteModal.value = true
}

function closeDeleteModal() {
  showDeleteModal.value = false
  selectedUser.value = null
}

async function handleDelete() {
  try {
    await api.delete(`/users/${selectedUser.value.id}`)
    successMessage.value = 'Ma\'lumotlar muvaffaqiyatli o\'chirildi'
    setTimeout(() => successMessage.value = '', 3000)
    await loadUsers()
    closeDeleteModal()
  } catch (error) {
    console.error('Error deleting user:', error)
    alert('O\'chirishda xatolik yuz berdi')
  }
}

function handleSaved() {
  const message = isEdit.value 
    ? 'Ma\'lumotlar muvaffaqiyatli o\'zgartirildi' 
    : 'Ma\'lumotlar muvaffaqiyatli qo\'shildi'
  
  closeModal()
  loadUsers()
  successMessage.value = message
  setTimeout(() => successMessage.value = '', 3000)
}

onMounted(() => {
  loadUsers()
})
</script>

<style scoped>
.users-page {
  background-color: var(--card-bg);
  border-radius: 8px;
  padding: 20px;
  border: 1px solid var(--border-color);
  transition: background-color 0.3s, border-color 0.3s;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.page-header h1 {
  font-size: 24px;
  color: var(--text-main);
}

.btn-primary {
  background-color: #4A90E2;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 600;
}

.btn-primary:hover {
  background-color: #357ABD;
}

.success-message {
  background-color: #4CAF50;
  color: white;
  padding: 12px 20px;
  border-radius: 5px;
  margin-bottom: 20px;
}

.filters {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
  margin-bottom: 20px;
}

.search-input {
  padding: 10px;
  border: 1px solid var(--input-border);
  background-color: var(--input-bg);
  color: var(--text-main);
  border-radius: 5px;
  font-size: 14px;
  transition: all 0.3s;
}

.search-input:focus {
  outline: none;
  border-color: var(--primary-color);
}

.table-container {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table thead {
  background-color: var(--table-header-bg);
}

.data-table th,
.data-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid var(--border-color);
  color: var(--text-main);
}

.data-table tbody tr:hover {
  background-color: var(--table-hover-bg);
}

.user-cell {
  display: flex;
  align-items: center;
  gap: 10px;
}

.avatar-small {
  width: 40px;
  height: 40px;
  border-radius: 50%;
}

.btn-edit,
.btn-delete {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 18px;
  padding: 5px 10px;
}

.btn-edit:hover {
  opacity: 0.7;
}

.btn-delete:hover {
  opacity: 0.7;
}
</style>
