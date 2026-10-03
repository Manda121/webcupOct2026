<script setup>
import AppHeader from './components/layout/AppHeader.vue'
import ApiTestPanel from './components/tests/ApiTestPanel.vue'
</script>

<template>
  <div class="app-shell">
    <AppHeader />
    <main>
      <section class="intro">
        <span class="eyebrow">Base de développement</span>
        <h1>Un nouveau départ pour Webnova.</h1>
        <p>Une structure en composants Vue, un backend PHP et un espace pour tester leur connexion.</p>
      </section>
      <ApiTestPanel />
    </main>
    <footer>Webnova · En développement</footer>
  </div>
</template>
