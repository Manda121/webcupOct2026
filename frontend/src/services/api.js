export const API_BASE_URL = (import.meta.env?.VITE_API_URL || '/api').replace(/\/$/, '')

export async function fetchJson(path, options = {}) {
  let response
  try {
    response = await fetch(`${API_BASE_URL}${path}`, {
      ...options,
      headers: { Accept: 'application/json', ...options.headers },
      signal: options.signal ?? AbortSignal.timeout(10000),
    })
  } catch (cause) {
    if (cause.name === 'TimeoutError') {
      throw new Error('Le backend ne répond pas dans le délai de 10 secondes.')
    }
    throw new Error('Connexion impossible. Vérifiez l’adresse du backend, sa disponibilité et les origines CORS autorisées.')
  }
  let data
  try {
    data = await response.json()
  } catch {
    throw new Error('Le serveur n’a pas renvoyé de JSON valide. Vérifiez l’adresse de l’API.')
  }
  if (!response.ok) {
    throw new Error(`HTTP ${response.status} : ${data?.error || 'La requête a échoué.'}`)
  }
  return data
}

export async function checkHealth() {
  const data = await fetchJson('/health')
  if (data?.project !== 'Webnova' || data?.status !== 'ok' || data?.backend !== 'PHP') {
    throw new Error('Le serveur répond, mais la réponse ne correspond pas au backend Webnova attendu.')
  }
  return data
}

export async function sendTestMessage(message) {
  const data = await fetchJson('/echo', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ message }),
  })
  if (data?.project !== 'Webnova' || data?.message !== message.trim()) {
    throw new Error('Le message reçu ne correspond pas au message envoyé.')
  }
  return data
}
