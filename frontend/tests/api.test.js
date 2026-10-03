import assert from 'node:assert/strict'
import { afterEach, mock, test } from 'node:test'
import { checkHealth, sendTestMessage } from '../src/services/api.js'

afterEach(() => mock.restoreAll())

const respond = (data, status = 200) => mock.method(globalThis, 'fetch', async () =>
  new Response(JSON.stringify(data), { status, headers: { 'Content-Type': 'application/json' } }))

test('connexion : identité et état du backend vérifiés', async () => {
  const data = { project: 'Webnova', status: 'ok', backend: 'PHP' }
  const fetchMock = respond(data)
  assert.deepEqual(await checkHealth(), data)
  assert.equal(fetchMock.mock.calls[0].arguments[0], '/api/health')
})

test('une réponse JSON provenant d’un autre serveur est refusée', async () => {
  respond({ project: 'Autre', status: 'ok', backend: 'PHP' })
  await assert.rejects(checkHealth(), /ne correspond pas au backend Webnova/)
})

test('message : JSON envoyé et réponse vérifiée', async () => {
  const fetchMock = respond({ project: 'Webnova', message: 'Bonjour !' })
  await sendTestMessage(' Bonjour ! ')
  const [url, options] = fetchMock.mock.calls[0].arguments
  assert.equal(url, '/api/echo')
  assert.equal(options.method, 'POST')
  assert.equal(options.headers['Content-Type'], 'application/json')
  assert.deepEqual(JSON.parse(options.body), { message: ' Bonjour ! ' })
})

test('un message altéré par le serveur est refusé', async () => {
  respond({ project: 'Webnova', message: 'Différent' })
  await assert.rejects(sendTestMessage('Bonjour !'), /ne correspond pas au message envoyé/)
})

test('les erreurs HTTP affichent le statut et le détail du backend', async () => {
  respond({ error: 'Un message non vide est requis.' }, 422)
  await assert.rejects(sendTestMessage(''), /HTTP 422 : Un message non vide/)
})

test('une page HTML à la place de l’API est signalée', async () => {
  mock.method(globalThis, 'fetch', async () => new Response('<html>Accueil</html>'))
  await assert.rejects(checkHealth(), /JSON valide/)
})

test('une panne réseau indique les vérifications de connexion et CORS', async () => {
  mock.method(globalThis, 'fetch', async () => { throw new TypeError('Failed to fetch') })
  await assert.rejects(checkHealth(), /disponibilité et les origines CORS/)
})

test('le délai dépassé est signalé', async () => {
  mock.method(globalThis, 'fetch', async () => { throw new DOMException('Timeout', 'TimeoutError') })
  await assert.rejects(checkHealth(), /délai de 10 secondes/)
})
