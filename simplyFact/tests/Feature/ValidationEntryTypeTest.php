<?php

describe('ValidateTrainingDaysEntry', function () {

    it('rejects training days below 1', function () {
        $response = $this->post('expenses-claims.training-expenses.create', [
            'nb_days_of_training' => 0,
        ]);

        $response->assertInvalid(['nb_days_of_training']);
    });

    it('rejects non-integer training days', function () {
        $response = $this->post('expenses-claims.training-expenses.create', [
            'nb_days_of_training' => 'abc',
        ]);

        $response->assertInvalid(['nb_days_of_training']);
    });

});
