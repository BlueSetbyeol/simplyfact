<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Str;

class ExpensesClaim extends Model
{
    protected $fillable = [
        'user_id',
        'committee_name',
        'action_name',
        'action_dates',
        'total_given',
        'total_reimbursed',
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

    public function otherTrips(): HasMany
    {
        return $this->hasMany(OtherTrip::class);
    }

    public function accommodations(): HasMany
    {
        return $this->hasMany(Accommodation::class);
    }

    public function meals(): HasOne
    {
        return $this->hasOne(Meal::class);
    }

    public function trainingExpenses(): HasOne
    {
        return $this->hasOne(TrainingExpense::class);
    }

    public function otherExpenses(): HasMany
    {
        return $this->hasMany(OtherExpense::class);
    }
}
