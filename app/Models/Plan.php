<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
		'title',
		'price',
		'duration',
		'resolution',
		'max_devices',
	 ];

	 public function memberships(): HasMany
	 {
	 	return $this->hasMany(Membership::class);
	 }

	 public function users(): BelongsToMany
	 {
		return $this->belongsToMany(User::class, 'memberships', 'plan_id', 'user_id');
	 }
}
