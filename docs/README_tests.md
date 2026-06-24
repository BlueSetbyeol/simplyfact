# Context

Ajout d'une dimension de tests au projet SimplyFact pour répondre à un besoin réel et à la demande du cours **Qualité logicielle et Tests** qui consiste à reprendre les points suivant :

- qualité logicielle
- TDD
- tests d'intégration
- tests E2E
- CI/CD
- documentation QA
- utilisation raisonnée de l'IA générative

# La demande

Mini-projet Individuel en TDD + Stratégie de tests

## Instructions et attentes :

Doit contenir :

- des tests unitaires, d'intégration :
  - pour des cas nominaux
  - pour des cas limites
  - pour des cas d'erreur
- des tests automatisés
- une pipeline CI/CD
- un rapport QA

## Instructions spécifique pour la reprise d'un projet existant :

Comme le code existe déjà, vous devez expliquer comment vous auriez développé au moins une fonctionnalité importante en TDD.

Vous devez présenter au minimum 3 cycles Red → Green → Refactor reconstruits :

comportement attendu ;
test qui aurait été écrit en premier ;
échec attendu du test ;
code minimal permettant de faire passer le test ;
amélioration ou refactor possible.

Vous devez également préciser clairement que cette démarche est reconstruite à partir d’un projet existant, et expliquer ce que vous avez réellement ajouté ou modifié.

# La réponse

## L'existant

Le projet utilisant le framework Laravel, certaines options ont été installé de base. Voici ce qui existe et qui ne va pas être modifié :

- le dossier _tests_ (les tests seront ajouté dans les dossiers enfants suivant)
- le dossier _Feature_ (pas de modification) - le dossier _Auth_ (pas de modification) - le dossier _Settings_ (pas de modification) - le fichier _DashboardTest.php_ (pas de modification)
  le dossier _Unit_ (les tests seront ajouté ici)
  Les fichiers _ExampleTest.php_ existant qui seront laissé tel quel.

## Ajouts

Ajout d'un dossier _Integration_ dans le dossier _tests_ selon les consignes.
Ajout d'un dossier _e2e_ selon les consignes.
Ajout d'un fichier _ci.yml_ dans le dossier _.github/workflows_ (existant) selon les consignes.

Ajout d'un fichier _QA_REPORT.md_ à la racine du dossier simplyFact (projet)

### TDD possible :

Logique métier à structurer: mettre les calculs des distances et des totaux dans un fichier séparé à appeler dans React en tant que service.

> A l'heure actuelle, ces fonctions sont dans les composants et page front-end ce qui est moins propre et structutée.
> Cela donne l'occasion de pensée en mode TDD :
>
> - écrire un test qui échoue
> - écrire le minimum de code pour le faire passer
> - améliorer le code si nécessaire

### Tests Unitaires possible :

- Les tests du TDD :
  - au moins une suite de test unitaires
  - plusieurs comportement testés
  - au moins un cas nominal
  - au moins un cas limite
  - au moins un cas d'erreur
- Ajout de piéces jointe (?)
- Ajout d'une étape dans le flow

### Tests d'intégration possible :

Les test doivent vérifier que plusieurs parties du projet fonctionnent ensemble - au moins un cas nominal - au moins un cas d'erreur - au moins une vérification du format de réponse - vérification du statut HTTP si API

- route API + service métier
- route API + stockage en mémoire
- formulaire + appel API

### Test E2E possible :

Il faut utilisé Cypress, Playwright en priorité - simuler un comportement utilisateur réel; - utiliser des sélecteurs robuste; - vérifier un résultat visible

- ajouter une étape dans le flow et la voir dans le summary
- ajouter un hébergement et le voir dans la liste des hébergements à rembourser

### CI/CD :

- doit installer les dépendances
- lancer les tests unitaires
- lancer les tests d'intégration si possible

### Rapport QA :

### Commandes à documenter :

- composer install
- composer run dev
- composer run test (???)
