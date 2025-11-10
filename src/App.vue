<template>
  <div class="app-wrapper">
    <router-view />
  </div>
</template>

<script setup>
</script>

<style>
.app-wrapper {
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  background-attachment: fixed;
}

/* Asegurar que las páginas de auth estén centradas */
.login-container {
  position: relative;
}
</style>

