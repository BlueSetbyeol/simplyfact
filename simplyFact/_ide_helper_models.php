<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $user_id
 * @property string $committee_name
 * @property string $action_name
 * @property string $action_dates
 * @property numeric|null $total_given
 * @property numeric|null $total_reimbursed
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Meal> $meals
 * @property-read int|null $meals_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpensesClaim newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpensesClaim newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpensesClaim query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpensesClaim whereActionDates($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpensesClaim whereActionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpensesClaim whereCommitteeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpensesClaim whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpensesClaim whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpensesClaim whereTotalGiven($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpensesClaim whereTotalReimbursed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpensesClaim whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpensesClaim whereUserId($value)
 */
	class ExpensesClaim extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $expenses_claim_id
 * @property int $number_of_meal
 * @property float $total_price
 * @property float $reimbursed_price
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\ExpensesClaim $expenses_claim
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Meal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Meal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Meal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Meal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Meal whereExpensesClaimId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Meal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Meal whereNumberOfMeal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Meal whereReimbursedPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Meal whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Meal whereUpdatedAt($value)
 */
	class Meal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $address_street
 * @property int $address_zipcode
 * @property string $address_city
 * @property string $address_country
 * @property string $email_address
 * @property string $phone_number
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpensesClaim> $expensesClaims
 * @property-read int|null $expenses_claims_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddressCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddressCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddressStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddressZipcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

