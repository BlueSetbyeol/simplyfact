<?php

namespace App\Http\Controllers;

use App\Models\ExpensesClaim;
use App\Services\ExpenseClaimPdfService;
use App\Services\PdfGenerator;
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
        return Inertia::render('claim/Informations', [
            'expensesClaim' => ExpensesClaim::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return Inertia::render('claim/Informations');
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
        $claim = ExpensesClaim::with(['meals'])->findOrFail($expensesClaim->id);

        // TODO Il va falloir créer une autre page pour présenter la claim dans son ensemble en fonction de là où on en est.

        // return Inertia::render('claim/ClaimSummary', [
        //     'expensesClaim' => $claim,
        // ]);
        return Inertia::render('claim/Informations', [
            'expensesClaim' => $expensesClaim,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpensesClaim $expensesClaim, Request $request)
    {
        // Not sure if we do authorize the modification at the end or not

        // ajout des valeurs de fin de note de frais
        $validated = $request->validate([
            'total_reimbursed' => 'decimal:0,2',
            'total_given' => 'nullable|decimal:0,2',
        ]);

        $expensesClaim->update($validated);

        // Génère PDF et envoie par email
        try {
            $service = new ExpenseClaimPdfService(new PdfGenerator);
            $service->generateAndSend($expensesClaim->id);
        } catch (\Exception $e) {
            return Inertia::flash('error', $e->getMessage())->back();
        }

        return redirect()->route('expenses-claims.flow.done', $expensesClaim);
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
