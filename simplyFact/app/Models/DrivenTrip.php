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
        'strating_zip_code',
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
}
