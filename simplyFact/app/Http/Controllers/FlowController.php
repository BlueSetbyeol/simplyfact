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

        $valid = ['travel', 'accommodation', 'meal', 'other_expense'];
        $selected = array_values(array_intersect($selected, $valid));

        session(['pending_steps' => $selected]);

        return redirect()->route('flow.summary', $expensesClaim);
    }

    public function summary(ExpensesClaim $expensesClaim)
    {
        return Inertia::render('choices/SumChoices', [
            'expensesClaim' => $expensesClaim,
            'selectedSteps' => session('pending_steps', []),
        ]);
    }

    public function start(ExpensesClaim $expensesClaim)
    {
        $requested = session('pending_steps', []);

        $definitions = [
            'travel' => [
                ['name' => 'vehicle',      'done' => false],
                ['name' => 'driven_trip',  'done' => false],
                ['name' => 'other_trip',   'done' => false],
            ],
            'accommodation' => [
                ['name' => 'accommodation_detail', 'done' => false],
            ],
            'meal' => [],
            'other_expense' => [],
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

        return redirect()->route('flow.next', $expensesClaim);
    }

    // Continuer à l'étape suivante
    public function next(ExpensesClaim $expensesClaim)
    {
        $steps = session('flow_steps_'.$expensesClaim->id, []);
        $index = session('step_index', 0);

        if (! isset($steps[$index])) {
            return redirect()->route('flow.done', $expensesClaim);
        }

        return $this->routeToStep($steps[$index]['name']);
    }

    // Choix d'aller à une étape intermédiaire
    public function enterChild(Request $request)
    {
        $childName = $request->input('child_name');

        // Store which child we're currently working on,
        // so we know where to return after it's saved.
        session(['current_child' => $childName]);

        return $this->routeToStep($childName);
    }

    // Fin de l'étape intermédiaire et retour à l'étape première
    public function returnToParent(ExpensesClaim $expensesClaim)
    {
        $steps = session('flow_steps_'.$expensesClaim->id, []);
        $index = session('step_index', 0);
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
        return $this->routeToStep($steps[$index]['name']);
    }

    // validation de l'étape en cours
    public function completeStep(ExpensesClaim $expensesClaim)
    {
        $steps = session('flow_steps', []);
        $index = session('step_index', 0);

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

        return redirect()->route('flow.next', $expensesClaim);
    }

    public function done(ExpensesClaim $expensesClaim)
    {
        session()->forget(['flow_steps', 'step_index']);

        return Inertia::render('flow.done', $expensesClaim);
    }

    private function routeToStep(string $step): RedirectResponse
    {
        return match ($step) {
            'travel' => redirect()->route('travel.index'),
            'vehicle' => redirect()->route('travel.vehicle.create'),
            'driven_trip' => redirect()->route('travel.driven_trip.create'),
            'other_trip' => redirect()->route('travel.other_trip.create'),
            'accommodation' => redirect()->route('accommodation.index'),
            'accommodation_detail' => redirect()->route('accommodation.detail.create'),
            'meal' => redirect()->route('meal.index'),
            'other_expense' => redirect()->route('other_expense.index'),
            default => redirect()->route('flow.done'),
        };
    }
}
