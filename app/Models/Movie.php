<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
		'title',
        'slug',
        'description',
        'director',
        'writers',
        'stars',
        'poster',
        'release_date',
        'duration',
        'url_720',
        'url_1080',
        'url_4k',
	];

	protected $appends = [
		'average_rating'
	];

	protected $casts = [
		'release_date' => 'datetime'
	];

	public function categories()
	{
		return $this->belongsToMany(Category::class);
	}

	public function ratings()
	{
		return $this->hasMany(Rating::class);
	}

	public function getAverageRatingAttribute(): float
	{
		return round($this->ratings()->avg('rating'), 2);
	}

	public function getStreamingUrl(string $resolution) 
	{
		return match ($resolution) {
			'720p' => $this->url_720,
			'1080p' => $this->url_1080,
			'4k' => $this->url_4k,
			default => $this->url_720
		};
	}

	public function getFormattedDurationAttribute()
	{
		$hours = floor($this->duration / 60);
		$minutes = $this->duration % 60;
		$formatted = '';
		if($hours > 0) {
			$formatted = $hours . 'h ';
		}

		if($minutes > 0 || $hours == 0) {
			$formatted .= $minutes . 'm';
		}
		$formated = $hours . 'h ' . $minutes . 'm';
		return trim($formatted);
	}
}
