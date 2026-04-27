<?php

namespace App\Http\Controllers;

use App\Models\Accomodation;
use App\Models\ExpensesClaim;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccomodationController extends Controller
{
    public function index(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('accomodation/Accomodation', [
            'accomodations' => Accomodation::where('expenses_claim_id', $expensesClaim->id)->get(),
            'expensesClaim' => [$expensesClaim],
        ]);
    }

    public function create(ExpensesClaim $expensesClaim)
    {
        $accomodation = Accomodation::with('expenses_claim')->get();

        return Inertia::render('accomodation/Accomodation', [
            'accomodation' => $accomodation,
            'expensesClaim' => $expensesClaim]);
    }

    public function store(Request $request, ExpensesClaim $expensesClaim)
    {

        // validation de la data
        $validated = $request->validate([
            'accomodation_type' => 'required|string|min:5',
            'nb_of_night' => 'required|integer|min:1',
            'total_price' => 'required|decimal:0,2|min:0',
            'reimbursed_price' => 'decimal:0,2',
            // TODO reimbursed_price a recalculer dans le back
        ]
        );

        Accomodation::create([
            'expenses_claim_id' => $expensesClaim->id,
            ...$validated,
        ]);

        return (new FlowController)->completeStep($expensesClaim);
    }

    public function show(Accomodation $accomodation)
    {
        //
    }

    public function edit(Accomodation $accomodation)
    {
        $claimId = session('expenses_claim_id');
        $accomodations = Accomodation::where('expenses_claim_id', $claimId)->get();
    }

    public function update(Request $request, Accomodation $accomodation, ExpensesClaim $expensesClaim)
    {
        $validated = $request->validate([
            'accomodation_type' => 'required|string|min:5',
            'nb_of_night' => 'required|integer|min:1',
            'total_price' => 'required|decimal:0,2|min:0',
            'reimbursed_price' => 'decimal:0,2',
            // TODO reimbursed_price a recalculer dans le back
            'expenses_claim_id' => ['exists:expensesClaim,id'],
        ]);

        $accomodation->update($validated);

        // Edit/update stays on the same page, no flow movement
        return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }

    public function destroy(Accomodation $accomodation, ExpensesClaim $expensesClaim)
    {
        $accomodation->delete();

        return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }
}
