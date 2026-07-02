<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

class TrainingExpense extends Model
{
    protected $fillable = [
        'expenses_claim_id',
        'nb_days_of_training',
        'reimbursed_price',
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

    // Accessors & Mutators (euros to cents and vice versa)
    public function getReimbursedPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setReimbursedPriceAttribute($value)
    {
        $this->attributes['reimbursed_price'] = (int) round($value * 100);
    }

}
