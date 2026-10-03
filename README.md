# Webnova

Base de test avec un frontend Vue 3 organisé en composants et un backend PHP avec Composer. Les anciennes fonctionnalités de gestion de budget ont été retirées.

## Structure

- `frontend/src/components/layout/` : composants de mise en page.
- `frontend/src/components/tests/` : interface des tests API.
- `frontend/src/services/` : appels au backend.
- `frontend/src/assets/` : styles.
- `backend/public/` : point d'entrée HTTP.
- `backend/src/` : logique de l'API.
- `backend/tests/` : tests PHP.

## Démarrer en local

Prérequis : Node.js 20.19+ ou 22.12+, npm et PHP 8.1+.

Dans un premier terminal, depuis la racine :

```sh
cd backend
composer install
composer start
```

Dans un deuxième terminal :

```sh
cd frontend
npm install
npm run dev
```

Ouvrir l'adresse indiquée par Vite. Le proxy Vite transmet `/api` au serveur PHP sur le port 8000. Utiliser « Tester la connexion » et « Envoyer » pour vérifier les échanges.

## Vérifications

Depuis la racine :

```sh
php backend/tests/run.php
```

Puis dans `frontend` :

```sh
npm test
npm run build
```

## API de test

- `GET /api/health` : état du backend et nom du projet.
- `POST /api/echo` : renvoie le message envoyé sous forme de JSON (`{"message":"Bonjour Webnova !"}`). Message requis, maximum 500 caractères.

Les erreurs renvoient du JSON avec un statut 400, 404, 405 ou 422. Aucune base de données n'est nécessaire à cette étape.

Le proxy est disponible avec `npm run dev`. Pour servir le build ou utiliser `npm run preview`, configurer un proxy `/api` vers PHP sur le serveur qui héberge le frontend.

## Backend déployable

Voir [les instructions du backend](backend/README.md) pour Composer, Hodifly et la connexion CORS avec un frontend hébergé séparément.
