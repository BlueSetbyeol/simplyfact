<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Str;

class Vehicle extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_type',
        'electrical',
        'power',
        'price_given',
        'number_plate',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function drivenTrips(): HasMany
    {
        return $this->hasMany(DrivenTrip::class);
    }
}
