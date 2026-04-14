<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $user = User::with('email_address');

        return Inertia::render('user/User', ['user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::with('email_address');

        return Inertia::render('user/User', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // data validation
        $validated = $request->validate([
            'firstname' => 'required|string|max:150|min:3',
            'lastname' => 'required|string|max:150|min:3',
            'address_street' => 'required|string|max:150|min:3',
            'address_zipcode' => 'required|numeric',
            'address_city' => 'required|string|max:150|min:3',
            'address_country' => 'required|string|max:150|min:3',
            'email_address' => 'required|string|max:250|min:3',
            'phone_number' => 'required|string|max:15|min:10',
        ]
        );

        User::create([
            'commitee_name' => $validated['commitee_name'],
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'address_street' => $validated['address_street'],
            'address_zipcode' => $validated['address_zipcode'],
            'address_city' => $validated['address_city'],
            'address_country' => $validated['address_country'],
            'email_address' => $validated['email_address'],
            'phone_number' => $validated['phone_number'],
        ]);

        // auth();

        return redirect('user')->route('expenses-claims');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with('email_address');

        return Inertia::render('user/User', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Not sure if we do authorize the modification at the end or not

        // Validate
        // $validated = $request->validate([
        // 'message' => 'required|string|max:255',
        // ]);
        // Update
        // $expenses_claim->update($validated);
        // return redirect('/')->with('success', 'Expenses Claim updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Not sure if we do authorize the deletion at the end or not, what if someone give up midway ?

        // $expenses_claim->delete();
        // return redirect('/')->with('success', 'Expenses Claim deleted!');
    }
}
