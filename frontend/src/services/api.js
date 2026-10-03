const API_BASE_URL = (import.meta.env.VITE_API_URL || '/api').replace(/\/$/, '')

export async function fetchJson(path, options = {}) {
  const response = await fetch(`${API_BASE_URL}${path}`, {
    ...options,
    headers: { Accept: 'application/json', ...options.headers },
    signal: options.signal ?? AbortSignal.timeout(10000),
  })
  let data
  try {
    data = await response.json()
  } catch {
    throw new Error('Le serveur n’a pas renvoyé de JSON valide.')
  }
  if (!response.ok) {
    throw new Error(data.error || `Erreur HTTP ${response.status}`)
  }
  return data
}

export const checkHealth = () => fetchJson('/health')
export const sendTestMessage = (message) => fetchJson('/echo', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ message }),
})
