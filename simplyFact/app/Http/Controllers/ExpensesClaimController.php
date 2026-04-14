<?php

namespace App\Http\Controllers;

use App\Models\ExpensesClaim;
use Illuminate\Http\Request;
use Inertia\Inertia;

// use Inertia\Inertia;

class ExpensesClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $expenses_claim = Expenses_claim::all();
        return Inertia::render('expensesClaim/ExpensesClaimForm', [
            'expensesClaim' => ExpensesClaim::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $expenses_claim = ExpensesClaim::with('user')->get();

        return Inertia::render('expenses-claims', ['expenses_claim' => $expenses_claim]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // data validation
        $validated = $request->validate([
            'committee_name' => 'required|string|max:150|min:3',
            'action_name' => 'required|string|max:255|min:5',
            'action_dates' => 'required|string|max:255|min:8',
            'total_given' => 'nullable|numeric',
            'total_reimbursed' => 'nullable|numeric',
        ], [
            'committee_name.required' => "Merci d'ajouter le nom de votre Commission",
            'commitee_name.min' => 'Le nom doit obligatoirement avoir 3 caractères minimum',
            'action_name.required' => "Merci d'indiquer le sujet de votre Note de Frais",
            'action_name.min' => "Le nom de l'action doit obligatoirement avoir 5 caractères minimum",
            'action_dates.required' => "Merci d'indiquer les dates auxquels ont eu lieu votre action",
            'action_dates.min' => "La date de l'action doit obligatoirement avoir 8 caractères minimum",
        ]
        );

        ExpensesClaim::create([
            'user_id' => null,
            'committee_name' => $validated['committee_name'],
            'action_name' => $validated['action_name'],
            'action_dates' => $validated['action_dates'],
            'total_given' => $validated['total_given'],
            'total_reimbursed' => $validated['total_reimbursed'],

        ]);

        return redirect('expenses_claim')->route('flow.start');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('expensesClaim/Edit', [
            'expensesClaim' => $expensesClaim,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Not sure if we do authorize the modification at the end or not

        // Validate
        // $validated = $request->validate([
        // 'message' => 'required|string|max:255',
        // ]);
        // Update
        // $expenses_claim->update($validated);
        // return redirect('/')->with('success', 'Expenses Claim updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Not sure if we do authorize the deletion at the end or not, what if someone give up midway ?

        // $expenses_claim->delete();
        // return redirect('/')->with('success', 'Expenses Claim deleted!');
    }
}
