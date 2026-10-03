<script setup>
import { ref } from 'vue'
import { checkHealth, sendTestMessage } from '../../services/api.js'

const message = ref('Bonjour Webnova !')
const result = ref(null)
const error = ref('')
const pending = ref(false)

async function runTest(test) {
  pending.value = true
  error.value = ''
  result.value = null
  try {
    result.value = await test()
  } catch (cause) {
    error.value = cause.message
  } finally {
    pending.value = false
  }
}
</script>

<template>
  <section class="test-panel" aria-labelledby="test-title" :aria-busy="pending">
    <div class="panel-heading">
      <div>
        <span class="eyebrow">Premiers tests</span>
        <h2 id="test-title">Connexion au backend</h2>
      </div>
      <span class="badge">API PHP</span>
    </div>
    <p>Vérifiez le serveur, puis envoyez un message pour tester un aller-retour.</p>
    <button class="button secondary" :disabled="pending" @click="runTest(checkHealth)">
      Tester la connexion
    </button>
    <form @submit.prevent="runTest(() => sendTestMessage(message))">
      <label for="message">Message de test</label>
      <div class="input-row">
        <input id="message" v-model="message" required maxlength="500" placeholder="Votre message" />
        <button class="button" :disabled="pending || !message.trim()">Envoyer</button>
      </div>
    </form>
    <div class="test-result" aria-live="polite" aria-atomic="true">
      <p v-if="pending">Test en cours…</p>
      <p v-else-if="error" class="error" role="alert">{{ error }} Vérifiez que le serveur PHP est démarré.</p>
      <template v-else-if="result">
        <p class="success">Test réussi</p>
        <pre>{{ JSON.stringify(result, null, 2) }}</pre>
      </template>
      <p v-else class="muted">Les résultats apparaîtront ici.</p>
    </div>
  </section>
</template>
