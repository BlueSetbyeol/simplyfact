<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expenses_claim extends Model
{
    protected $fillable = [
        'commitee_name',
        'action_name',
        'action_dates',
        'total_given',
        'total_reimbursed',
    ];

    public function user(): BelongsTo{
        return $this -> belongsTo(User::class);
    }


}
