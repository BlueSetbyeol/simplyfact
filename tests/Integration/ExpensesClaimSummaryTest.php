<?php

use App\Models\ExpensesClaim;
use App\Models\Meal;
use App\Models\OtherTrip;
use App\Models\User;
use App\Services\PriceCalculator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Mail::fake();
    Storage::fake();
    $this->user = User::factory()->create();
});

describe('expenses claims', function () {

    it('renders summary of a claim', function () {
        $expensesClaim = ExpensesClaim::factory()->create();

        $response = $this->get(route('expenses-claims.flow.checking-claims', $expensesClaim));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('claim/ClaimSummary')
            ->has('expensesClaim')
        );
    });

    it('updates the claim and redirects to done when reimbursed total matches the calculation', function () {
        $expensesClaim = ExpensesClaim::factory()
            ->has(OtherTrip::factory()->count(3), 'otherTrips')
            ->has(Meal::factory()->count(1), 'meals')
            ->create();

        $claim = ExpensesClaim::with([
            'drivenTrips', 'otherTrips', 'accommodations', 'meals', 'trainingExpenses', 'otherExpenses',
        ])->findOrFail($expensesClaim->id);

        $totalGiven = 500;
        $expectedReimbursed = PriceCalculator::calculateTotalPriceAndTotalReimbursed($claim, $totalGiven);

        $formattedTotalGiven = number_format($totalGiven, 2, '.', '');
        $formattedReimbursed = number_format($expectedReimbursed, 2, '.', '');

        $response = $this->put(route('expenses-claims.update', $expensesClaim), [
            'total_given' => $formattedTotalGiven,
            'total_reimbursed' => $formattedReimbursed,
        ]);

        $response->assertRedirect(route('expenses-claims.flow.done', $expensesClaim));

        $this->assertDatabaseHas('expenses_claims', [
            'id' => $expensesClaim->id,
            'total_reimbursed' => $formattedReimbursed,
        ]);
    });

    it('does not update the claim and flashes an error when totals do not match', function () {
        $expensesClaim = ExpensesClaim::factory()
            ->has(OtherTrip::factory()->count(3), 'otherTrips')
            ->has(Meal::factory(), 'meals')
            ->create();

        $response = $this->put(route('expenses-claims.update', $expensesClaim), [
            'total_given' => 500,
            'total_reimbursed' => 999999,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('expenses_claims', [
            'id' => $expensesClaim->id,
            'total_reimbursed' => 999999,
        ]);
    });

    it('requires total_reimbursed to be a valid decimal', function () {
        $expensesClaim = ExpensesClaim::factory()->create();

        $response = $this->put(route('expenses-claims.update', $expensesClaim), [
            'total_given' => 500,
            'total_reimbursed' => 'not-a-number',
        ]);

        $response->assertSessionHasErrors('total_reimbursed');
    });

});
