<?php

namespace App\Services;

use App\Models\ExpensesClaim;

class FlowCleaner
{
    public function resetSessionIfClaimChanged(ExpensesClaim $expensesClaim): void
    {
        $storedId = (int) session('expenses_claim_id');

        if ($storedId !== $expensesClaim->id) {
            $this->clearFlowSession($expensesClaim);
        }

        // Pour garder l'id synchroniser avec la NDF actuelle
        session(['expenses_claim_id' => $expensesClaim->id]);
    }

    public function clearFlowSession(ExpensesClaim $expensesClaim): void
    {
        session()->forget([
            'flow_steps_'.$expensesClaim->id,
            'step_index_'.$expensesClaim->id,
            'pending_steps',
            'current_child',
            'expenses_claim_id',
        ]);
    }
}
