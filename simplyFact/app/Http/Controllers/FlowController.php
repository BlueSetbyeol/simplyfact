<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FlowController extends Controller
{

    public function start(Request $request)
    {
        $requested = $request->input('steps', []);

        $definitions = [
            'travel' => [
                ['name' => 'vehicle',      'done' => false],
                ['name' => 'driven_trip',  'done' => false],
                ['name' => 'other_trip',   'done' => false],
            ],
            'accommodation' => [
                ['name' => 'accommodation_detail', 'done' => false],
            ],
            'meal'          => [],
            'other_expense' => [],
        ];

        $steps = [];
        foreach ($requested as $name) {
            if (array_key_exists($name, $definitions)) {
                $steps[] = [
                    'name'        => $name,
                    'done'        => false,
                    'children'    => $definitions[$name],
                ];
            }
        }

        session([
            'flow_steps' => $steps,
            'step_index' => 0,
        ]);

        return redirect()->route('flow.next');
    }

    //Advance to the next undone top-level step.
    public function next()
    {
        $steps    = session('flow_steps', []);
        $index    = session('step_index', 0);

        if (!isset($steps[$index])) {
            return redirect()->route('flow.done');
        }

        return $this->routeToStep($steps[$index]['name']);
    }

    // Choose to enter to the next child, stays on the parent step
    public function enterChild(Request $request)
    {
        $childName = $request->input('child_name');

        // Store which child we're currently working on,
        // so we know where to return after it's saved.
        session(['current_child' => $childName]);

        return $this->routeToStep($childName);
    }

    // Finish child step and return to parent step
    public function returnToParent()
    {
        $steps      = session('flow_steps', []);
        $index      = session('step_index', 0);
        $childName  = session('current_child');

        // Mark child as done (optional)
        if (isset($steps[$index]['children'])) {
            foreach ($steps[$index]['children'] as &$child) {
                if ($child['name'] === $childName) {
                    $child['done'] = true;
                    break;
                }
            }
        }

        session([
            'flow_steps'    => $steps,
            'current_child' => null,
        ]);

        // Return to the parent step's index/summary page
        return $this->routeToStep($steps[$index]['name']);
    }

    // validates the entire current step
    public function completeStep()
    {
        $steps = session('flow_steps', []);
        $index = session('step_index', 0);

        if (isset($steps[$index])) {
            $steps[$index]['done'] = true;
        }

        $nextIndex = $index + 1;

        // Skip any steps already marked done
        while (isset($steps[$nextIndex]) && $steps[$nextIndex]['done']) {
            $nextIndex++;
        }

        session([
            'flow_steps' => $steps,
            'step_index' => $nextIndex,
        ]);

        return redirect()->route('flow.next');
    }

    public function done()
    {
        session()->forget(['flow_steps', 'step_index']);
        return Inertia::render('Flow/Done');
    }

    private function routeToStep(string $step): \Illuminate\Http\RedirectResponse
    {
        return match ($step) {
            'travel'               => redirect()->route('travel.index'),
            'vehicle'              => redirect()->route('travel.vehicle.create'),
            'driven_trip'          => redirect()->route('travel.driven_trip.create'),
            'other_trip'           => redirect()->route('travel.other_trip.create'),
            'accommodation'        => redirect()->route('accommodation.index'),
            'accommodation_detail' => redirect()->route('accommodation.detail.create'),
            'meal'                 => redirect()->route('meal.index'),
            'other_expense'        => redirect()->route('other_expense.index'),
            default                => redirect()->route('flow.done'),
        };
    }
}
