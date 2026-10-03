<script setup>
import AppHeader from './components/layout/AppHeader.vue'
import FrontendTestPanel from './components/tests/FrontendTestPanel.vue'
</script>

<template>
  <div class="app-shell">
    <AppHeader />
    <main>
      <section class="intro">
        <span class="eyebrow">Vérification du déploiement</span>
        <h1>Webnova est en ligne.</h1>
        <p>Cette page permet de vérifier le chargement et le fonctionnement du frontend.</p>
      </section>
      <FrontendTestPanel />
    </main>
    <footer>Webnova · Frontend de test</footer>
  </div>
</template>
