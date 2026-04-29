<?php

namespace App\Http\Controllers;

use App\Models\ExpensesClaim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FlowController extends Controller
{
    // Montre la page de choix
    public function choices(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('choices/Choices', [
            'expensesClaim' => $expensesClaim,
        ]);
    }

    // Récupère les choix de l'utilisateur et renvoie vers la page suivante qui résume les choix
    public function saveChoices(Request $request, ExpensesClaim $expensesClaim)
    {
        $selected = $request->input('steps', []);

        $valid = ['driven_travel', 'other_travel', 'accommodation', 'meal', 'training', 'other_expenses'];
        $selected = array_values(array_intersect($selected, $valid));

        session(['pending_steps' => $selected]);

        return redirect()->route('expenses-claims.flow.summary', $expensesClaim);
    }

    public function summary(ExpensesClaim $expensesClaim)
    {
        $selectedSteps = session('pending_steps');

        if (! $selectedSteps) {
            return redirect()->route('expenses-claims.flow.choices', $expensesClaim);
        }

        return Inertia::render('choices/SumChoices', [
            'expensesClaim' => $expensesClaim,
            'steps' => $selectedSteps,
        ]);
    }

    public function start(ExpensesClaim $expensesClaim)
    {
        $requested = session('pending_steps', []);

        $definitions = [
            'driven_travel' => [
                ['name' => 'vehicle',      'done' => false],
                ['name' => 'driven_trip',  'done' => false],
            ],
            'other_travel' => [
                ['name' => 'other_trip',   'done' => false],
            ],
            'accommodation' => [
                ['name' => 'accommodation_detail', 'done' => false],
            ],
            'meal' => [],
            'training' => [],
            'other_expenses' => [
                ['name' => 'other_expenses_detail', 'done' => false],
            ],
        ];

        $steps = [];
        foreach ($requested as $name) {
            if (array_key_exists($name, $definitions)) {
                $steps[] = [
                    'name' => $name,
                    'done' => false,
                    'children' => $definitions[$name],
                ];
            }
        }

        session([
            'flow_steps_'.$expensesClaim->id => $steps,
            'step_index_'.$expensesClaim->id => 0,
        ]);

        return redirect()->route('expenses-claims.flow.next', $expensesClaim);
    }

    // Continuer à l'étape suivante
    public function next(ExpensesClaim $expensesClaim)
    {
        $steps = session('flow_steps_'.$expensesClaim->id, []);
        $index = session('step_index_'.$expensesClaim->id, 0);

        if (! isset($steps[$index])) {
            return redirect()->route('expenses-claims.flow.checking-claims', $expensesClaim);
        }

        return $this->routeToStep($steps[$index]['name'], $expensesClaim);
    }

    // Redirection vers la prochaine étape
    public function enterChild(string $childName, ExpensesClaim $expensesClaim)
    {
        session(['current_child' => $childName]);

        return $this->routeToStep($childName, $expensesClaim);
    }

    // Fin de l'étape intermédiaire et retour à l'étape première
    public function returnToParent(ExpensesClaim $expensesClaim)
    {
        $steps = session('flow_steps_'.$expensesClaim->id, []);
        $index = session('step_index_'.$expensesClaim->id, 0);
        $childName = session('current_child');

        // Marquer l'étape intermédiaire comme complète (option)
        if (isset($steps[$index]['children'])) {
            foreach ($steps[$index]['children'] as &$child) {
                if ($child['name'] === $childName) {
                    $child['done'] = true;
                    break;
                }
            }
        }

        session([
            'flow_steps_'.$expensesClaim->id => $steps,
            'current_child' => null,
        ]);

        // Return to the parent step's index/summary page
        return $this->routeToStep($steps[$index]['name'], $expensesClaim);
    }

    // validation de l'étape en cours
    public function completeStep(ExpensesClaim $expensesClaim)
    {
        $steps = session('flow_steps_'.$expensesClaim->id, []);
        $index = session('step_index_'.$expensesClaim->id, 0);

        if (isset($steps[$index])) {
            $steps[$index]['done'] = true;
        }

        $nextIndex = $index + 1;

        // Aller directement à la prochaine étape non faite
        while (isset($steps[$nextIndex]) && $steps[$nextIndex]['done']) {
            $nextIndex++;
        }

        session([
            'flow_steps_'.$expensesClaim->id => $steps,
            'step_index_'.$expensesClaim->id => $nextIndex,
        ]);

        return redirect()->route('expenses-claims.flow.next', $expensesClaim);
    }

    public function checkingClaims(ExpensesClaim $expensesClaim)
    {

        // $claim = ExpensesClaim::with(['travels', 'accommodations', 'meal', 'training', 'otherExpenses'])->findOrFail($expensesClaim->id);
        $claim = ExpensesClaim::with(['drivenTrips', 'accommodations', 'meals', 'trainingExpenses', 'otherExpenses'])->findOrFail($expensesClaim->id);

        return Inertia::render('claim/ClaimSummary', [
            'expensesClaim' => $claim,
        ]);

    }

    public function done(ExpensesClaim $expensesClaim)
    {
        session()->forget(['flow_steps_'.$expensesClaim->id,
            'step_index_'.$expensesClaim->id,
            'pending_steps',
            'current_child',
        ]);

        return Inertia::render('end/End');
        // TODO préparer la prochaine fonction de destination pour la page de confirmation
        // return (new FlowController)->completeClaim ??($expensesClaim); expensesClaim.edit ?
    }

    private function routeToStep(string $step, ExpensesClaim $expensesClaim): RedirectResponse
    {
        return match ($step) {
            'driven_travel' => redirect()->route('expenses-claims.driven-travels.index', $expensesClaim),
            'vehicle' => redirect()->route('expenses-claims.vehicles.create', $expensesClaim),
            'driven_trip' => redirect()->route('expenses-claims.driven-travels.create', $expensesClaim),
            'other_travel' => redirect()->route('expenses-claims.other-travels.index', $expensesClaim),
            'other_trip' => redirect()->route('expenses-claims.other-travels.other-trips.create', $expensesClaim),
            'accommodation' => redirect()->route('expenses-claims.accommodations.index', $expensesClaim),
            'accommodation_detail' => redirect()->route('expenses-claims.accommodations.detail.create', $expensesClaim),
            'meal' => redirect()->route('expenses-claims.meals.index', $expensesClaim),
            'training' => redirect()->route('expenses-claims.training-expenses.index', $expensesClaim),
            'other_expenses' => redirect()->route('expenses-claims.other-expenses.index', $expensesClaim),
            'other_expenses_detail' => redirect()->route('expenses-claims.other-expenses.detail.create', $expensesClaim),
            default => redirect()->route('expenses-claims.flow.done', $expensesClaim),
        };
    }
}
