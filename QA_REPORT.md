# Documentation QA

## 1. Présentation du projet

### Choix du projet?

La Fédération Française de Spéléologie Canyoning et Plongée sous marine a besoin de remplacer la manière dont les commissions et leurs adhérents produisent des notes de frais et les transmettent à l'équipe de comptabilité. Jusqu'à maintenant, cela était géré via un questionnaire depuis un template Excel : un document qui rassemblent énormément d'informations et peut être difficile à aborder et remplir.

SimplyFact est la solution web que nous avons construite pour répondre à ce proble. L'application Web guides les utilisateurs à travers un flux d'étapes : sélection des étapes, remplir étape par étape la note de frais puis l'envoyé en ayant transformé celle-ci en pdf. Cela dans l'objectif de rendre le processus de complétion de la note de frais le plus simple possible.

> _Les Photos utilisé sur la page Home et la page End sont les propriété de la FFSCP._

### Stack Technique

| Couche        | Technologies                                     |
| ------------- | ------------------------------------------------ |
| Frontend      | React, TypeScript, Tailwind CSS, Material UI     |
| Backend       | Laravel (PHP), Inertia.js, ORM Eloquent + SQLite |
| Stockage      | Amazon S3 (signed URLs)                          |
| Email (local) | Mailpit                                          |

### Structure du Projet

```
├── app/          # Logic de l'application Backend
├── database/     # Migrations et factories
├── docs/         # Design et documents de préparation en groupe
├── lang/         # Traduction vers différente langue
├── resources/    # Source et logic Frontend
├── routes/       # routes API
└── tests/        # Les tests (unit, integration, feature, E2E)
```

## 2. Fonctionnalités développées

Précédemment, nous avions déjà développé le front end et le back end pour permettre à l'utilisateur de remplir les dépenses qu'il souhaitait renseigner afin que celles-ci soient transmise à la comptabilité via un email portant le pdf résumant la note de frais.

Afin d'effectuer les tests de cette application que j'avais réalisé précédemment en groupe, j'ai pu créer des fonctions pour vérifier les calculs et les informations amené par le front end dans le back end avant l'ajout dans la BDD.

J'ai aussi ajouté des factories afin de pouvoir mocker des informations stockée par la base de donnée.

## 3. Règles métier principales

Un utilisateur peut remplir une à six étapes dans cette une note de frais.

Un utilisateur doit fournir un justificatif pour chaque dépenses ( à l'exception de l'indémnisation de stage qui n'est pas une dépense).

Pour une dépense de repas, l'utilisateur sera remboursé au maximum à hauteur du forfait par repas donné par l'association.

Pour une nuit en hébergement, chaque hébergement est limité à une somme spécifique par nuit.

Dans les deux cas, si l'utilisateur dépense moins que la somme du forfait alors il sera remboursé de la totalité dépensé et non du maximum forfaitaire.

Pour une distance parcourue, l'utilisateur à la possibilité d'abandonné (en faveur de l'association) tout ou partie du trajet déclarée. Celui-ci sera pris en compte et déclaré auprès de l'état pour les taxes de l'utilisateur. Il faut donc pouvoir faire les calculs selon les critaires sélectionnés.

A la fin de la déclaration de la note de frais, l'utilisateur peut ou non faire un complément d'abandon de tout ou partie des frais déclaré avant de finaliser la note de frais.

## 4. Démarche TDD

### Cycle 1 : Vérification de la somme abandonnée à la fin de la déclaration

**Comportement attendu** :
Récupération des coups associé à la note de frais et comparaison à la somme du total à rembourser + total abandonné.

Test écrit :

```php
it('updates the claim and redirects to done when reimbursed total matches the calculation', function () {
    $expensesClaim = ExpensesClaim::factory()
        ->has(OtherTrip::factory()->count(3), 'otherTrips')
        ->has(Meal::factory(), 'meals')
        ->create();

    $claim = ExpensesClaim::with([
        'drivenTrips', 'otherTrips', 'accommodations', 'meals', 'trainingExpenses', 'otherExpenses',
    ])->findOrFail($expensesClaim->id);

    $totalGiven = 500;
    $expectedReimbursed = PriceCalculator::calculateTotalPriceAndTotalReimbursed($claim, $totalGiven);

    $response = $this->put(route('expenses-claims.update', $expensesClaim), [
        'total_given' => $totalGiven,
        'total_reimbursed' => $expectedReimbursed,
    ]);

    $response->assertRedirect(route('expenses-claims.flow.done', $expensesClaim));

    $this->assertDatabaseHas('expenses_claims', [
        'id' => $expensesClaim->id,
        'total_reimbursed' => $expectedReimbursed,
    ]);
});
```

Résultat initial : échec

```
FAILED Tests\Integration\ExpensesClaimSummaryTest > `expenses claims` → it updates the claim and redirects to done when reimbursed total matches… TypeError
 App\Services\PriceCalculator::calculateTotalPriceAndTotalReimbursed(): Argument #1 ($totalFromClaim) must be of type float, App\Models\ExpensesClaim given, called in /Users/sica/Documents/mds/w9-applications/simplyfact/tests/Integration/ExpensesClaimSummaryTest.php on line 42

at app/Services/PriceCalculator.php:59
55▕
56▕ return round(min($numberOfTrainingDays * $pricePerDay, $maxReimbursed), 2);
     57▕     }
     58▕
  ➜  59▕     public static function calculateTotalPriceAndTotalReimbursed(float $totalFromClaim, float $totalGiven): float
     60▕     {
     61▕         return round(($totalFromClaim - $totalGiven), 2);
62▕ }
63▕ }

1 app/Services/PriceCalculator.php:59
2 tests/Integration/ExpensesClaimSummaryTest.php:42

```

Code ajouté :

```php
public static function calculateTotalPriceAndTotalReimbursed(ExpensesClaim $claim, float $totalGiven): float
{
    $relations = [
        'drivenTrips', 'otherTrips', 'accommodations',
        'meals', 'trainingExpenses', 'otherExpenses',
    ];

    $totalFromClaim = 0;

    foreach ($relations as $relation) {
        $totalFromClaim += $claim->{$relation}->sum('total_price');
    }

    return round(($totalFromClaim - $totalGiven), 2);
}
```

Résultat final : succès

### Cycle 2 : Comparaison du prix payé et de la somme maximum à payer pour garder la plus petite

Comportement attendu : Doit renvoyé une erreur lorsque les sommes précisés ne sont pas accepté

Test écrit :

```php
it('throws an exception with the correct message for an incorrect price given', function () {

    $totalPricePaid = -10;
    $maxPricePerDay = 25;
    $numberOfMeal = 2;

    expect(fn () => PriceCalculator::calculateMaximumPricePerMeal($totalPricePaid, $maxPricePerDay, $numberOfMeal))
        ->toThrow(InvalidArgumentException::class, 'Price cannot be negative');
});
```

Résultat initial : échec, car exception non ajoutée

Code ajouté :

```php
if ($totalPricePaid < 0) {
    throw new InvalidArgumentException('Price cannot be negative');
}
```

Résultat final : succès

#### Cycle 3 : Vérification du type d'entrée pour le nombre de jour de stage

Comportement attendu : Doit renvoyer la plus petite somme entre le taux journalier et le maximum par mois

Test écrit :

```php
it('return limit when number total price is over the limit', function () {

    $numberOfTrainingDays = 10;
    $pricePerDay = 25;
    $maxReimbursed = 149.7;

    $price = PriceCalculator::calculateMaximumPricePerTrainingPeriod($numberOfTrainingDays, $pricePerDay, $maxReimbursed);

    expect($price)->toBe(149.70);

});
```

Résultat initial : échec

Code ajouté :

```php
public static function calculateMaximumPricePerTrainingPeriod(float $numberOfTrainingDays, float $pricePerDay, float $maxReimbursed): float
{

    return round(min($numberOfTrainingDays * $pricePerDay, $maxReimbursed), 2);
}
```

Résultat final : succès

## 5. Risques qualité identifiés

Pour ce projet, le principal risque est que les calculs ne soient pas fait correctement que ce soit du côté front ou du côté back.
Cela aurait des conséquences pour les remboursements qui devraient ensuite suivre la complétion de la note de frais.

## 6. Stratégie de tests

J'ai commencé par les tests unitaires pour commencer à prendre en main les tests puis j'ai aggrandi pour les tests d'intégrations.

## 7. Tests unitaires réalisés

- Tests sur le calcul des distances données par l'adhérent
- Tests sur le calcul de la somme remboursé pour les repas (sachant qu'il y a un taux maximum par repas)
- Tests sur le calcul de la somme remboursé pour les hébergements (sachant qu'il y a un taux par nuit dans un hébergement spécifique)
- Tests sur le calcul de la somme d'indémnité que va recevoir l'encadrant d'un stage (sachant qu'il y a un taux par jour et un maximum par mois)
- Tests pour valider le nombre de jour déclaré pour les stages

Cela dans le but de valider tous les calculs porteur de risque pour l'application.

## 8. Tests d'intégration réalisés

- Tests pour valider les choix que l'adhérent à fait
- Tests pour valider la sauvegarde des choix de l'adhérent
- Tests pour valider la redirection à la dernière étape de la validation de la note de frais
- Tests pour valider l'ajout de la somme remboursé dans la dernière étape de la note de frais

Cela dans le but de vérifier plusieurs exemples d'intéraction avec les routes API.

## 9. Test E2E

- Le test de la page d'accueil

## 10. CI/CD

La CI/CD est mise en place sur github depuis le projet et est présent dans le dossier `.github/workflows/ci.yml`. J'ai aussi gardé une deuxième CI pour les tests uniquement, prévue par Laravel `.github/workflows/tests.yml`.

Elle se déclenche à chaque push sur les branches `dev`et `main` ainsi que sur la pull request effectué sur la branche `main`.

Une fois que le merge est fait sur la branche `main`, les mises à jour du code sont envoyé vers la plateforme Laravel Cloud pour accompagner le déploiement continus pour le site qui est en ligne à l'adresse [suivante](https://simplyfact-production-m8wtgc.laravel.cloud/).
Attention: l'envoi de mail n'étant pas mis en place, le processus s'arrête sur une erreur.

### Les commandes exécutées

La CI consiste à :

- `ci.yml` : Lint, Types & Build
    - installation de node.js et de php
    - linter back
    - formater et lint front
    - vérification typescript
- `test.yml` : tests
    - installation de php (et de node)
    - mise en place des variables nécessaire
    - build
    - tests sous pest

### les tests lancés

```bash
 PASS  Tests\Unit\DistanceGivenTest
  ✓ DrivenTripTotalPricePaid → it calculates total price correctly with distance given                                                                                0.18s
  ✓ DrivenTripTotalPricePaid → it calculates total price correctly with no distance given                                                                             0.01s
  ✓ DrivenTripTotalPricePaid → it rounds to two decimal places                                                                                                        0.01s
  ✓ DrivenTripTotalPricePaid → it throws an exception with the correct message for an incorrect total distance                                                        0.01s
  ✓ DrivenTripTotalPricePaid → it throws an exception with the correct message for an incorrect distance given                                                        0.01s
  ✓ DrivenTripTotalPriceGiven → it calculates total price given correctly                                                                                             0.01s
  ✓ DrivenTripTotalPriceGiven → it calculates total price given as numeric string (coercion check)                                                                    0.01s
  ✓ DrivenTripTotalPriceGiven → it calculates total price given correctly with no distance provided                                                                   0.01s
  ✓ DrivenTripTotalPriceGiven → it throws an exception with the correct message for an incorrect price given                                                          0.01s
  ✓ DrivenTripTotalPriceGiven → it throws an exception with the correct message for an incorrect total distance                                                       0.01s


   PASS  Tests\Unit\MaximumPriceTest
  ✓ AccomodationMaximumPrice → it return the paid price when it is under the limit                                                                                    0.01s
  ✓ AccomodationMaximumPrice → it return maximum price when over the ceiling                                                                                          0.01s
  ✓ AccomodationMaximumPrice → it choose smallest price to reimburse as numeric string (coercion check)                                                               0.02s
  ✓ AccomodationMaximumPrice → it return no price to reimburse with no price paid provided                                                                            0.01s
  ✓ AccomodationMaximumPrice → it throws an exception with the correct message for an incorrect price given                                                           0.01s
  ✓ MealMaximumPrice → it return the maximum possible when paid price is over the total                                                                               0.01s
  ✓ MealMaximumPrice → it return total price when under maximum                                                                                                       0.01s
  ✓ MealMaximumPrice → it choose smallest price to reimburse as numeric string (coercion check)                                                                       0.01s
  ✓ MealMaximumPrice → it return nothing to reimburse with no price paid provided                                                                                     0.01s
  ✓ MealMaximumPrice → it throws an exception with the correct message for an incorrect price given                                                                   0.01s
  ✓ TrainingMaximumPrice → it return limit when number total price is over the limit                                                                                  0.01s
  ✓ TrainingMaximumPrice → it choose smallest price to reimburse as numeric string (coercion check)                                                                   0.01s
  ✓ TrainingMaximumPrice → it returns calculated price when under the maximum                                                                                         0.01s
  ✓ TrainingMaximumPrice → it returns the exact price when it equals the maximum                                                                                      0.01s

   PASS  Tests\Unit\ValidationEntryTypeTest
  ✓ ValidateTrainingDaysEntry → it rejects training days below 1                                                                                                      0.08s
  ✓ ValidateTrainingDaysEntry → it rejects non-integer training days                                                                                                  0.01s

 PASS  Tests\Integration\FlowTest
  ✓ choices → it renders the choices page with the expensesClaim                                                                                                      0.04s
  ✓ saveChoices → it stores only valid steps in session and redirects to summary                                                                                      0.02s
  ✓ saveChoices → it strips out invalid step names                                                                                                                    0.01s
  ✓ saveChoices → it stores an empty array when no valid steps are selected                                                                                           0.01s
  ✓ saveChoices → it stores an empty array when no steps are submitted                                                                                                0.01s

  Tests:    33 passed (52 assertions)
  Duration: 0.78s
```

### En cas de l'échec d'un test

En cas d'échec, la CI et le merge d'une mise à jour du code est stopée et ne peux pas être effectué. Une solution doit être trouvé et push sur le repository/dans le merge afin que les tests puissent recommencé et passé.

### Limite de la pipeline actuelle

Je ne vérifie pas toutes les routes et n'est donc pas de moyen de vérification totale.

## 11. Utilisation éventuelle de l'IA générative

**Outils utilisé** : Claude web

**Prompts utilisés** :

```
What kind of test can I do on this :

// validation de la data

$validated = $request->validate([
    'nb_days_of_training' => 'required|integer|min:1',
]);

$price_per_day = 21.30;
$max_reimbursed = 149.10;

// Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée

$validated['reimbursed_price'] = min($validated['nb_days_of_training'] * $price_per_day, $max_reimbursed);

I already did these 2 :

describe('TrainingMaximumPrice', function () {
    it('choose smallest price to reimburse correctly', function () {

        $numberOfTrainingDays = 10;
        $pricePerDay = 25;
        $maxReimbursed = 149.7;

        $price = PriceCalculator::calculateMaximumPricePerTrainingPeriod($numberOfTrainingDays, $pricePerDay, $maxReimbursed);

        expect($price)->toBe(149.7);

    });

    it('choose smallest price to reimburse as numeric string (coercion check)', function () {

        $numberOfTrainingDays = '10';
        $pricePerDay = '25';
        $maxReimbursed = '149.7';

        $price = PriceCalculator::calculateMaximumPricePerTrainingPeriod($numberOfTrainingDays, $pricePerDay, $maxReimbursed);

        expect($price)->toBe(149.7);
    });

});
```

**Ce qui est conservé** :

```php
it('returns calculated price when under the maximum', function () {
    $price = PriceCalculator::calculateMaximumPricePerTrainingPeriod(
        numberOfTrainingDays: 3,
        pricePerDay: 21.30,
        maxReimbursed: 149.10
    );

    expect($price)->toBe(63.9); // 3 * 21.30, well under cap
});

it('returns the exact price when it equals the maximum', function () {
    $price = PriceCalculator::calculateMaximumPricePerTrainingPeriod(
        numberOfTrainingDays: 7,
        pricePerDay: 21.30,
        maxReimbursed: 149.10
    );

    expect($price)->toBe(149.10); // 7 * 21.30 = 149.10 exactly
});
```

**Ce qui a été modifié** :

Ajout d'une factory :

```php
$expensesClaim = ExpensesClaim::factory()->create();
```

Modification de la prise en compte des variables :

```php
$numberOfTrainingDays = 3;
$pricePerDay = 21.30;
$maxReimbursed = 149.10;

$price = PriceCalculator::calculateMaximumPricePerTrainingPeriod($numberOfTrainingDays, $pricePerDay, $maxReimbursed);
```

**Ce qui a été refusé** :

Un test qui ne pouvait pas être fait car la valeur ne pouvait pas être proposé :

```php
it('rejects training days below 1', function () {
    $response = $this
        ->from(route('some-previous-page')) // wherever the form would have been
        ->post(route('expenses-claims.training-expenses.create'), [
            'nb_days_of_training' => 0,
        ]);

    $response->assertInvalid(['nb_days_of_training']);
});
```

## 12. Limites actuelles

Je n'ai pas pu mettre en place des tests pour le Front bien qu'ayant tenté avec Vitest.
Travaille à finir sur la branche Feature/more_tests

## 13. Améliorations possibles

Ajoutés des tests Front avec Vitest qui fonctionnent et valider les mêmes calculs qu'en Back End

## 14. Preuves d'éxécution attendues

![TDD avec erreur](simplyfact/public/images/proofOfTests/TDDTestUnitError.png)

![Test avec erreur](simplyfact/public/images/proofOfTests/testUnitError.png)

![CI succés d'éxecution](simplyfact/public/images/proofOfTests/CI.png)

![Résultats de Test](simplyfact/public/images/proofOfTests/testsResults.png)

![Résultats de Test E2E 1ère page](simplyfact/public/images/proofOfTests/proofE2E.png)
