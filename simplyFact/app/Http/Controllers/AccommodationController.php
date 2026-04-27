<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\ExpensesClaim;
use Illuminate\Http\Request;
use Inertia\Inertia;

class accommodationController extends Controller
{
    public function index(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('accommodation/Accommodation', [
            'accommodations' => Accommodation::where('expenses_claim_id', $expensesClaim->id)->get(),
            'expensesClaimId' => $expensesClaim->id,
        ]);
    }

    public function create(ExpensesClaim $expensesClaim)
    {
        $accommodation = Accommodation::with('expenses_claim')->get();

        return Inertia::render('accommodation/AccommodationDetails', [
            'accommodation' => $accommodation,
            'expensesClaimId' => $expensesClaim->id]);
    }

    public function store(Request $request, ExpensesClaim $expensesClaim)
    {

        // validation de la data
        $validated = $request->validate([
            'accommodation_type' => 'required|string|min:5',
            'nb_of_night' => 'required|integer|min:1',
            'total_price' => 'required|decimal:0,2|min:0',
            'reimbursed_price' => 'decimal:0,2',
            // TODO reimbursed_price a recalculer dans le back
        ]
        );

        Accommodation::create([
            'expenses_claim_id' => $expensesClaim->id,
            ...$validated,
        ]);

        return (new FlowController)->enterChild('accommodation', $expensesClaim);
    }

    public function show(Accommodation $accommodation)
    {
        //
    }

    public function edit(Accommodation $accommodation)
    {
        $claimId = session('expenses_claim_id');
        $accommodations = Accommodation::where('expenses_claim_id', $claimId)->get();
    }

    public function update(Request $request, Accommodation $accommodation, ExpensesClaim $expensesClaim)
    {
        $validated = $request->validate([
            'accommodation_type' => 'required|string|min:5',
            'nb_of_night' => 'required|integer|min:1',
            'total_price' => 'required|decimal:0,2|min:0',
            'reimbursed_price' => 'decimal:0,2',
            // TODO reimbursed_price a recalculer dans le back
            'expenses_claim_id' => ['exists:expensesClaim,id'],
        ]);

        $accommodation->update($validated);

        // Edit/update stays on the same page, no flow movement
        return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }

    public function destroy(Accommodation $accommodation, ExpensesClaim $expensesClaim)
    {
        $accommodation->delete();

        return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }
}
