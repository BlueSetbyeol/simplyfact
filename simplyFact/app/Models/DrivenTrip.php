<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

class DrivenTrip extends Model
{
    protected $fillable = [
        'expenses_claim_id',
        'vehicle_id',
        'starting_city',
        'starting_zip_code',
        'ending_city',
        'ending_zip_code',
        'trip_type',
        'total_distance',
        'total_price',
        'total_distance_given',
        'total_price_given',
        'reimbursed_price',
        'description',
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

    public function expensesClaims(): BelongsTo
    {
        return $this->belongsTo(ExpensesClaim::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
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

    public function getTotalPriceGivenAttribute($value)
    {
        return $value / 100;
    }

    public function setTotalPriceGivenAttribute($value)
    {
        $this->attributes['total_price_given'] = (int) round($value * 100);
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
