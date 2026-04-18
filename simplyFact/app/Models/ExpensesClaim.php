<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Str;

class ExpensesClaim extends Model
{
    protected $fillable = [
        'user_id',
        'committee_name',
        'action_name',
        'action_dates',
        'total_given' => 'decimal:2',
        'total_reimbursed' => 'decimal:2',
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

    // public function travels(): HasMany{ return $this->hasMany(Travel::class); }
    // public function accommodations(): HasMany{ return $this->hasMany(Accommodation::class); }

    public function meals(): HasMany // TODO a changé pour HasOne parce qu'il y aura qu'un seul repas déclaré (total)
    {
        return $this->hasMany(Meal::class);
    }

    // public function otherExpenses(): HasMany { return $this->hasMany(OtherExpense::class); }
}
