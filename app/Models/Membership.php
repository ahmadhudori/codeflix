<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    protected $fillable = [
		'plan_id',
		'user_id',
		'active',
		'start_date',
		'end_date',
	 ];

	 protected $casts = [
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'active' => 'boolean',
	 ];

	 public function plan(): BelongsTo
	 {
	 	return $this->belongsTo(Plan::class);
	 }

	 public function user(): BelongsTo
	 {
		return $this->belongsTo(User::class);
	 }
}
