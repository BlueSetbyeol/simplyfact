<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meal extends Model
{
    protected $fillable = [
        'number_of_meal',
        'total_price',
    ];

    //TODO vérifier comment est ajouté 'reimbursed_price'

    public function expenses_claim(): BelongsTo{
        return $this -> belongsTo(ExpensesClaim::class);
    }
}
