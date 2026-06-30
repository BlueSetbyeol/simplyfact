<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\ExpensesClaim;
use App\Services\PriceCalculator;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccommodationController extends Controller
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
        return Inertia::render('accommodation/AccommodationDetails', [
            'accommodation' => null,
            'expensesClaimId' => $expensesClaim->id]);
    }

    public function store(Request $request, ExpensesClaim $expensesClaim)
    {

        // validation de la data
        $validated = $request->validate([
            'accommodation_type' => 'required|string|min:5',
            'nb_of_night' => 'required|integer|min:1',
            'total_price' => 'required|decimal:0,2|min:0',
        ]
        );

        $ceilings = [
            'Hôtel province hors coeur de ville' => 70,
            'Hôtel province coeur de ville' => 90,
            'Hôtel Lyon' => 100,
            'Hôtel Paris' => 150,
        ];

        $ceiling = $ceilings[$validated['accommodation_type']] ?? 0;

        // Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée
        $validated['reimbursed_price'] = PriceCalculator::calculateMaximumPricePerNight($validated['total_price'], $ceiling, $validated['nb_of_night']);

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
        //
    }

    public function update(Request $request, Accommodation $accommodation, ExpensesClaim $expensesClaim)
    {
        //
    }

    public function destroy(Accommodation $accommodation, ExpensesClaim $expensesClaim)
    {
        //
    }
}
