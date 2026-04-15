<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

class Meal extends Model
{
    protected $fillable = [
        'number_of_meal',
        'total_price',
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
