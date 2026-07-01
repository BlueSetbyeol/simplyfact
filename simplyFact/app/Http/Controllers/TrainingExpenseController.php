<?php

namespace App\Http\Controllers;

use App\Models\ExpensesClaim;
use App\Models\TrainingExpense;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrainingExpenseController extends Controller
{
    public function index(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('trainingExpense/TrainingExpense', [
            'trainingExpense' => $expensesClaim->trainingExpenses,
            'expensesClaimId' => $expensesClaim->id,
        ]);
    }

    public function create(ExpensesClaim $expensesClaim)
    {
        $trainingExpense = TrainingExpense::with('expenses_claim')->get();

        return Inertia::render('trainingExpense/TrainingExpense', [
            'trainingExpense' => $trainingExpense,
            'expensesClaim' => $expensesClaim]);
    }

    public function store(Request $request, ExpensesClaim $expensesClaim)
    {

        // validation de la data
        $validated = $request->validate([
            'nb_days_of_training' => 'required|integer|min:1',
        ]);

        $price_per_day = 21.30;
        $max_reimbursed = 149.10;
        // Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée
        $validated['reimbursed_price'] = min($validated['nb_days_of_training'] * $price_per_day, $max_reimbursed);

        TrainingExpense::create([
            'expenses_claim_id' => $expensesClaim->id,
            ...$validated,
        ]);

        return (new FlowController)->completeStep($expensesClaim);
    }

    public function show(TrainingExpense $trainingExpense)
    {
        //
    }

    public function edit(TrainingExpense $trainingExpense)
    {
        // return Inertia::render('trainingExpense/TrainingExpense', [
        //     'trainingExpense' => $trainingExpense,
        //     'expensesClaim' => ['exists:expensesClaim'],
        // ]);
    }

    public function update(Request $request, TrainingExpense $trainingExpense, ExpensesClaim $expensesClaim)
    {
        // validation de la data
        // $validated = $request->validate([
        //     'nb_days_of_training' => 'required|integer|min:1',
        // ]);

        // $price_per_day = 21.30;
        // $max_reimbursed = 149.10;
        // Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée
        // $validated['reimbursed_price'] = min($validated['nb_days_of_training'] * $price_per_day, $max_reimbursed);

        // $trainingExpense->update($validated);

        // return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }

    public function destroy(TrainingExpense $trainingExpense, ExpensesClaim $expensesClaim)
    {
        $trainingExpense->delete();

        return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }
}
