<script setup>
defineProps({
  status: { type: String, required: true },
  duration: { type: Number, default: null },
  checkedAt: { type: String, default: '' },
})

const labels = {
  idle: 'À vérifier',
  pending: 'Vérification…',
  connected: 'Connexion établie',
  error: 'Échec du test',
}
</script>

<template>
  <div class="connection-summary" aria-live="polite">
    <div class="summary-card">
      <span class="summary-label">Frontend Vue</span>
      <strong class="success">Prêt</strong>
      <span class="muted">Interface chargée</span>
    </div>
    <div class="summary-card">
      <span class="summary-label">Backend PHP</span>
      <strong :class="{ success: status === 'connected', error: status === 'error' }">{{ labels[status] }}</strong>
      <span class="muted">{{ checkedAt ? `Dernier test à ${checkedAt}` : 'Aucun résultat pour le moment' }}</span>
    </div>
    <div class="summary-card">
      <span class="summary-label">Durée du dernier test</span>
      <strong>{{ duration === null ? '—' : `${duration} ms` }}</strong>
      <span class="muted">Mesurée depuis le navigateur</span>
    </div>
  </div>
</template>
