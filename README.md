# Webnova

Projet organisé en composants Vue avec un backend PHP séparé. À cette étape, le frontend affiche uniquement une interface de vérification du déploiement ; il n'appelle pas le backend.

## Frontend

```sh
cd frontend
npm install
npm run dev
```

Pour compiler : `npm run build`. Pour consulter le build localement : `npm run preview`.

La page vérifie le chargement Vue/JavaScript, les styles CSS et le fonctionnement d'un bouton interactif. Un message HTML reste visible si les fichiers JavaScript ne se chargent pas.

Sur Hodifly : chemin du paquet `frontend`, type statique, runtime Node 22, build `npm run build`, sortie `dist`. Aucune variable d'environnement nécessaire. Voir [les instructions frontend](frontend/README.md).

## Backend PHP indépendant

Le backend PHP est conservé dans `backend`. Il n'est pas nécessaire de le démarrer ou de le déployer pour tester le frontend.

Pour le lancer indépendamment :

```sh
cd backend
composer install
composer start
```

Sur Hodifly, créer un deuxième projet avec le chemin du paquet `backend`, mode PHP, runtime PHP 8.2 et docroot `public`. Aucune variable obligatoire pour tester le backend seul. Le fichier `backend/hodifly.json` configure son installation Composer.

Tests PHP : `composer test`. Voir [les instructions backend](backend/README.md).
