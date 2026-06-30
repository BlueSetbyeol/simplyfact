<?php

namespace App\Http\Controllers;

use App\Models\ExpensesClaim;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleController extends Controller
{
    public function index(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('drivenTravel/Vehicle', [
            'vehicle' => Vehicle::where('user_id', session('user_id'))->latest()->get(),
            'expensesClaimId' => $expensesClaim->id,
        ]);
    }

    public function create(ExpensesClaim $expensesClaim, Request $request)
    {
        $userId = session('user_id');
        $vehicle = Vehicle::where('user_id', $userId)->latest()->first();

        return Inertia::render('drivenTravel/Vehicle', [
            'vehicle' => $vehicle,
            'expensesClaimId' => $expensesClaim->id,
        ]);
    }

    public function store(Request $request, ExpensesClaim $expensesClaim)
    {
        $userId = session('user_id');

        if (! $userId) {
            return redirect()->route('users.create');
        }

        // data validation
        $validated = $request->validate([
            'vehicle_type' => 'required|in:voiture,moto',
            'electrical' => 'required|boolean',
            'power' => 'required|string|max:150|min:3',
            'price_given' => 'required|decimal:0,3|min:0',
            'number_plate' => 'required|string|max:150|min:8',
        ]
        );

        $vehicle = Vehicle::create([
            'user_id' => $userId,
            ...$validated,
        ]);

        session(['vehicle_id' => $vehicle->id]);

        return (new FlowController)->enterChild('driven_trip', $expensesClaim);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Vehicle $vehicle, Request $request)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
