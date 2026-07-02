<?php

use App\Services\PriceCalculator;

describe('AccomodationMaximumPrice', function () {
    it('return the paid price when it is under the limit', function () {

        $totalPricePaid = 100;
        $ceiling = 75;
        $numberOfNight = 2;

        $price = PriceCalculator::calculateMaximumPricePerNight($totalPricePaid, $ceiling, $numberOfNight);

        expect($price)->toBe(100.0);

    });

    it('return maximum price when over the ceiling', function () {

        $totalPricePaid = 200;
        $ceiling = 75;
        $numberOfNight = 2;

        $price = PriceCalculator::calculateMaximumPricePerNight($totalPricePaid, $ceiling, $numberOfNight);

        expect($price)->toBe(150.0);

    });

    it('choose smallest price to reimburse as numeric string (coercion check)', function () {

        $totalPricePaid = '100';
        $ceiling = '75';
        $numberOfNight = '2';

        $price = PriceCalculator::calculateMaximumPricePerNight($totalPricePaid, $ceiling, $numberOfNight);

        expect($price)->toBe(100.0);
    });

    it('return no price to reimburse with no price paid provided', function () {

        $totalPricePaid = 0;
        $ceiling = 75;
        $numberOfNight = 2;

        $price = PriceCalculator::calculateMaximumPricePerNight($totalPricePaid, $ceiling, $numberOfNight);

        expect($price)->toBe(0.0);

    });

    it('throws an exception with the correct message for an incorrect price given', function () {

        $totalPricePaid = -10;
        $ceiling = 75;
        $numberOfNight = 2;

        expect(fn () => PriceCalculator::calculateMaximumPricePerNight($totalPricePaid, $ceiling, $numberOfNight))
            ->toThrow(InvalidArgumentException::class, 'Price cannot be negative');
    });
});

describe('MealMaximumPrice', function () {
    it('return the maximum possible when paid price is over the total', function () {

        $totalPricePaid = 100;
        $maxPricePerDay = 25;
        $numberOfMeal = 2;

        $price = PriceCalculator::calculateMaximumPricePerMeal($totalPricePaid, $maxPricePerDay, $numberOfMeal);

        expect($price)->toBe(50.0);

    });

    it('return total price when under maximum', function () {

        $totalPricePaid = 45;
        $maxPricePerDay = 25;
        $numberOfMeal = 2;

        $price = PriceCalculator::calculateMaximumPricePerMeal($totalPricePaid, $maxPricePerDay, $numberOfMeal);

        expect($price)->toBe(45.0);

    });

    it('choose smallest price to reimburse as numeric string (coercion check)', function () {

        $totalPricePaid = '100';
        $maxPricePerDay = '25';
        $numberOfMeal = '2';

        $price = PriceCalculator::calculateMaximumPricePerMeal($totalPricePaid, $maxPricePerDay, $numberOfMeal);

        expect($price)->toBe(50.0);
    });

    it('return nothing to reimburse with no price paid provided', function () {

        $totalPricePaid = 0;
        $maxPricePerDay = 25;
        $numberOfMeal = 2;

        $price = PriceCalculator::calculateMaximumPricePerMeal($totalPricePaid, $maxPricePerDay, $numberOfMeal);

        expect($price)->toBe(0.0);

    });

    it('throws an exception with the correct message for an incorrect price given', function () {

        $totalPricePaid = -10;
        $maxPricePerDay = 25;
        $numberOfMeal = 2;

        expect(fn () => PriceCalculator::calculateMaximumPricePerMeal($totalPricePaid, $maxPricePerDay, $numberOfMeal))
            ->toThrow(InvalidArgumentException::class, 'Price cannot be negative');
    });
});

describe('TrainingMaximumPrice', function () {
    it('return limit when number total price is over the limit', function () {

        $numberOfTrainingDays = 10;
        $pricePerDay = 25;
        $maxReimbursed = 149.7;

        $price = PriceCalculator::calculateMaximumPricePerTrainingPeriod($numberOfTrainingDays, $pricePerDay, $maxReimbursed);

        expect($price)->toBe(149.70);

    });

    it('choose smallest price to reimburse as numeric string (coercion check)', function () {

        $numberOfTrainingDays = '10';
        $pricePerDay = '25';
        $maxReimbursed = '149.7';

        $price = PriceCalculator::calculateMaximumPricePerTrainingPeriod($numberOfTrainingDays, $pricePerDay, $maxReimbursed);

        expect($price)->toBe(149.70);
    });

    it('returns calculated price when under the maximum', function () {

        $numberOfTrainingDays = 3;
        $pricePerDay = 21.30;
        $maxReimbursed = 149.10;

        $price = PriceCalculator::calculateMaximumPricePerTrainingPeriod($numberOfTrainingDays, $pricePerDay, $maxReimbursed);

        expect($price)->toBe(63.90);
    });

    it('returns the exact price when it equals the maximum', function () {

        $numberOfTrainingDays = 7;
        $pricePerDay = 21.30;
        $maxReimbursed = 149.10;

        $price = PriceCalculator::calculateMaximumPricePerTrainingPeriod($numberOfTrainingDays, $pricePerDay, $maxReimbursed);

        expect($price)->toBe(149.10);
    });
});
