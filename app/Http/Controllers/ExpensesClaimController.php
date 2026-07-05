<?php

namespace App\Http\Controllers;

use App\Models\ExpensesClaim;
use App\Services\ExpenseClaimPdfService;
use App\Services\PdfGenerator;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpensesClaimController extends Controller
{
    public function index()
    {
        return Inertia::render('claim/Informations', [
            'expensesClaim' => ExpensesClaim::latest('created_at')->get(),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('claim/Informations');
    }

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

    public function show(string $id)
    {
        //
    }

    public function edit(ExpensesClaim $expensesClaim)
    {
        // TODO Il va falloir créer une autre page pour présenter la claim dans son ensemble en fonction de là où on en est.
    }

    public function update(ExpensesClaim $expensesClaim, Request $request)
    {
        // ajout des valeurs de fin de note de frais
        $validated = $request->validate([
            'total_reimbursed' => 'decimal:0,2',
            'total_given' => 'nullable|decimal:0,2',
        ]);

        $expensesClaim->update($validated);

        // Génère PDF et envoie par email
        // try {
        $service = new ExpenseClaimPdfService(new PdfGenerator);
        $service->generateAndSend($expensesClaim->id);
        // } catch (\Exception $e) {
        //     return Inertia::flash('error', $e->getMessage())->back();
        // }

        return redirect()->route('expenses-claims.flow.done', $expensesClaim);
    }

    public function destroy(string $id)
    {
        // Not sure if we do authorize the deletion at the end or not, what if someone give up midway ?
    }
}
