## Enoncé :

### Prise de notes :

#### Initial :

Grosses associations, donc doit gérer des dépenses, remboursements, fiscalité ...
Justification de dépenses
Réception de fichiers (récupération et centraliser) : photos, pdf
Outils simple à utiliser, configurable

#### Secondaire :

Dans une asso ou une direction, il y a des personnes qui ont des moyens de paiement. Cela implique une comptabilité pour justifier aux contrôles, Bilans, Impôts, Suivi de la TVA.
Dans le cas d'un paiement autrement qu'avec le moyen de paiement de l'entreprise, il faut faire une demande de remboursement : Note de frais.
Validation des budgets par l'école mais aussi par la fédération.
EFS : Ecole Française de Spéléologie, commission de la FFSCP (spéléologie, canyoning, plongé sous marine).
En tant que Client : je veux juste qu'on me rembourse (16 and 80 ans)
En tant que Trésorier : je veux que les dépenses soit justifié et que la Comptable de la fédé puisse tout traiter sans problème.
Abandon du remboursement : don des frais encouru à l'association, pour que cela rentre dans les dons aux associations pour les impôts.

### Concept :

Existant :

- un fichier excel complexe à remplir
- il est composant de plusieurs feuilles : une type facture, une de type récupération de pièces jointe, une explication / mode d'emploi, une référence de catégorie, abandon des frais.
- But : de transformer/envoyer un PDF au service compta de la fédération de spéléologie.
  Contrainte :
- Adaptabilité à toutes capacités d'utilisation des outils informatique.
- Node ou PHP
- Simplifié le fichier excel : aller au plus simple (minimaliser)
- Conditions à créer pour remplir le moins de questions possible en fonction du besoin réel.
  Objectif V1 :
- Remplacer ce fichier excel par un formulaire "intelligent" qui produit un pdf.
- A l'aide du formulaire, on peut stocker et proposer des données de remplissage automatique pour des utilisateurs identifiés et des Notes de Frais récurrentes.
  Objectif V2 :
- Sortir un CSV pour les virements
- Proposer autre chose qu'un PDF pour le service compta
- Gestion des utilisateurs : Back office.

## Besoins :

Interface utilisateur (non obligatoire ou possible à la fin du formulaire avec les informations déjà saisie et non la totalité obligatoirement, ou encore uniquement au choix de l'utilisateur avec uniquement utilisation de son email comme mdp)
Compte utilisateur + connexion ==> Authentification, Token
Export PDF à envoyer par email :

- pdf : pdfkit, [jsPDF](https://github.com/MrRio/jsPDF), pdf-lib (pdf dans pdf)
- mail : [nodemailer](https://nodemailer.com/),
  Back End (PHP Laravel ou Nest JS + typeORM, Express JS ?) + Front End (React, React Native ?) + BDD(MySQL : déjà mis en place sur serveur)
  Vérification Front : Zod ?
  Vérification Back : Zod ?
  MUI / DaisyUI
  Signature en ligne (trackpad)

### Base de données

#### Simplifié

C'est à dire deux tables : `user` et `expenses_claim`
`user`

| champ           | caractéristiques |
| --------------- | ---------------- |
| id              | uuid PK          |
| firstname       | string           |
| lastname        | string           |
| address_street  | string           |
| address_zipcode | number           |
| address_city    | string           |
| address_country | string           |
| email_address   | string           |
| phone_number    | string           |

`expenses_claim`

| champ             | caractéristiques          |
| ----------------- | ------------------------- |
| id                | uuid PK                   |
| user              | uuid PK                   |
| commitee_name     | string                    |
| title             | string                    |
| action_dates      | string                    |
| claim_declaration | string (JSON.stringify()) |
| total_given       | number                    |
| total_reimbursed  | number                    |
| created_at        | timestamp                 |

possiblement `vehicule`

| champ         | caractéristiques         |
| ------------- | ------------------------ |
| id            | uuid PK                  |
| owner         | uuid FK(user)            |
| vehicule_type | string ("auto"\| "moto") |
| number_plate  | string                   |

#### Complète

`user`

| champ           | caractéristiques |
| --------------- | ---------------- |
| id              | uuid PK          |
| firstname       | string           |
| lastname        | string           |
| address_street  | string           |
| address_zipcode | number           |
| address_city    | string           |
| address_country | string           |
| email_address   | string           |
| phone_number    | string           |

> on pourrait avoir un champ `favorite_commitee`pour auto-remplir le nom de la commission pour laquelle l'utilisateur remplis les NDF

`vehicle`

| champ          | caractéristiques                                    |
| -------------- | --------------------------------------------------- | --------------------- |
| id             | uuid PK                                             |
| user           | uuid FK                                             |
| legal_document | uuid FK (car_registration_licence) si c'est utilisé | option à voir pour V2 |
| vehicule_type  | "voiture" \| "moto"                                 |
| electrical     | boolean default false                               |
| power          | string (select en dur dans le front)                |
| price_given    | number                                              | rate du prix          |
| number_plate   | string                                              |
| added_at       | timestamp                                           |

`car_registration_licence` (pour la carte grise, optionnel - à decider)

| champ    | caractéristiques |
| -------- | ---------------- |
| id       | uuid PK          |
| document | string ??        |
| url      | string ??        |
| added_at | timestamp        |

`expenses_claim`

| champ            | caractéristiques     |
| ---------------- | -------------------- |
| id               | uuid PK              |
| user             | uuid FK nullable (?) |
| commitee_name    | string               |
| action_name      | string               |
| action_dates     | string               |
| total_given      | number nullable      |
| total_reimbursed | number nullable      |
| created_at       | timestamp            |

`accommodation`

| champ              | caractéristiques         |
| ------------------ | ------------------------ |
| id                 | uuid PK                  |
| expenses_claim     | uuid FK (expenses_claim) |
| accommodation_type | string                   |
| nb_of_night        | number                   |
| total_price        | number                   |
| reimbursed_price   | number                   |

`meal`

| champ            | caractéristiques         |
| ---------------- | ------------------------ |
| id               | uuid PK                  |
| expenses_claim   | uuid FK (expenses_claim) |
| number_of_meal   | number                   |
| total_price      | number                   |
| reimbursed_price | number                   |

`training_expense`

| champ               | caractéristiques         |
| ------------------- | ------------------------ |
| id                  | uuid PK                  |
| expenses_claim      | uuid FK (expenses_claim) |
| nb_days_of_training | number                   |
| total_price         | number                   |
| reimbursed_price    | number                   |

`other_expense`

| champ            | caractéristiques         |
| ---------------- | ------------------------ |
| id               | uuid PK                  |
| expenses_claim   | uuid FK (expenses_claim) |
| expense_name     | string                   |
| total_price      | number                   |
| reimbursed_price | number                   |

`driven_trip`

| champ                | caractéristiques         |
| -------------------- | ------------------------ |
| id                   | uuid PK                  |
| expenses_claim       | uuid FK (expenses_claim) |
| vehicle              | uuid FK (vehicle)        |
| starting_city        | string                   |
| strating_zip_code    | number                   |
| ending_city          | string                   |
| ending_zip_code      | string                   |
| trip_type            | string nullable          |
| total_distance       | number                   |
| total_price          | number                   |
| total_distance_given | number nullable          |
| total_price_given    | number nullable          |
| description          | string nullable          |
| reimbursed_price     | number                   |

> question de savoir si on fait une table driven_trip_destination et destination ?

`other_trip`

| champ            | caractéristiques         |
| ---------------- | ------------------------ |
| id               | uuid PK                  |
| expenses_claim   | uuid FK (expenses_claim) |
| expense_name     | string                   |
| total_price      | number                   |
| reimbursed_price | number                   |

#### Choix final

BDD **complète** car plus stable et adaptable à notre user flow.

### Sauvegarde des documents

Non nécessaire pour les justificatifs de paiement, ils doivent uniquement être join au PDF final.
Possibilité de sauvegarder uniquement la carte grise ou de tout mettre dans un dossier temporaire et sous-dossier avec l'id de la note de frais pour ensuite supprimer les documents à l'envoi de la NDF

### Fonctionnalités

Utilisateurs :

- Enregistrer des données à réutiliser dans le profil
  - véhicule
  - carte grise
  - association / commission : nom
  - personne physique (?)
  - adresse
  - information bancaire
- Voir son historique
- Ré-utiliser un ancien formulaire
- Explication possible sur certaine entrée

Formulaires :

- Remplir le formulaire et le mettre en pause à tout moment
- Auto-complétion possible avec information utilisateur
- Récupération d'une autre note de frais
- Signature électronique
- Ajout de documents justificatif
- Exportation en PDF
- Envoi par email au service comptable et à l'utilisateur

> Bonus : envoie d'un CSV descriptif

## Plannification de l'application

### V1.1 :

React + Nest JS
=> petit formulaire + formation PDF () + envoie email
Pk : compréhension et stabilisation de la logique technique

### V1.2 :

=> vrai formulaire + formation PDF + envoie email + envoie des données en BDD
Pk : enregistrement des données, authentification et répétition des données pour usage ultérieur + historique.
Q : possibilité de stocker les informations en plusieurs format (informatif ou complet pour pouvoir en tirer des études et analyse en plus de l'historique possiblement personnalisé)

## Questions :

- Est-ce qu'il faut que l'on affiche un nom d'Hotel pour l'UI/UX ? Ou juste le type de logement suffit amplement ?
