<?php

use App\Models\ExpensesClaim;

describe('choices', function () {

    it('renders the choices page with the expensesClaim', function () {
        $expensesClaim = ExpensesClaim::factory()->create();

        $response = $this->get(route('expenses-claims.flow.choices', $expensesClaim));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('choices/Choices')
            ->has('expensesClaim')
        );
    });

});

describe('saveChoices', function () {

    it('stores only valid steps in session and redirects to summary', function () {
        $expensesClaim = ExpensesClaim::factory()->create();

        $response = $this->post(route('expenses-claims.flow.choices.save', $expensesClaim), [
            'steps' => ['driven_travel', 'meal'],
        ]);

        $response->assertRedirect(route('expenses-claims.flow.summary', $expensesClaim));
        expect(session('pending_steps'))->toBe(['driven_travel', 'meal']);
        expect(session('expensesClaimId'))->toBe((string) $expensesClaim->id);
    });

    it('strips out invalid step names', function () {
        $expensesClaim = ExpensesClaim::factory()->create();

        $this->post(route('expenses-claims.flow.choices.save', $expensesClaim), [
            'steps' => ['driven_travel', 'hacked_step', 'fake_entry'],
        ]);

        expect(session('pending_steps'))->toBe(['driven_travel']);
    });

    it('stores an empty array when no valid steps are selected', function () {
        $expensesClaim = ExpensesClaim::factory()->create();

        $this->post(route('expenses-claims.flow.choices.save', $expensesClaim), [
            'steps' => ['fake_step', 'another_invalid'],
        ]);

        expect(session('pending_steps'))->toBe([]);
    });

    it('stores an empty array when no steps are submitted', function () {
        $expensesClaim = ExpensesClaim::factory()->create();

        $this->post(route('expenses-claims.flow.choices.save', $expensesClaim), []);

        expect(session('pending_steps'))->toBe([]);
    });

});
