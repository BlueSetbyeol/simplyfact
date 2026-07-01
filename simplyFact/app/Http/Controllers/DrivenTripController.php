<?php

namespace App\Http\Controllers;

use App\Models\DrivenTrip;
use App\Models\ExpensesClaim;
use App\Models\Vehicle;
use App\Services\PriceCalculator;
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
            'starting_zip_code' => 'required|string|max:6|min:5|regex:/^[0-9]+$/',
            'ending_city' => 'required|string|max:150|min:4',
            'ending_zip_code' => 'required|string|max:6|min:5|regex:/^[0-9]+$/',
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

        $validated['total_price'] = PriceCalculator::calculateTotalPrice(
            $rate,
            $validated['total_distance'],
            $validated['total_distance_given'] ?? 0
        );

        $validated['total_price_given'] = PriceCalculator::calculateTotalPriceGiven($vehicle->price_given, $validated['total_distance_given'] ?? 0);

        $validated['reimbursed_price'] = $validated['total_price'];

        DrivenTrip::create([
            'expenses_claim_id' => $expensesClaim->id,
            'vehicle_id' => $vehicleID,
            ...$validated,
        ]);

        return app(FlowController::class)->enterChild('driven_travel', $expensesClaim);
    }

    public function show(DrivenTrip $drivenTrip)
    {
        //
    }

    public function edit(DrivenTrip $drivenTrip)
    {
        //
    }

    public function update(Request $request, DrivenTrip $drivenTrip, ExpensesClaim $expensesClaim)
    {
        //
    }

    public function destroy(DrivenTrip $drivenTrip, ExpensesClaim $expensesClaim)
    {
        //
    }
}
