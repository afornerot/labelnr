# LabelNR

Application de gestion de labels NR (Numérique Responsable) pour l'audit RSE.

## Fonctionnalités

- Gestion des thématiques, axes prioritaires (PA) et actions TIR
- Évaluation de la maturité selon plusieurs niveaux
- Gestion documentaire (DMR) par action
- Génération de rapports et exports
- Système de partage par token pour accès lecture seule

## Installation

```bash
composer install
bin/console d:s:u --force
```

## Configuration

Copier `.env` vers `.env.local` et configurer les variables :

```env
DATABASE_URL="mysql://user:pass@127.0.0.1:3306/labelnr"
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

### Création d'un lien de partage

1. Se connecter en admin
2. Aller dans Partage > Créer un lien
3. Copier le lien généré

Le lien donne un accès lecture seule au dossier.

## License

Voir fichier [LICENSE](LICENSE).
