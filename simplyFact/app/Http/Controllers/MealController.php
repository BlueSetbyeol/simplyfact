<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'number_of_meal' =>'required|float',
            'total_price' =>'required|float',
            'reimbursed_price' =>'float',
            // TODO a recalculer dans le back
            'expenses_claim_id' => ['exists:expensesClaim,id'],
        ], [
            'number_of_meal.required' => "Merci d'ajouter le nombre de repas",
            'total_price.required' => "Merci de préciser le total du prix des repas consommés",
        ]
        );

        Meal::create([
            'expenses_claim_id'=> $validated['expenses_claim_id'],
            'number_of_meal'=> $validated['number_of_meal'],
            'total_price'=> $validated['total_price'],
            'reimbursed_price'=> $validated['reimbursed_price']
        ]);

        return redirect('meal')->with('success', "L'ajout du/des repas a bien été pris en compte")->route('flow.next');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meal $meal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        //
    }
}
