import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../services/api'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('token') || null)
  const user = ref(JSON.parse(localStorage.getItem('user') || 'null'))

  const isAuthenticated = computed(() => !!token.value)

  async function login(email, password) {
    try {
      const response = await api.post('/login', { email, password }, {
        headers: {
          'Content-Type': 'application/json'
        }
      })
      token.value = response.data.token
      // Fetch user data after login
      try {
        const userResponse = await api.get('/users', {
          params: { 'email': email }
        })
        let users = []
        if (Array.isArray(userResponse.data)) {
          users = userResponse.data
        } else if (userResponse.data && userResponse.data['hydra:member']) {
          users = userResponse.data['hydra:member']
        }
        if (users.length > 0) {
          user.value = users[0]
        } else {
          user.value = { email, fullName: email }
        }
      } catch (e) {
        user.value = { email, fullName: email }
      }
      localStorage.setItem('token', token.value)
      localStorage.setItem('user', JSON.stringify(user.value))
      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
      return { success: true }
    } catch (error) {
      return { 
        success: false, 
        message: error.response?.data?.message || error.response?.data?.['hydra:description'] || 'Login xatosi' 
      }
    }
  }

  function logout() {
    token.value = null
    user.value = null
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    delete api.defaults.headers.common['Authorization']
  }

  if (token.value) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  return {
    token,
    user,
    isAuthenticated,
    login,
    logout
  }
})

