# Présentation

Ce projet a été réalisé à l'occasion de plusieurs cours applicatif, durant le premier semestre de 2026, qui m'ont donné l'occasion de mettre en pratique plusieurs disciplines :

- Application Web ;
- Laravel + PHP & Inertia ;
- Utilisation de S3, Mailpit et création de PDF ;
- Tests unitaires, d'intégration, fonctionnels & E2E ;
- À confirmer : Déploiement sur Laravel Cloud ;

Je vous invite à venir le découvrir et, pourquoi pas, à le prendre en main à votre tour si celui-ci vous intéresse.

## SimplyFact, c'est quoi ?

SimplyFact répond à la demande de la Fédération Française de Spéléologie, qui doit traiter les notes de frais et les remboursements associés de toutes les associations qui lui sont rattachées.
_Les photos utilisés dans la page d'accueil sont la propriété de la FFS_

Originellement, un fichier Excel existait, mais il restait peu accessible et compliqué pour la plupart des gens.
C'est pourquoi il nous a été demandé de proposer une solution web, facile d'accès et d'usage pour tous les utilisateurs.

De cette demande, en apparence simple, est né SimplyFact.

Nous avons réalisé cette web application autour d'un Flow (flu d'action) qui simplifient la complétion de la note de frais à ses tâches les plus essentiel.
Pour finaliser la note de frais, les adhérents de l'association auront d'abord à choisir quels dépenses ils veulent déclarer puis à suivre le chemin (Flow) qui les guides une étape par une étape jusqu'à la confirmation et la proposition d'inclure un don.

## Technologies

- React Js
- Inertia
- TypeScript
- Tailwind CSS
- Material UI
- Mailpit
- S3

## Ce que vous trouverez dans ce projet

### docs

Le projet étant issu d'un travail de groupe dans le cadre d'une formation, vous trouverez dans ce repo/dossier les documents de réflexion ayant mené au développement de ce projet.

### app

Le Back end du projet

### database

Les migrations et les factories de la Base de données

### resources

Les routes API et le Front end du projet

### tests

Tous les tests réalisés

## Send email

Pour tester l'envoie d'email localement:

- Installer Mailpit: https://mailpit.axllent.org/docs/install/

- Changer votre fichier `.env`:

```env
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@simplyfact.fr"
MAIL_FROM_NAME="${APP_NAME}"
MAIL_TO_ACCOUNTANT="comptable@ffs.fr"
```

- Lancer Mailpit depuis le terminal:

```bash
mailpit
```

- Mailpit UI est disponible à cette [url](http://localhost:8025)

## S3 storage

Notre application utilises le stockage de S3 et les url signé ("signed url") pour les fichiers uploader.
Vous aurez besoin de :

- configurer votre propre 'bucket' s3 et ajouter un utilisateur avec les bonnes authorisation dans IAM.
- completer les variables env :

```env
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=tour_secret_access_key
AWS_DEFAULT_REGION=eu-west-3
AWS_BUCKET=simplyfact
AWS_USE_PATH_STYLE_ENDPOINT=false
```

## Comment installer le projet

Pour commencer, cloner ce repo/dossier et faites les commandes suivantes :

- composer update
- npm install
- php artisan:migrate
- composer run dev
