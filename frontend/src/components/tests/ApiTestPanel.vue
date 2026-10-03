<script setup>
import { computed, onMounted, ref } from 'vue'
import ConnectionStatus from './ConnectionStatus.vue'
import { API_BASE_URL, checkHealth, sendTestMessage } from '../../services/api.js'

const message = ref('Bonjour Webnova !')
const result = ref(null)
const error = ref('')
const status = ref('idle')
const duration = ref(null)
const checkedAt = ref('')
const lastTest = ref('')
const pending = computed(() => status.value === 'pending')
const apiAddress = new URL(API_BASE_URL, window.location.origin).href

async function runTest(name, test) {
  if (pending.value) return
  status.value = 'pending'
  error.value = ''
  result.value = null
  duration.value = null
  lastTest.value = name
  const start = performance.now()
  try {
    result.value = await test()
    status.value = 'connected'
  } catch (cause) {
    error.value = cause.message
    status.value = 'error'
  } finally {
    duration.value = Math.round(performance.now() - start)
    checkedAt.value = new Date().toLocaleTimeString('fr-FR')
  }
}

async function fullTest() {
  const sent = message.value
  const health = await checkHealth()
  const echo = await sendTestMessage(sent)
  return { connexion: health, messageEnvoye: sent, reponse: echo }
}

onMounted(() => runTest('Connexion · GET /health', checkHealth))
</script>

<template>
  <section class="test-panel" aria-labelledby="test-title" :aria-busy="pending">
    <div class="panel-heading">
      <div>
        <span class="eyebrow">Tests frontend–backend</span>
        <h2 id="test-title">Connexion à Webnova</h2>
      </div>
      <span class="badge">API PHP</span>
    </div>
    <p>La connexion est vérifiée à l’ouverture de cette page. Envoyez un message pour vérifier aussi l’aller-retour avec le backend.</p>
    <ConnectionStatus :status="status" :duration="duration" :checked-at="checkedAt" />
    <div class="api-address">
      <span class="summary-label">Adresse de l’API utilisée</span>
      <code>{{ apiAddress }}</code>
    </div>
    <div class="test-actions">
      <button class="button secondary" :disabled="pending" @click="runTest('Connexion · GET /health', checkHealth)">
        Tester la connexion
      </button>
      <button class="button" :disabled="pending || !message.trim()" @click="runTest('Test complet · GET + POST', fullTest)">
        Lancer le test complet
      </button>
    </div>
    <form @submit.prevent="runTest('Message · POST /echo', () => sendTestMessage(message))">
      <label for="message">Message de test</label>
      <div class="input-row">
        <input id="message" v-model="message" :disabled="pending" required maxlength="500" placeholder="Votre message" />
        <button class="button" :disabled="pending || !message.trim()">Envoyer le message</button>
      </div>
    </form>
    <div class="test-result" aria-live="polite" aria-atomic="true">
      <span v-if="lastTest" class="summary-label">{{ lastTest }}</span>
      <p v-if="pending">Test en cours…</p>
      <p v-else-if="error" class="error" role="alert">{{ error }}</p>
      <template v-else-if="result">
        <p class="success">Test réussi · Réponse Webnova vérifiée</p>
        <pre>{{ JSON.stringify(result, null, 2) }}</pre>
      </template>
      <p v-else class="muted">Les résultats apparaîtront ici.</p>
    </div>
  </section>
</template>
