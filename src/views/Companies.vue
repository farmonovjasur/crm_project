<template>
  <div class="companies-page">
    <div class="page-header">
      <h1>Kompaniyalar</h1>
      <button @click="openAddModal" class="btn-primary">Kompaniya qo'shish</button>
    </div>

    <div v-if="successMessage" class="success-message">
      {{ successMessage }}
    </div>

    <div class="filters">
      <input 
        type="text" 
        v-model="searchName" 
        placeholder="Kompaniya qidirish"
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
        v-model="searchPassword" 
        placeholder="Parol qidirish"
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
            <th>Kompaniya nomi</th>
            <th>Email</th>
            <th>Parol</th>
            <th>So'nggi faollik</th>
            <th>Amallar</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="company in filteredCompanies" :key="company.id">
            <td>
              <div class="user-cell">
                <img :src="getAvatarUrl(company.name)" :alt="company.name" class="avatar-small" />
                <span>{{ company.name }}</span>
              </div>
            </td>
            <td>{{ company.email || '-' }}</td>
            <td>{{ company.password || '••••••••' }}</td>
            <td>{{ formatDate(company.createdAt) }}</td>
            <td>
              <button @click="openEditModal(company)" class="btn-edit">✏️</button>
              <button @click="openDeleteModal(company)" class="btn-delete">🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <CompanyModal 
      v-if="showModal"
      :company="selectedCompany"
      :isEdit="isEdit"
      @close="closeModal"
      @saved="handleSaved"
    />

    <DeleteModal
      v-if="showDeleteModal"
      :item="selectedCompany"
      itemName="kompaniya"
      @confirm="handleDelete"
      @cancel="closeDeleteModal"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../services/api'
import CompanyModal from '../components/CompanyModal.vue'
import DeleteModal from '../components/DeleteModal.vue'

const companies = ref([])
const searchName = ref('')
const searchEmail = ref('')
const searchPassword = ref('')
const searchActivity = ref('')
const showModal = ref(false)
const showDeleteModal = ref(false)
const selectedCompany = ref(null)
const isEdit = ref(false)
const successMessage = ref('')

const filteredCompanies = computed(() => {
  return companies.value.filter(company => {
    const nameMatch = !searchName.value || company.name?.toLowerCase().includes(searchName.value.toLowerCase())
    const emailMatch = !searchEmail.value || (company.email && company.email.toLowerCase().includes(searchEmail.value.toLowerCase()))
    const passwordMatch = !searchPassword.value || (company.password && company.password.includes(searchPassword.value))
    const activityMatch = !searchActivity.value || getDateSearchText(company.createdAt).includes(searchActivity.value.toLowerCase())
    return nameMatch && emailMatch && passwordMatch && activityMatch
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
  selectedCompany.value = null
  isEdit.value = false
  showModal.value = true
}

function openEditModal(company) {
  selectedCompany.value = { ...company }
  isEdit.value = true
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  selectedCompany.value = null
}

function openDeleteModal(company) {
  selectedCompany.value = company
  showDeleteModal.value = true
}

function closeDeleteModal() {
  showDeleteModal.value = false
  selectedCompany.value = null
}

async function handleDelete() {
  try {
    await api.delete(`/companies/${selectedCompany.value.id}`)
    successMessage.value = 'Ma\'lumotlar muvaffaqiyatli o\'chirildi'
    setTimeout(() => successMessage.value = '', 3000)
    await loadCompanies()
    closeDeleteModal()
  } catch (error) {
    console.error('Error deleting company:', error)
    alert('O\'chirishda xatolik yuz berdi')
  }
}

function handleSaved() {
  const message = isEdit.value 
    ? 'Ma\'lumotlar muvaffaqiyatli o\'zgartirildi' 
    : 'Ma\'lumotlar muvaffaqiyatli qo\'shildi'

  closeModal()
  loadCompanies()
  successMessage.value = message
  setTimeout(() => successMessage.value = '', 3000)
}

onMounted(() => {
  loadCompanies()
})
</script>

<style scoped>
.companies-page {
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
