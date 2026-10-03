<script setup>
import { computed, onMounted, ref } from 'vue'

const clicks = ref(0)
const stylesLoaded = ref(false)
const address = window.location.href
const buildTime = new Date(__WEBNOVA_BUILD_TIME__).toLocaleString('fr-FR')
const interactionStatus = computed(() => clicks.value > 0 ? 'Test réussi' : 'À tester')

onMounted(() => {
  stylesLoaded.value = getComputedStyle(document.documentElement)
    .getPropertyValue('--webnova-styles').trim() === 'ready'
})
</script>

<template>
  <section class="test-panel" aria-labelledby="test-title">
    <div class="panel-heading">
      <div>
        <span class="eyebrow">Frontend uniquement</span>
        <h2 id="test-title">Vérifier le chargement</h2>
      </div>
      <span class="badge">Webnova</span>
    </div>
    <div class="connection-summary">
      <div class="summary-card">
        <span class="summary-label">JavaScript / Vue</span>
        <strong class="success">Chargé</strong>
        <span class="muted">Les composants sont affichés.</span>
      </div>
      <div class="summary-card">
        <span class="summary-label">Feuille de styles</span>
        <strong :class="stylesLoaded ? 'success' : 'error'">{{ stylesLoaded ? 'Chargée' : 'À vérifier' }}</strong>
        <span class="muted">{{ stylesLoaded ? 'Le fichier CSS est disponible.' : 'Le fichier CSS ne semble pas chargé.' }}</span>
      </div>
      <div class="summary-card">
        <span class="summary-label">Interaction</span>
        <strong :class="{ success: clicks > 0 }">{{ interactionStatus }}</strong>
        <span class="muted">Cliquez sur le bouton ci-dessous.</span>
      </div>
    </div>
    <button class="button" @click="clicks++">Tester l’interface</button>
    <p class="test-result" aria-live="polite" aria-atomic="true">
      {{ clicks > 0 ? `L’interface répond : ${clicks} clic${clicks > 1 ? 's' : ''}.` : 'Le compteur confirmera que les interactions Vue fonctionnent.' }}
    </p>
    <div class="api-address">
      <span class="summary-label">Adresse de cette page</span>
      <code>{{ address }}</code>
    </div>
    <p class="muted">Version compilée le {{ buildTime }}.</p>
  </section>
</template>
