<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

	 public function memberships(): HasMany
	 {
		return $this->hasMany(Membership::class);
	 }

	 public function devices(): HasMany
	 {
		return $this->hasMany(UserDevice::class);
	 }

	 public function hasMembershipsPlan(): bool
	 {
		return $this->memberships()
		->where('active', true)
		->where('end_date', '>=', today())
		->exists();
	 }

	 public function getCurrentPlan()
	 {
		$activeMembership = $this->memberships()
		->where('active', true)
		->where('start_date', '<=', today())
		->where('end_date', '>=', today())
		->latest()
		->first();

		if (! $activeMembership) {
			return null;
		}

		// return $activeMembership->plan;
	 }
}
