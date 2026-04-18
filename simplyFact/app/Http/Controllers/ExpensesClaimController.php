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
        return Inertia::render('user/Informations', [
            'expensesClaim' => ExpensesClaim::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return Inertia::render('user/Informations');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = session('user_id');

        if (! $userId) {
            return redirect()->route('users.create');
        }

        // data validation
        $validated = $request->validate([
            'committee_name' => 'required|string|max:150|min:3',
            'action_name' => 'required|string|max:255|min:5',
            'action_dates' => 'required|string|max:255|min:8',
        ]
        );

        $expensesClaim = ExpensesClaim::create([
            'user_id' => $userId,
            ...$validated,
        ]);

        return redirect()->route('expenses-claims.flow.choices', $expensesClaim);
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
        // TODO Il va falloir créer une autre page pour présenter la claim dans son ensemble en fonction de là où on en est.
        return Inertia::render('user/Informations', [
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
