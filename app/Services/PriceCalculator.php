<?php

namespace App\Services;

use App\Models\ExpensesClaim;
use Pest\Exceptions\InvalidArgumentException;

class PriceCalculator
{
    public static function calculateTotalPrice(float $rate, float $totalDistance, float $distanceGiven = 0): float
    {
        if ($distanceGiven < 0) {
            throw new InvalidArgumentException('Distance driven cannot be negative');
        }

        if ($totalDistance < 0) {
            throw new InvalidArgumentException('Distance driven cannot be negative');
        }

        return round($rate * ($totalDistance - $distanceGiven), 2);
    }

    public static function calculateTotalPriceGiven(float $vehiclePriceGiven, float $distanceGiven = 0): float
    {
        if ($vehiclePriceGiven < 0) {
            throw new InvalidArgumentException('Price cannot be negative');
        }

        if ($distanceGiven < 0) {
            throw new InvalidArgumentException('Distance driven cannot be negative');
        }

        return round($vehiclePriceGiven * $distanceGiven, 2);
    }

    public static function calculateMaximumPricePerNight(float $totalPricePaid, float $ceiling, float $numberOfNight): float
    {
        if ($totalPricePaid < 0) {
            throw new InvalidArgumentException('Price cannot be negative');
        }

        return round(min($totalPricePaid, $ceiling * $numberOfNight), 2);
    }

    public static function calculateMaximumPricePerMeal(float $totalPricePaid, float $maxPricePerDay, float $numberOfMeal): float
    {
        if ($totalPricePaid < 0) {
            throw new InvalidArgumentException('Price cannot be negative');
        }

        return round(min($totalPricePaid, $maxPricePerDay * $numberOfMeal), 2);
    }

    public static function calculateMaximumPricePerTrainingPeriod(float $numberOfTrainingDays, float $pricePerDay, float $maxReimbursed): float
    {

        return round(min($numberOfTrainingDays * $pricePerDay, $maxReimbursed), 2);
    }

    public static function calculateTotalPriceAndTotalReimbursed(ExpensesClaim $claim, float $totalGiven): float
    {
        $hasManyRelations = [
            'drivenTrips', 'otherTrips', 'accommodations', 'otherExpenses',
        ];

        $hasOneRelations = [
            'meals', 'trainingExpenses',
        ];

        $totalFromClaim = 0;

        foreach ($hasManyRelations as $relation) {
            $related = $claim->{$relation};

            if ($related === null) {
                continue;
            }

            $totalFromClaim += $related->sum('total_price');
        }

        foreach ($hasOneRelations as $relation) {
            $related = $claim->{$relation};

            if ($related === null) {
                continue;
            }

            $totalFromClaim += $related->total_price;
        }

        return round(($totalFromClaim - $totalGiven), 2);
    }
}
