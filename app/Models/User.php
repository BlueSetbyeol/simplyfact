<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Str;

#[Fillable(['firstname', 'lastname', 'address_street', 'address_zipcode', 'address_city', 'address_country', 'email_address', 'phone_number'])]
// #[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    // protected function casts(): array
    // {
    //     return [
    //         'address_zipcode' => 'integer',
    //     ];
    // }

    protected $fillable = [
        'firstname',
        'lastname',
        'address_street',
        'address_zipcode',
        'address_city',
        'address_country',
        'email_address',
        'phone_number',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expensesClaims(): HasMany
    {
        return $this->hasMany(ExpensesClaim::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
