<template>
  <div class="clients-page">
    <div class="page-header">
      <h1>Mijozlar</h1>
      <button @click="openAddModal" class="btn-primary">Mijoz qo'shish</button>
    </div>

    <div v-if="successMessage" class="success-message">
      {{ successMessage }}
    </div>

    <div class="filters">
      <input 
        type="text" 
        v-model="searchName" 
        placeholder="Mijozlar qidirish"
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
        v-model="searchWorkplace" 
        placeholder="Ishlash joyini qidirish"
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
            <th>Ishlash joyi</th>
            <th>So'nggi faollik</th>
            <th>Amallar</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="client in filteredClients" :key="client.id">
            <td>
              <div class="user-cell">
                <img :src="getAvatarUrl(client.fullName)" :alt="client.fullName" class="avatar-small" />
                <span>{{ client.fullName }}</span>
              </div>
            </td>
            <td>{{ client.email || '-' }}</td>
            <td>••••••••</td>
            <td>{{ getCompanyName(client.company) || client.workplace || '-' }}</td>
            <td>{{ formatDate(client.createdAt) }}</td>
            <td>
              <button @click="openEditModal(client)" class="btn-edit">✏️</button>
              <button @click="openDeleteModal(client)" class="btn-delete">🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <ClientModal 
      v-if="showModal"
      :client="selectedClient"
      :isEdit="isEdit"
      :companies="companies"
      @close="closeModal"
      @saved="handleSaved"
    />

    <DeleteModal
      v-if="showDeleteModal"
      :item="selectedClient"
      itemName="mijoz"
      @confirm="handleDelete"
      @cancel="closeDeleteModal"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../services/api'
import ClientModal from '../components/ClientModal.vue'
import DeleteModal from '../components/DeleteModal.vue'

const clients = ref([])
const companies = ref([])
const searchName = ref('')
const searchEmail = ref('')
const searchWorkplace = ref('')
const searchActivity = ref('')
const showModal = ref(false)
const showDeleteModal = ref(false)
const selectedClient = ref(null)
const isEdit = ref(false)
const successMessage = ref('')

const filteredClients = computed(() => {
  return clients.value.filter(client => {
    const nameMatch = !searchName.value || client.fullName?.toLowerCase().includes(searchName.value.toLowerCase())
    const emailMatch = !searchEmail.value || (client.email && client.email.toLowerCase().includes(searchEmail.value.toLowerCase()))
    const workplaceMatch = !searchWorkplace.value || (client.workplace && client.workplace.toLowerCase().includes(searchWorkplace.value.toLowerCase()))
    const activityMatch = !searchActivity.value || getDateSearchText(client.createdAt).includes(searchActivity.value.toLowerCase())
    return nameMatch && emailMatch && workplaceMatch && activityMatch
  })
})

function getAvatarUrl(name) {
  return `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=4A90E2&color=fff`
}

function getCompanyName(company) {
  if (!company) return null
  if (typeof company === 'string') {
    const companyId = company.split('/').pop()
    const found = companies.value.find(c => c.id === parseInt(companyId))
    return found?.name || null
  }
  return company.name || null
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

async function loadClients() {
  try {
    const response = await api.get('/clients')
    // API Platform javobini to'g'ri parse qilish
    if (Array.isArray(response.data)) {
      clients.value = response.data
    } else if (response.data && response.data['hydra:member']) {
      clients.value = response.data['hydra:member']
    } else if (response.data && Array.isArray(response.data)) {
      clients.value = response.data
    } else {
      clients.value = []
    }
  } catch (error) {
    console.error('Error loading clients:', error)
    clients.value = []
  }
}

async function loadCompanies() {
  try {
    const response = await api.get('/companies')
    // API Platform javobini to'g'ri parse qilish
    if (Array.isArray(response.data)) {
      companies.value = response.data
    } else if (response.data && response.data['hydra:member']) {
      companies.value = response.data['hydra:member']
    } else if (response.data && Array.isArray(response.data)) {
      companies.value = response.data
    } else {
      companies.value = []
    }
  } catch (error) {
    console.error('Error loading companies:', error)
    companies.value = []
  }
}

function openAddModal() {
  selectedClient.value = null
  isEdit.value = false
  showModal.value = true
}

function openEditModal(client) {
  selectedClient.value = { ...client }
  isEdit.value = true
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  selectedClient.value = null
}

function openDeleteModal(client) {
  selectedClient.value = client
  showDeleteModal.value = true
}

function closeDeleteModal() {
  showDeleteModal.value = false
  selectedClient.value = null
}

async function handleDelete() {
  try {
    await api.delete(`/clients/${selectedClient.value.id}`)
    successMessage.value = 'Ma\'lumotlar muvaffaqiyatli o\'chirildi'
    setTimeout(() => successMessage.value = '', 3000)
    await loadClients()
    closeDeleteModal()
  } catch (error) {
    console.error('Error deleting client:', error)
    alert('O\'chirishda xatolik yuz berdi')
  }
}

function handleSaved() {
  const message = isEdit.value 
    ? 'Ma\'lumotlar muvaffaqiyatli o\'zgartirildi' 
    : 'Ma\'lumotlar muvaffaqiyatli qo\'shildi'

  closeModal()
  loadClients()
  successMessage.value = message
  setTimeout(() => successMessage.value = '', 3000)
}

onMounted(() => {
  loadClients()
  loadCompanies()
})
</script>

<style scoped>
.clients-page {
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
  grid-template-columns: repeat(4, 1fr);
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
