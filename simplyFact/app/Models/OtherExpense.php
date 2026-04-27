<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

class OtherExpense extends Model
{
    protected $fillable = [
        'expenses_claim_id',
        'expense_name',
        'expense_price',
        'nb_days_of_training',
    ];

    // Generation d'un UUID à la place d'un id en integer
    protected $keyType = 'string';

    public $incrementing = false;

    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function expenses_claim(): BelongsTo
    {
        return $this->belongsTo(ExpensesClaim::class);
    }
}
