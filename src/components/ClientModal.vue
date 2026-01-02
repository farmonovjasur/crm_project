<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal-content">
      <h2>{{ isEdit ? 'Mijoz ma\'lumotlarini o\'zgartirish' : 'Mijoz qo\'shish' }}</h2>
      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label>Ismi*</label>
          <input 
            type="text" 
            v-model="form.fullName" 
            required 
            placeholder="Ism kiriting"
          />
        </div>
        <div class="form-group">
          <label>Email*</label>
          <input 
            type="email" 
            v-model="form.email" 
            required 
            placeholder="Email kiriting"
          />
        </div>
        <div class="form-group">
          <label>Ishlash joyi*</label>
          <select v-model="form.company" required class="select-input">
            <option value="">Kompaniya tanlang</option>
            <option v-for="company in companies" :key="company.id" :value="`/api/companies/${company.id}`">
              {{ company.name }}
            </option>
          </select>
        </div>
        <div class="modal-actions">
          <button type="button" @click="$emit('close')" class="btn-cancel">Bekor qilish</button>
          <button type="submit" class="btn-primary">Qo'shish</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import api from '../services/api'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()

const props = defineProps({
  client: {
    type: Object,
    default: null
  },
  isEdit: {
    type: Boolean,
    default: false
  },
  companies: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['close', 'saved'])

const form = ref({
  fullName: '',
  email: '',
  company: ''
})

watch(() => props.client, (newClient) => {
  if (newClient) {
    let companyValue = ''
    if (newClient.company) {
      if (typeof newClient.company === 'string') {
        companyValue = newClient.company
      } else if (newClient.company['@id']) {
        companyValue = newClient.company['@id']
      } else if (newClient.company.id) {
        companyValue = `/api/companies/${newClient.company.id}`
      }
    }
    form.value = {
      fullName: newClient.fullName || '',
      email: newClient.email || '',
      company: companyValue
    }
  } else {
    form.value = {
      fullName: '',
      email: '',
      company: ''
    }
  }
}, { immediate: true })

async function handleSubmit() {
  try {
    const data = {
      fullName: form.value.fullName,
      email: form.value.email,
      company: form.value.company
    }
    
    // Add owner field for new clients (required by backend)
    if (!props.isEdit && authStore.user?.id) {
      data.owner = `/api/users/${authStore.user.id}`
    }
    
    if (props.isEdit) {
      await api.patch(`/clients/${props.client.id}`, data, {
        headers: {
          'Content-Type': 'application/merge-patch+json'
        }
      })
    } else {
      await api.post('/clients', data, {
        headers: {
          'Content-Type': 'application/json'
        }
      })
    }
    emit('saved')
  } catch (error) {
    console.error('Error saving client:', error)
    let errorMessage = 'Saqlashda xatolik yuz berdi'
    
    if (error.response?.data) {
      const data = error.response.data
      if (data.violations && data.violations.length > 0) {
        errorMessage = data.violations[0].message
      } else {
        errorMessage = data['hydra:description'] || data.message || data['hydra:title'] || errorMessage
      }
    }
    
    alert(errorMessage)
  }
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 2000;
}

.modal-content {
  background-color: var(--sidebar-bg);
  padding: 30px;
  border-radius: 10px;
  width: 90%;
  max-width: 500px;
  border: 1px solid var(--border-color);
  transition: background-color 0.3s, border-color 0.3s;
}

.modal-content h2 {
  margin-bottom: 20px;
  color: var(--text-main);
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: var(--text-main);
  font-weight: 500;
}

.form-group input,
.select-input {
  width: 100%;
  padding: 10px;
  border: 1px solid var(--input-border);
  background-color: var(--input-bg);
  color: var(--text-main);
  border-radius: 5px;
  font-size: 14px;
  transition: all 0.3s;
}

.form-group input:focus,
.select-input:focus {
  outline: none;
  border-color: var(--primary-color);
}

.modal-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
  margin-top: 20px;
}

.btn-cancel {
  padding: 10px 20px;
  border: 1px solid var(--border-color);
  background-color: var(--bg-color);
  color: var(--text-main);
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-cancel:hover {
  background-color: var(--nav-hover);
}

.btn-primary {
  padding: 10px 20px;
  background-color: #4A90E2;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 600;
}

.btn-primary:hover {
  background-color: #357ABD;
}
</style>

