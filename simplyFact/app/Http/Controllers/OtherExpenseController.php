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
            'otherExpenses' => OtherExpense::where('expenses_claim_id', $expensesClaim->id)->get(),
            'expensesClaimId' => $expensesClaim->id,
        ]);
    }

    public function create(ExpensesClaim $expensesClaim)
    {
        $otherExpense = OtherExpense::with('expenses_claim')->get();

        return Inertia::render('otherExpenses/OtherExpensesDetails', [
            'otherExpense' => $otherExpense,
            'expensesClaimId' => $expensesClaim->id]);
    }

    public function store(Request $request, ExpensesClaim $expensesClaim)
    {

        // validation de la data
        $validated = $request->validate([
            'expense_name' => 'required|string|min:5',
            'total_price' => 'required|decimal:0,2|min:0',
            'reimbursed_price' => 'decimal:0,2',
            // TODO reimbursed_price a recalculer dans le back
        ]
        );

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
        $claimId = session('expenses_claim_id');
        $otherExpense = OtherExpense::where('expenses_claim_id', $claimId)->get();
    }

    public function update(Request $request, OtherExpense $otherExpense, ExpensesClaim $expensesClaim)
    {
        $validated = $request->validate([
            'expense_name' => 'required|string|min:5',
            'total_price' => 'required|decimal:0,2|min:0',
            'reimbursed_price' => 'decimal:0,2',
            // TODO reimbursed_price a recalculer dans le back
            'expenses_claim_id' => ['exists:expensesClaim,id'],
        ]);

        $otherExpense->update($validated);

        // Edit/update stays on the same page, no flow movement
        return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }

    public function destroy(OtherExpense $otherExpense, ExpensesClaim $expensesClaim)
    {
        $otherExpense->delete();

        return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }
}
