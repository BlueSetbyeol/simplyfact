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
            'drivenTrips' => DrivenTrip::where('expenses_claim_id', $expensesClaim->id)->get(),
            'expensesClaimId' => $expensesClaim->id,
        ]);
    }

    public function create(ExpensesClaim $expensesClaim)
    {
        $drivenTrip = DrivenTrip::with('expenses_claim')->get();

        $vehicleID = session('vehicle_id');
        $vehicle = Vehicle::find($vehicleID);

        return Inertia::render('drivenTravel/DrivenTrip', [
            'drivenTrip' => $drivenTrip,
            'vehicle' => $vehicle,
            'expensesClaimId' => $expensesClaim->id]);
    }

    public function store(Request $request, ExpensesClaim $expensesClaim)
    {

        // validation de la data
        $validated = $request->validate([
            'starting_city' => 'required|string|max:150|min:5',
            'strating_zip_code' => 'required|numeric',
            'ending_city' => 'required|string|max:150|min:5',
            'ending_zip_code' => 'required|numeric',
            'trip_type' => 'string|max:255|min:5',
            'total_distance' => 'required|integer|min:1',
            'total_price' => 'required|decimal:0,2|min:0',
            'total_distance_given' => 'required|integer|min:1',
            'total_price_given' => 'required|decimal:0,2|min:0',
            'description' => 'required|string|max:255|min:5',
        ]
        );

        // Calcule de reimbursed_price pour s'assurer que la règle de remboursement est respectée
        $validated['reimbursed_price'] = ($validated['total_price'] - $validated['total_price_given']);

        $vehicle = session('vehicle_id');

        DrivenTrip::create([
            'expenses_claim_id' => $expensesClaim->id,
            'vehicle_id' => $vehicle->id,
            ...$validated,
        ]);

        return (new FlowController)->enterChild('driven_trip', $expensesClaim);
    }

    public function show(DrivenTrip $drivenTrip)
    {
        //
    }

    public function edit(DrivenTrip $drivenTrip)
    {
        // $claimId = session('expenses_claim_id');
        // $drivenTrip = DrivenTrip::where('expenses_claim_id', $claimId)->get();

        // return Inertia::render('drivenTravel/DrivenTravel', [
        //     'vehicle' => $vehicle,
        //     'user' => $user,
        // ]);
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
