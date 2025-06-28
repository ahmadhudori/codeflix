<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDevice extends Model
{
    protected $fillable = [
		'user_id',
		'device_name',
		'device_id',
		'device_type',
		'platform',
		'platform_version',
		'browser',
		'browser_version',
		'last_active',
	 ];

	 protected $casts = [
		'last_active' => 'datetime',
	 ];

	 public function user(): BelongsTo
	 {
		return $this->belongsTo(User::class);
	 }
}
