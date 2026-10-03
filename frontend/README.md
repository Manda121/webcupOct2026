# Webnova — Frontend

Page Vue indépendante pour vérifier le déploiement du frontend. Elle affiche le chargement JavaScript/Vue, la présence des styles, un compteur interactif et la date de compilation. Aucun appel au backend et aucune variable d'environnement ne sont nécessaires.

## Développement et compilation

```sh
npm install
npm run dev
npm run build
npm run preview
```

## Hodifly

| Réglage | Valeur |
| --- | --- |
| Chemin du paquet | `frontend` |
| Type | Statique |
| Runtime | Node 22 |
| Commande de build | `npm run build` |
| Dossier de sortie | `dist` |
| Variables d'environnement | Aucune |

Le fichier `hodifly.json` configure le build et la sortie à la racine du paquet. Le chemin du paquet reste à sélectionner dans Hodifly. Pour un projet existant, vérifier aussi son type et son runtime dans le formulaire.

Publier tout le contenu de `dist`, y compris `dist/assets`, lors du même déploiement. Vite génère des adresses relatives pour les fichiers JS et CSS, adaptées à la racine du domaine ou à un sous-dossier.

## Diagnostic des erreurs 404

Si le texte « La page HTML est chargée » reste affiché, Vue n'a pas démarré. Vérifier la présence des fichiers JS et CSS indiqués dans le nouvel `index.html` dans `dist/assets`. Redéployer l'ensemble du build, puis recharger la page sans cache pour éviter un ancien HTML référençant des fichiers supprimés.

La présence du HTML seul ne confirme pas le fonctionnement de Vue. Le bouton « Tester l'interface » et les indicateurs permettent de vérifier le frontend complet.
