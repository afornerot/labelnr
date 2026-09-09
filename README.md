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
- PHP 8.2+ (si développement local sans Docker)

## Installation avec Docker

```bash
# Cloner le projet
git clone https://github.com/afornerot/labelnr.git
cd labelnr

# Démarrer les containers
docker-compose up -d

# Initialiser la base de données
docker exec labelnr bin/console d:s:u --force

# Charger les fixtures (optionnel)
docker exec labelnr bin/console doctrine:fixtures:load
```

### Permissions sur les répertoires d'upload

Les répertoires `uploads/`, `public/uploads/` et `public/medias/` doivent être accessibles en écriture par le serveur web :

```bash
# Dans le container
docker exec labelnr chown -R www-data:www-data /app/uploads
docker exec labelnr chown -R www-data:www-data /app/public/uploads
docker exec labelnr chown -R www-data:www-data /app/public/medias
docker exec labelnr chmod -R 755 /app/uploads
docker exec labelnr chmod -R 755 /app/public/uploads
docker exec labelnr chmod -R 755 /app/public/medias
```

Ou depuis l'hôte (selon votre configuration de volumes) :

```bash
chown -R 82:82 public/uploads public/medias uploads
```

## Configuration

Éditer le fichier `.env.local` ou les variables d'environnement :

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

# Mise à jour schéma BDD
docker exec labelnr bin/console d:s:u --force

# Logs
docker logs -f labelnr
```

## License

Voir fichier [LICENSE](LICENSE).
