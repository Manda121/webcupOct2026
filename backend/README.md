# Webnova — Backend PHP

Application PHP Composer avec un contrôleur frontal dans `public/index.php`. Cette structure est prise en charge par Hodifly pour les applications PHP personnalisées. Le dépôt est un monorepo : le frontend et le backend ont chacun leur manifeste dans leur propre sous-dossier.

## Créer le projet backend dans Hodifly

Créer un projet distinct de celui du frontend, depuis le même dépôt et sur un domaine ou sous-domaine dédié.

| Champ du formulaire | Valeur |
| --- | --- |
| Chemin du paquet / Package path | `backend` |
| Type / Mode | PHP |
| Runtime | PHP 8.2 |
| Racine web / docroot | `public` |
| Commande de build | Vide |
| Variables obligatoires | Aucune pour le premier déploiement |

Sélectionner **le chemin du paquet `backend` avant de vérifier la détection** : Hodifly trouvera alors `composer.json`, `composer.lock`, `hodifly.json` et le contrôleur frontal `public/index.php` dans ce paquet. Le docroot est `public`, relatif à ce paquet, et non `backend/public`.

`backend/hodifly.json` fixe explicitement le mode PHP, PHP 8.2, l'installation Composer et la racine web. Aucun build JavaScript n'est exécuté pour ce projet. Le chemin du paquet se renseigne dans le formulaire Hodifly ; il ne peut pas être défini dans le manifeste.

Si le projet a déjà été créé avec un autre mode, corriger aussi le mode et le runtime dans le formulaire, ou resélectionner le chemin du paquet : ces deux réglages ne sont pas réappliqués automatiquement à chaque déploiement.

Versionner `composer.lock` et `.htaccess`. Ne pas versionner `vendor/` ni `.env`. Hodifly installe les dépendances Composer, et `.htaccess` transmet les routes à `public/index.php`.

## Vérifier le backend seul

Après déploiement :

1. Ouvrir `https://ton-backend.example/` : réponse JSON identifiant Webnova.
2. Ouvrir `https://ton-backend.example/api/health` : réponse JSON avec `status: "ok"`.

Ces vérifications ne nécessitent aucune connexion au frontend ni variable CORS. Le frontend actuel reste une page de test indépendante, sans appel API.

## Développement local

Depuis `backend` :

```sh
composer install
composer start
```

Le backend est disponible à `http://127.0.0.1:8000`. Vérifier avec `composer test`.

## CORS pour une future connexion au frontend

Lorsque le frontend appellera l'API, définir cette variable dans le projet backend Hodifly :

```text
CORS_ALLOWED_ORIGINS=https://ton-frontend.example
```

Utiliser l'origine exacte du frontend, sans chemin ni slash final. Plusieurs origines peuvent être séparées par des virgules. Hodifly génère le fichier `.env`, que l'application charge avec `phpdotenv`.

En local, `.env.example` peut être copié vers `.env`. Sans configuration explicite, les origines autorisées sont `http://localhost:5173` et `http://127.0.0.1:5173`. CORS autorise l'accès depuis le navigateur ; ce n'est pas une authentification.

`VITE_API_URL` n'est pas utilisée par l'interface frontend actuelle. Elle sera à remettre en place lors de l'intégration de l'API.

## Routes

- `GET /` : identification de l'application.
- `GET /api/health` : état du service.
- `POST /api/echo` : JSON `{"message":"Bonjour Webnova !"}`.
- `OPTIONS` : prérequêtes CORS pour les origines autorisées.

Sources : [applications PHP et monorepos](https://help.hodi.host/fr/article/que-puis-je-deployer-sur-hodifly-1w02yb0/) et [configuration hodifly.json](https://help.hodi.host/fr/article/comment-configurer-un-projet-avec-hodiflyjson-fk3uey/).
