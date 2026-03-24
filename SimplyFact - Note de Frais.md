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
 Interface utilisateur (non obligatoire ou alors à la fin du formulaire avec les informations déjà saisie et non la totalité obligatoirement)
 Compte utilisateur + connexion ==> Authentification, Token
 Export PDF à envoyer par email : 
 - pdf : pdfkit, [jsPDF](https://github.com/MrRio/jsPDF)
 - mail : [nodemailer](https://nodemailer.com/), 
 Back End (PHP Laravel ou Nest JS + typeORM, Express JS ?) + Front End (React, React Native ?) + BDD(MySQL : déjà mis en place sur serveur)
 Vérification Front : Zod ?
 Vérification Back : Zod ?
 MUI / DaisyUI
 Signature en ligne (trackpad)

### Base de données
 V1 = pas de base de données; on va simplement envoyé le pdf formulé par email après avoir fait un recap sur l'interface pour confirmer avec l'utilisateur.

V2 = BDD MySQL

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



## Versionning

### V1 :
React + Nest JS
=> petit formulaire + formation PDF () + envoie email
Pk : compréhension et stabilisation de la logique technique

### V2 : 
=> vrai formulaire + formation PDF + envoie email
Pk : focalisation sur la rédaction de la Note de frais complète

### V3 : 
=> vrai formulaire + formation PDF + envoie email + envoie des données en BDD
Pk : enregistrement des données, authentification et répétition des données pour usage ultérieur + historique.
Q : possibilité de stocker les informations en plusieurs format (informatif ou complet pour pouvoir en tirer des études et analyse en plus de l'historique possiblement personnalisé)