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
            'trainingExpense' => TrainingExpense::where('expenses_claim_id', $expensesClaim->id)->get(),
            'expensesClaimId' => $expensesClaim->id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ExpensesClaim $expensesClaim)
    {
        $trainingExpense = TrainingExpense::with('expenses_claim')->get();

        return Inertia::render('trainingExpense/TrainingExpense', [
            'trainingExpense' => $trainingExpense,
            'expensesClaim' => $expensesClaim]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ExpensesClaim $expensesClaim)
    {

        // validation de la data
        $validated = $request->validate([
            'nb_days_of_training' => 'required|integer|min:1',
            'total_price' => 'required|decimal:0,2|min:0',
        ]);

        $max_reimbursed = 149.10;
        // Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée
        $validated['reimbursed_price'] = min($validated['total_price'], $max_reimbursed);

        TrainingExpense::create([
            'expenses_claim_id' => $expensesClaim->id,
            ...$validated,
        ]);

        return (new FlowController)->completeStep($expensesClaim);
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingExpense $trainingExpense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrainingExpense $trainingExpense)
    {
        // return Inertia::render('trainingExpense/TrainingExpense', [
        //     'trainingExpense' => $trainingExpense,
        //     'expensesClaim' => ['exists:expensesClaim'],
        // ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingExpense $trainingExpense, ExpensesClaim $expensesClaim)
    {
        $validated = $request->validate([
            'nb_days_of_training' => 'required|integer|min:1',
            'total_price' => 'required|decimal:0,2|min:0',
        ]);

        // Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée

        $trainingExpense->update($validated);

        // Edit/update stays on the same page, no flow movement
        return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingExpense $trainingExpense, ExpensesClaim $expensesClaim)
    {
        $trainingExpense->delete();

        return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }
}
