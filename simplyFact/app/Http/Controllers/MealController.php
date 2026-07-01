<?php

namespace App\Http\Controllers;

use App\Models\ExpensesClaim;
use App\Models\Meal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MealController extends Controller
{
    public function index(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('meal/MealForm', [
            'meal' => null,
            'expensesClaimId' => $expensesClaim->id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('meal/MealForm', [
            'meal' => null,
            'expensesClaim' => $expensesClaim]);
    }

    public function store(Request $request, ExpensesClaim $expensesClaim)
    {
        // validation de la data
        $validated = $request->validate([
            'number_of_meal' => 'required|integer|min:1',
            'total_price' => 'required|decimal:0,2|min:0',
        ]);

        // Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée
        $max_price_per_day = 25;
        $validated['reimbursed_price'] = min($validated['total_price'], $max_price_per_day * $validated['number_of_meal']);

        Meal::create([
            'expenses_claim_id' => $expensesClaim->id,
            ...$validated,
        ]);

        return (new FlowController)->completeStep($expensesClaim);
    }

    public function show(Meal $meal)
    {
        //
    }

    public function edit(Meal $meal)
    {
        // Meal::where('expenses_claim_id', $expensesClaim->id)->get()
        // return Inertia::render('meal/MealForm', [
        //     'meal' => $meal,
        //     'expensesClaim' => ['exists:expensesClaim'],
        // ]);
    }

    public function update(Request $request, Meal $meal, ExpensesClaim $expensesClaim)
    {
        // $validated = $request->validate([
        //     'number_of_meal' => 'required|integer|min:1',
        //     'total_price' => 'required|decimal:0,2|min:0',
        // ]);
        // Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée
        // $validated['reimbursed_price'] = min($validated['total_price'], 25 * $validated['number_of_meal']);
        // $meal->update($validated);
        // return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }

    public function destroy(Meal $meal, ExpensesClaim $expensesClaim)
    {
        // $meal->delete();
        // return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }
}
