<?php

namespace App\Http\Controllers;

use App\Models\ExpensesClaim;
use App\Models\Meal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ExpensesClaim $expensesClaim)
    {
        $claimId = session('expenses_claim_id');

        return Inertia::render('meal/MealForm', [
            'meals' => Meal::where('expenses_claim_id', $claimId)->get(),
            'expensesClaim' => [$expensesClaim],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ExpensesClaim $expensesClaim)
    {
        $meal = Meal::with('expenses_claim')->get();

        return Inertia::render('meal/MealForm', [
            'meal' => $meal,
            'expensesClaim' => $expensesClaim]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ExpensesClaim $expensesClaim)
    {

        // validation de la data
        $validated = $request->validate([
            'number_of_meal' => 'required|integer|min:1',
            'total_price' => 'required|decimal:0,2|min:0',
            'reimbursed_price' => 'decimal:0,2',
            // TODO reimbursed_price a recalculer dans le back
        ]
        );

        Meal::create([
            'expenses_claim_id' => $expensesClaim->id,
            ...$validated,
        ]);

        return redirect('')->route('expenses-claims.flow.complete-step', $expensesClaim);
    }

    /**
     * Display the specified resource.
     */
    public function show(Meal $meal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meal $meal)
    {
        // return Inertia::render('meal/MealForm', [
        //     'meal' => $meal,
        //     'expensesClaim' => ['exists:expensesClaim'],
        // ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meal $meal, ExpensesClaim $expensesClaim)
    {
        $validated = $request->validate([
            'number_of_meal' => 'required|integer|min:1',
            'total_price' => 'required|float|min:0',
            'reimbursed_price' => 'float',
            // TODO reimbursed_price a recalculer dans le back
            'expenses_claim_id' => ['exists:expensesClaim,id'],
        ]);

        $meal->update($validated);

        // Edit/update stays on the same page, no flow movement
        return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal, ExpensesClaim $expensesClaim)
    {
        $meal->delete();

        return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }
}
