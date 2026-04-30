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
            'vehicle' => Vehicle::latest()->get(),
            'expensesClaimId' => $expensesClaim->id,
        ]);
    }

    public function create(ExpensesClaim $expensesClaim, Request $request)
    {
        // TO REMOVE: Vehicle n'est plus lié à expensesClaim,
        // remplacé par récupération faite par le user_id en session.
        // $vehicle = Vehicle::with('expenses_claim')->get();

        $userId = session('user_id');
        $user = User::find($userId);

        $vehicle = $user
            ? Vehicle::where('user_id', $userId)->latest()->first()
            : null;

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // $vehicle = Vehicle::with(['users'])->findOrFail($user->id);

        // TODO Il va falloir créer une autre page pour présenter la claim dans son ensemble en fonction de là où on en est.

        // return Inertia::render('drivenTravel/Vehicle', [
        //     'vehicle' => $vehicle,
        //     'user' => $user,
        // ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Vehicle $vehicle, Request $request)
    {
        // Not sure if we do authorize the modification at the end or not

        // ajout des valeurs de fin de note de frais
        // $validated = $request->validate([
        //     'vehicle_type' => 'required|enum',
        //     'electrical' => 'required|boolean',
        //     'power' => 'required|string|max:150|min:3',
        //     'price_given' => 'required|decimal:0,2|min:0',
        //     'number_plate' => 'required|string|max:150|min:8',
        // ]);

        // $vehicle->update($validated);

        // return redirect()->route('vehicle', $vehicle);
    }

    public function destroy(string $id)
    {
        // Not sure if we do authorize the deletion at the end or not, what if someone give up midway ?

        // $vehicle->delete();
        // return redirect('/')->with('success', 'Vehicle deleted!');
    }
}
