<?php

namespace App\Http\Controllers;

use App\Models\ExpensesClaim;
use App\Models\OtherExpense;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OtherExpenseController extends Controller
{
    public function index(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('otherExpenses/OtherExpenses', [
            'otherExpenses' => $expensesClaim->otherExpenses,
            'expensesClaimId' => $expensesClaim->id,
        ]);
    }

    public function create(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('otherExpenses/OtherExpensesDetails', [
            'otherExpense' => null,
            'expensesClaimId' => $expensesClaim->id]);
    }

    public function store(Request $request, ExpensesClaim $expensesClaim)
    {
        // validation de la data
        $validated = $request->validate([
            'expense_name' => 'required|string|min:5',
            'total_price' => 'required|decimal:0,2|min:0',
        ]
        );

        // Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée
        $validated['reimbursed_price'] = $validated['total_price'];

        OtherExpense::create([
            'expenses_claim_id' => $expensesClaim->id,
            ...$validated,
        ]);

        return (new FlowController)->enterChild('other_expenses', $expensesClaim);
    }

    public function show(OtherExpense $otherExpense)
    {
        //
    }

    public function edit(OtherExpense $otherExpense)
    {
        //
    }

    public function update(Request $request, OtherExpense $otherExpense, ExpensesClaim $expensesClaim)
    {
        //
    }

    public function destroy(OtherExpense $otherExpense, ExpensesClaim $expensesClaim)
    {
        //
    }
}
