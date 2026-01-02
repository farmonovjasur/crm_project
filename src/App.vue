<template>
  <router-view />
</template>

<script setup>
</script>

<style>
:root {
  --bg-color: #f5f5f5;
  --sidebar-bg: #ffffff;
  --card-bg: #ffffff;
  --text-main: #333333;
  --text-muted: #666666;
  --border-color: #e0e0e0;
  --primary-color: #4A90E2;
  --nav-hover: #f5f5f5;
  --nav-active-bg: #E3F2FD;
  --nav-active-text: #4A90E2;
  --table-header-bg: #f5f5f5;
  --table-hover-bg: #f9f9f9;
  --input-bg: #ffffff;
  --input-border: #dddddd;
}

[data-theme='dark'] {
  --bg-color: #121212;
  --sidebar-bg: #1e1e1e;
  --card-bg: #1e1e1e;
  --text-main: #e0e0e0;
  --text-muted: #aaaaaa;
  --border-color: #333333;
  --primary-color: #64B5F6;
  --nav-hover: #2c2c2c;
  --nav-active-bg: #0D47A1;
  --nav-active-text: #ffffff;
  --table-header-bg: #252525;
  --table-hover-bg: #2c2c2c;
  --input-bg: #2c2c2c;
  --input-border: #444444;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  background-color: var(--bg-color);
  color: var(--text-main);
  transition: background-color 0.3s, color 0.3s;
}

#app {
  min-height: 100vh;
}
</style>

