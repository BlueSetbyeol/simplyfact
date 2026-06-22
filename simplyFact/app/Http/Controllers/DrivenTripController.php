<?php

namespace App\Http\Controllers;

use App\Models\DrivenTrip;
use App\Models\ExpensesClaim;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DrivenTripController extends Controller
{
    public function index(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('drivenTravel/Travel', [
            'drivenTrips' => $expensesClaim->drivenTrips,
            'expensesClaimId' => $expensesClaim->id,
        ]);
    }

    public function create(ExpensesClaim $expensesClaim)
    {
        $vehicle = Vehicle::findOrFail(session('vehicle_id'));

        return Inertia::render('drivenTravel/DrivenTrip', [
            'drivenTrip' => null,
            'vehicle' => $vehicle,
            'expensesClaimId' => $expensesClaim->id]);
    }

    public function store(Request $request, ExpensesClaim $expensesClaim)
    {

        // validation de la data
        $validated = $request->validate([
            'starting_city' => 'required|string|max:150|min:4',
            'starting_zip_code' => 'required|string|max:6|min:5',
            'ending_city' => 'required|string|max:150|min:4',
            'ending_zip_code' => 'required|string|max:6|min:5',
            'trip_type' => 'string|max:255|min:4',
            'total_distance' => 'required|integer|min:1',
            'total_distance_given' => 'nullable|integer',
            'description' => 'nullable|string|max:255|min:5',
        ]
        );

        $vehicleID = session('vehicle_id');
        $vehicle = Vehicle::findOrFail($vehicleID);

        $rate = match (true) {
            in_array($validated['trip_type'], ['Covoiturage, remorque, salarié...', 'Déplacements pendant stage fédéral']) => 0.4,
            $validated['trip_type'] === 'voiture' => 0.36,
            default => 0.14,
        };

        $validated['total_price'] = round($rate * ($validated['total_distance'] - ($validated['total_distance_given'] ?? 0)), 2);

        $validated['total_price_given'] = round($vehicle->price_given * ($validated['total_distance_given'] ?? 0), 2);

        $validated['reimbursed_price'] = $validated['total_price'];

        DrivenTrip::create([
            'expenses_claim_id' => $expensesClaim->id,
            'vehicle_id' => $vehicleID,
            ...$validated,
        ]);

        return (new FlowController)->enterChild('driven_travel', $expensesClaim);
    }

    public function show(DrivenTrip $drivenTrip)
    {
        //
    }

    public function edit(DrivenTrip $drivenTrip)
    {
        // $claimId = session('expenses_claim_id');
        // $drivenTrip = DrivenTrip::where('expenses_claim_id', $claimId)->get();

        // $vehicleID = session('vehicle_id');
        // $vehicle = Vehicle::find($vehicleID);

        // return Inertia::render('drivenTravel/DrivenTrip', [
        //     'drivenTrip' => null,
        //     'vehicle' => $vehicle,
        //     'expensesClaimId' => $expensesClaim->id]);
    }

    public function update(Request $request, DrivenTrip $drivenTrip, ExpensesClaim $expensesClaim)
    {
        // $validated = $request->validate([
        // 'starting_city' => 'required|string|max:150|min:5',
        // 'strating_zip_code' => 'required|numeric',
        // 'ending_city' => 'required|string|max:150|min:5',
        // 'ending_zip_code' => 'required|numeric',
        // 'trip_type' => 'string|max:255|min:5',
        // 'total_distance' => 'required|integer|min:1',
        // 'total_price' => 'required|decimal:0,2|min:0',
        // 'total_distance_given' => 'required|integer|min:1',
        // 'total_price_given' => 'required|decimal:0,2|min:0',
        // 'description' => 'required|string|max:255|min:5',
        // ]);

        // Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée
        // $validated['reimbursed_price'] = $validated['total_price'];

        // $drivenTrip->update($validated);

        // Edit/update stays on the same page, no flow movement
        // return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }

    public function destroy(DrivenTrip $drivenTrip, ExpensesClaim $expensesClaim)
    {
        $drivenTrip->delete();

        return redirect()->route('expenses-claims.flow.return-parent', $expensesClaim);
    }
}
