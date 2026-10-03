# Webnova — Backend PHP

Application PHP avec un point d'entrée HTTP, Composer et une configuration Hodifly. Pas de base de données à cette étape. `phpdotenv` charge les variables du fichier `.env` généré par Hodifly.

## Développement

Depuis `backend` :

```sh
composer install
composer start
```

Le backend est disponible à `http://127.0.0.1:8000`. Tests : `composer test`.

Pour personnaliser les variables en local, copier `.env.example` vers `.env`. Ne pas versionner `.env` ni `vendor/`.

## Déploiement Hodifly

Créer un projet backend distinct du frontend, à partir du même dépôt :

| Réglage | Valeur |
| --- | --- |
| Chemin du paquet / Package path | `backend` |
| Type | PHP |
| Runtime | PHP 8.2 |
| Racine web / docroot | `public` |
| Commande de build | Vide |

`hodifly.json` configure le mode PHP, le runtime et le docroot à la racine du paquet. Si un projet existe déjà avec un autre type, modifier également le type/runtime dans le formulaire Hodifly ou resélectionner son chemin de paquet. Le chemin `backend` doit toujours être renseigné dans le formulaire.

Composer est exécuté par Hodifly à chaque déploiement ; conserver `composer.lock` dans Git. Apache sert `public/index.php`, et `.htaccess` transmet les routes de l'API à ce point d'entrée. Aucun serveur Node ni Docker n'est nécessaire.

Ajouter cette variable au projet backend :

```text
CORS_ALLOWED_ORIGINS=https://ton-frontend.example
```

Remplacer par l'origine exacte du frontend (protocole, domaine, port éventuel), sans chemin ni slash final. Plusieurs origines peuvent être séparées par des virgules. Hodifly génère le `.env`, chargé par l'application. Le manifeste exige cette variable pour éviter un déploiement sans configuration CORS.

Côté projet frontend, définir avant compilation :

```text
VITE_API_URL=https://ton-backend.example/api
```

Puis redéployer le frontend. Le backend doit avoir son propre domaine ou sous-domaine. La page d'accueil affiche l'adresse de l'API réellement utilisée et permet de vérifier la connexion.

## Vérifier après déploiement

1. Ouvrir `https://ton-backend.example/` : identification JSON de Webnova.
2. Ouvrir `https://ton-backend.example/api/health` : statut `ok`.
3. Ouvrir le frontend : contrôle automatique de la connexion, puis test complet GET + POST.

## Routes

- `GET /` : identification de l'application.
- `GET /api/health` : état du backend.
- `POST /api/echo` : JSON `{"message":"Bonjour Webnova !"}`.
- `OPTIONS` : prérequêtes CORS pour les origines autorisées.

CORS autorise l'accès depuis le navigateur ; ce n'est pas une authentification.

Documentation officielle : [configuration Hodifly](https://help.hodi.host/fr/article/comment-configurer-un-projet-avec-hodiflyjson-fk3uey/) et [applications PHP / monorepos](https://help.hodi.host/fr/article/que-puis-je-deployer-sur-hodifly-1w02yb0/).
