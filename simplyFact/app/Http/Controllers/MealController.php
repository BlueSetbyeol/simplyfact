<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pass existing meals for the current claim => flow
        // $flowSteps = session('flow_steps', []);
        // $stepIndex = session('step_index', 0);
        // $claimId = session('expenses_claim_id');
        // 'meals'    => Meal::where('expenses_claim_id', $claimId)->get(),
        // 'flowStep' => $flowSteps[$stepIndex] ?? null,

        $meal = Meal::with('expenses_claim');

        return Inertia::render('meal/MealForm', [
            'meal' => $meal,
            'expensesClaim' => ['exists:expensesClaim'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $meal = Meal::with('expenses_claim');

        return Inertia::render('meal/MealForm', [
            'meal' => $meal,
            'expensesClaim' => ['exists:expensesClaim']]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation de la data
        $validated = $request->validate([
            'number_of_meal' => 'required|integer|min:1',
            'total_price' => 'required|float|min:0',
            'reimbursed_price' => 'float',
            // TODO reimbursed_price a recalculer dans le back
            'expenses_claim_id' => ['exists:expensesClaim.id'],
        ], [
            'number_of_meal.required' => "Merci d'ajouter le nombre de repas",
            'total_price.required' => 'Merci de préciser le total du prix des repas consommés',
        ]
        );

        $validated['expenses_claim_id'] = session('expenses_claim_id');

        Meal::create($validated);

        // Go back to meal.index (the hub) via the flow
        return redirect('meals')->route('flow.return-parent');
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
        return Inertia::render('meal/MealForm', [
            'meal' => $meal,
            'expensesClaim' => ['exists:expensesClaim'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meal $meal)
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
        return redirect()->route('flow.return-parent');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        $meal->delete();

        return redirect()->route('flow.return-parent');
    }
}
