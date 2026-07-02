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
        'total_price',
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
    public function getTotalPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setTotalPriceAttribute($value)
    {
        $this->attributes['total_price'] = (int) round($value * 100);
    }

    public function getReimbursedPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setReimbursedPriceAttribute($value)
    {
        $this->attributes['reimbursed_price'] = (int) round($value * 100);
    }
}
