<?php

use App\Services\PriceCalculator;

describe('DrivenTripTotalPricePaid', function () {
    it('calculates total price correctly with distance given', function () {

        // arrange
        $rate = 0.5;
        $totalDistance = 100;
        $distanceGiven = 20;

        // act
        $price = PriceCalculator::calculateTotalPrice($rate, $totalDistance, $distanceGiven);

        // assert
        expect($price)->toBe(40.0);
    });

    it('calculates total price correctly with no distance given', function () {

        $rate = 0.5;
        $totalDistance = 100;
        $distanceGiven = 0;

        $price = PriceCalculator::calculateTotalPrice($rate, $totalDistance, $distanceGiven);

        expect($price)->toBe(50.0);
    });

    it('rounds to two decimal places', function () {

        $rate = 0.146;
        $totalDistance = 10;
        $distanceGiven = 0;

        $price = PriceCalculator::calculateTotalPrice($rate, $totalDistance, $distanceGiven);

        expect($price)->toBe(1.46);
    });

    it('throws an exception with the correct message for an incorrect total distance', function () {

        $rate = 0.146;
        $totalDistance = 10;
        $distanceGiven = -10;

        expect(fn () => PriceCalculator::calculateTotalPrice($rate, $totalDistance, $distanceGiven))
            ->toThrow(InvalidArgumentException::class, 'Distance driven cannot be negative');
    });

    it('throws an exception with the correct message for an incorrect distance given', function () {

        $rate = 0.146;
        $totalDistance = -10;
        $distanceGiven = 10;

        expect(fn () => PriceCalculator::calculateTotalPrice($rate, $totalDistance, $distanceGiven))
            ->toThrow(InvalidArgumentException::class, 'Distance driven cannot be negative');
    });
});

describe('DrivenTripTotalPriceGiven', function () {
    it('calculates total price given correctly', function () {

        $vehiclePriceGiven = 0.658;
        $distanceGiven = 10;

        $price = PriceCalculator::calculateTotalPriceGiven($vehiclePriceGiven, $distanceGiven);

        expect($price)->toBe(6.58);

    });

    it('calculates total price given as numeric string (coercion check)', function () {

        $vehiclePriceGiven = '0.6';
        $distanceGiven = '10';

        $price = PriceCalculator::calculateTotalPriceGiven($vehiclePriceGiven, $distanceGiven);

        expect($price)->toBe(6.0);
    });

    it('calculates total price given correctly with no distance provided', function () {

        $vehiclePriceGiven = 0.658;
        $distanceGiven = 0;

        $price = PriceCalculator::calculateTotalPriceGiven($vehiclePriceGiven, $distanceGiven);

        expect($price)->toBe(0.0);

    });

    it('throws an exception with the correct message for an incorrect price given', function () {

        $vehiclePriceGiven = -10;
        $distanceGiven = 10;

        expect(fn () => PriceCalculator::calculateTotalPriceGiven($vehiclePriceGiven, $distanceGiven))
            ->toThrow(InvalidArgumentException::class, 'Price cannot be negative');
    });

    it('throws an exception with the correct message for an incorrect total distance', function () {

        $vehiclePriceGiven = 10;
        $distanceGiven = -10;

        expect(fn () => PriceCalculator::calculateTotalPriceGiven($vehiclePriceGiven, $distanceGiven))
            ->toThrow(InvalidArgumentException::class, 'Distance driven cannot be negative');
    });
});
