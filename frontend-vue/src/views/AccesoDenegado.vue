<template>
    <div class="access-denied">
      <h1>Acceso Denegado</h1>
      <p>Lo sentimos, no tienes permiso para acceder a esta página.</p>
      <router-link to="/">Volver a la página de inicio</router-link>
    </div>
  </template>
  
  <script>
  export default {
    name: 'AccesoDenegado',
  };
  </script>
  
  <style scoped>
  .access-denied {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    text-align: center;
  }
  
  .access-denied h1 {
    font-size: 24px;
    margin-bottom: 20px;
  }
  
  .access-denied p {
    font-size: 16px;
    margin-bottom: 20px;
  }
  
  .access-denied a {
    text-decoration: underline;
    color: #007bff;
    cursor: pointer;
  }
  </style>