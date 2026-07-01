<?php

use App\Models\ExpensesClaim;

describe('ValidateTrainingDaysEntry', function () {

    it('rejects training days below 1', function () {

        $expensesClaim = ExpensesClaim::factory()->create();

        $response = $this->post(route('expenses-claims.training-expenses.store', $expensesClaim), [
            'nb_days_of_training' => 0,
        ]);

        $response->assertInvalid(['nb_days_of_training']);
    });

    it('rejects non-integer training days', function () {

        $expensesClaim = ExpensesClaim::factory()->create();

        $response = $this->post(route('expenses-claims.training-expenses.store', $expensesClaim), [
            'nb_days_of_training' => 'abc',
        ]);

        $response->assertInvalid(['nb_days_of_training']);
    });

});
