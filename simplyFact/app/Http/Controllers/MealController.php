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
        $flowSteps = session('flow_steps', []);
        $stepIndex = session('step_index', 0);

        // Pass existing meals for the current claim
        $claimId = session('expenses_claim_id');

        return Inertia::render('meal/MealForm', [
            'meals'    => Meal::where('expenses_claim_id', $claimId)->get(),
            'flowStep' => $flowSteps[$stepIndex] ?? null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $meal = Meal::with('expenses_claim');
        return view('meal', ['meal'=> $meal]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validation de la data
        $validated = $request->validate([
            'number_of_meal' =>'required|integer|min:1',
            'total_price' =>'required|float|min:0',
            'reimbursed_price' =>'float',
            // TODO reimbursed_price a recalculer dans le back
            'expenses_claim_id' => ['exists:expensesClaim,id'],
        ], [
            'number_of_meal.required' => "Merci d'ajouter le nombre de repas",
            'total_price.required' => "Merci de préciser le total du prix des repas consommés",
        ]
        );

        $validated['expenses_claim_id'] = session('expenses_claim_id');

        Meal::create($validated);

        // Go back to meal.index (the hub) via the flow
        return redirect()->with('success', "L'ajout du/des repas a bien été pris en compte")->route('flow.return-parent');
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
        return Inertia::render('meal/MealEdit', [
            'meal' => $meal,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meal $meal)
    {
        $validated = $request->validate([
            'number_of_meal' => 'required|integer|min:1',
            'total_price'  => 'required|float|min:0',
            'reimbursed_price' =>'float',
            // TODO reimbursed_price a recalculer dans le back
            'expenses_claim_id' => ['exists:expensesClaim,id'],
        ]);

        $meal->update($validated);

        // Edit/update stays on the same page, no flow movement
        return redirect()->route('meal.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        $meal->delete();

        return redirect()->route('meal.index');
    }
}
