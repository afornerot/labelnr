# LabelNR

Application de gestion de labels NR (Numérique Responsable) pour l'audit RSE.

## Fonctionnalités

- Gestion des thématiques, axes prioritaires (PA) et actions TIR
- Évaluation de la maturité selon plusieurs niveaux
- Gestion documentaire (DMR) par action
- Génération de rapports et exports
- Système de partage par token pour accès lecture seule

## Prérequis

- Docker et Docker Compose

L'application s'exécute sous **PHP 8.5** (image `reg.cadoles.com/envole/nineapache:8.5`).

## Installation avec Docker

```bash
# Cloner le projet
git clone https://github.com/afornerot/labelnr.git
cd labelnr

# Créer le fichier .env.local (voir section Configuration)
cp .env .env.local

# Démarrer les containers (l'entrypoint initialise automatiquement la BDD)
docker-compose up -d
```

### Permissions sur les répertoires d'upload

Les répertoires `uploads/` et `public/uploads/` doivent être accessibles en écriture par le serveur web.
L'image `reg.cadoles.com/envole/nineapache:8.5` utilise l'utilisateur `apache` avec les identifiants **UID 101 / GID 102** :

```bash
# Depuis l'hôte (selon votre configuration de volumes)
chown -R 101:102 public/uploads uploads
```

## Configuration

Éditer le fichier `.env.local` avant de démarrer les containers :

```env
DATABASE_URL="mysql://user:pass@db:3306/labelnr"
MODE_AUTH=SQL|CAS|OIDC
```

### Authentification CAS/OIDC

```env
CAS_HOST=cas.example.com
CAS_PORT=443
CAS_PATH=/cas
OIDC_ISSUER=https://...
OIDC_CLIENTID=...
OIDC_CLIENTSECRET=...
```

## Utilisation

### Démarrage

```bash
docker-compose up -d
```

### Arrêt

```bash
docker-compose down
```

### Accès

- Application : http://localhost:8000
- Adminer (base de données) : http://localhost:8080

### Création d'un lien de partage

1. Se connecter en admin
2. Aller dans **Partage** > Créer un lien
3. Copier le lien généré

Le lien donne un accès lecture seule au dossier.

## Commandes utiles

```bash
# Clear cache
docker exec labelnr bin/console cache:clear

# Mise à jour schéma BDD (si besoin)
docker exec labelnr bin/console d:s:u --force

# Logs
docker logs -f labelnr
```

## License

Voir fichier [LICENSE](LICENSE).
