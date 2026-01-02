<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal-content">
      <h2>{{ isEdit ? 'Kompaniya o\'zgartirish' : 'Kompaniya qo\'shish' }}</h2>
      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label>Nomi*</label>
          <input 
            type="text" 
            v-model="form.name" 
            required 
            placeholder="Kompaniya nomi"
          />
        </div>
        <div class="form-group">
          <label>Email*</label>
          <input 
            type="email" 
            v-model="form.email" 
            required 
            placeholder="Email"
          />
        </div>
        <div class="form-group">
          <label>Parol*</label>
          <input 
            type="text" 
            v-model="form.password" 
            required 
            placeholder="Parol"
          />
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
  company: {
    type: Object,
    default: null
  },
  isEdit: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'saved'])

const form = ref({
  name: '',
  email: '',
  password: ''
})

watch(() => props.company, (newCompany) => {
  if (newCompany) {
    form.value = {
      name: newCompany.name || '',
      email: newCompany.email || '',
      password: newCompany.password || ''
    }
  } else {
    form.value = {
      name: '',
      email: '',
      password: ''
    }
  }
}, { immediate: true })

async function handleSubmit() {
  try {
    const data = {
      name: form.value.name,
      email: form.value.email,
      password: form.value.password
    }
    
    // Add owner field for new companies (required by backend)
    if (!props.isEdit && authStore.user?.id) {
      data.owner = `/api/users/${authStore.user.id}`
    }
    
    if (props.isEdit) {
      await api.patch(`/companies/${props.company.id}`, data, {
        headers: {
          'Content-Type': 'application/merge-patch+json'
        }
      })
    } else {
      await api.post('/companies', data, {
        headers: {
          'Content-Type': 'application/json'
        }
      })
    }
    emit('saved')
  } catch (error) {
    console.error('Error saving company:', error)
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

.form-group input {
  width: 100%;
  padding: 10px;
  border: 1px solid var(--input-border);
  background-color: var(--input-bg);
  color: var(--text-main);
  border-radius: 5px;
  font-size: 14px;
  transition: all 0.3s;
}

.form-group input:focus {
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

