<template>
  <div class="modal-overlay" @click.self="$emit('cancel')">
    <div class="modal-content">
      <h2>Rostan ham o'chirilsinmi?</h2>
      <div class="modal-actions">
        <button @click="$emit('confirm')" class="btn-delete">Ha</button>
        <button @click="$emit('cancel')" class="btn-cancel">Yo'q</button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  item: Object,
  itemName: String
})

defineEmits(['confirm', 'cancel'])
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
  max-width: 400px;
  border: 1px solid var(--border-color);
  transition: background-color 0.3s, border-color 0.3s;
}

.modal-content h2 {
  margin-bottom: 20px;
  color: var(--text-main);
  text-align: center;
}

.modal-actions {
  display: flex;
  gap: 10px;
  justify-content: center;
}

.btn-delete {
  padding: 10px 30px;
  background-color: #e74c3c;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 600;
}

.btn-delete:hover {
  background-color: #c0392b;
}

.btn-cancel {
  padding: 10px 30px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 600;
}

.btn-cancel:hover {
  background-color: #45a049;
}
</style>

