<?php

namespace App\Http\Controllers;

use App\Models\ExpensesClaim;
use App\Models\OtherTrip;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OtherTripsController extends Controller
{
    public function index(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('otherTravel/Travel', [
            'otherTrips' => OtherTrip::where('expenses_claim_id', $expensesClaim->id)->get(),
            'expensesClaimId' => $expensesClaim->id,
        ]);
    }

    public function create(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('otherTravel/OtherTrip', [
            'otherTrip' => null,
            'expensesClaimId' => $expensesClaim->id]);
    }

    public function store(Request $request, ExpensesClaim $expensesClaim)
    {
        // validation de la data
        $validated = $request->validate([
            'expense_name' => 'required|string|min:5',
            'total_price' => 'required|decimal:0,2|min:0',
        ]
        );

        // Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée
        $validated['reimbursed_price'] = $validated['total_price'];

        OtherTrip::create([
            'expenses_claim_id' => $expensesClaim->id,
            ...$validated,
        ]);

        return (new FlowController)->enterChild('other_travel', $expensesClaim);
    }

    public function show(OtherTrip $otherTrip)
    {
        //
    }

    public function edit(OtherTrip $otherTrip)
    {
        // $claimId = session('expenses_claim_id');
        // $otherTrip = OtherTrip::where('expenses_claim_id', $claimId)->get();
    }

    public function update(Request $request, OtherTrip $otherTrip, ExpensesClaim $expensesClaim)
    {
        // $validated = $request->validate([
        //     'expense_name' => 'required|string|min:5',
        //     'total_price' => 'required|decimal:0,2|min:0',
        //     'expenses_claim_id' => ['exists:expensesClaim,id'],
        // ]);

        // Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée
        // $validated['reimbursed_price'] = $validated['total_price'];
        // $otherTrip->update($validated);
        // return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }

    public function destroy(OtherTrip $otherTrip, ExpensesClaim $expensesClaim)
    {
        // $otherTrip->delete();
        // return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }
}
