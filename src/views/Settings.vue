<template>
  <div class="settings-page">
    <div class="header">
      <h1>Sozlamalar</h1>
    </div>

    <div class="settings-container">
      <div class="settings-section">
        <h3>Tashqi ko'rinish</h3>
        <p class="section-desc">Tizim interfeysi mavzusini tanlang.</p>
        
        <div class="theme-options">
          <div 
            class="theme-card" 
            :class="{ active: themeStore.currentTheme === 'light' }"
            @click="themeStore.setTheme('light')"
          >
            <div class="theme-preview light-preview">
              <div class="preview-sidebar"></div>
              <div class="preview-content">
                <div class="preview-line"></div>
                <div class="preview-line"></div>
              </div>
            </div>
            <span>Kunduzgi</span>
          </div>

          <div 
            class="theme-card" 
            :class="{ active: themeStore.currentTheme === 'dark' }"
            @click="themeStore.setTheme('dark')"
          >
            <div class="theme-preview dark-preview">
              <div class="preview-sidebar"></div>
              <div class="preview-content">
                <div class="preview-line"></div>
                <div class="preview-line"></div>
              </div>
            </div>
            <span>Tungi</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useThemeStore } from '../stores/theme'

const themeStore = useThemeStore()
</script>

<style scoped>
.settings-page {
  max-width: 800px;
}

.header {
  margin-bottom: 30px;
}

.header h1 {
  font-size: 24px;
  color: var(--text-main);
}

.settings-container {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.settings-section {
  background-color: var(--sidebar-bg);
  padding: 24px;
  border-radius: 12px;
  border: 1px solid var(--border-color);
  transition: background-color 0.3s, border-color 0.3s;
}

.settings-section h3 {
  margin-bottom: 8px;
  color: var(--text-main);
}

.section-desc {
  color: var(--text-muted);
  font-size: 14px;
  margin-bottom: 24px;
}

.theme-options {
  display: flex;
  gap: 20px;
}

.theme-card {
  flex: 1;
  max-width: 200px;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  border-radius: 8px;
  padding: 12px;
  border: 2px solid transparent;
  transition: all 0.2s;
}

.theme-card:hover {
  background-color: var(--nav-hover);
}

.theme-card.active {
  border-color: var(--primary-color);
  background-color: var(--nav-hover);
}

.theme-card span {
  font-weight: 500;
  color: var(--text-main);
}

.theme-preview {
  width: 100%;
  height: 100px;
  border-radius: 6px;
  border: 1px solid var(--border-color);
  display: flex;
  overflow: hidden;
}

.preview-sidebar {
  width: 30%;
  height: 100%;
}

.preview-content {
  flex: 1;
  padding: 10px;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.preview-line {
  height: 8px;
  border-radius: 4px;
  width: 100%;
}

/* Light Preview Styles */
.light-preview .preview-sidebar { background-color: #ffffff; border-right: 1px solid #e0e0e0; }
.light-preview .preview-content { background-color: #f5f5f5; }
.light-preview .preview-line { background-color: #e0e0e0; }

/* Dark Preview Styles */
.dark-preview .preview-sidebar { background-color: #1e1e1e; border-right: 1px solid #333; }
.dark-preview .preview-content { background-color: #121212; }
.dark-preview .preview-line { background-color: #333; }
</style>
