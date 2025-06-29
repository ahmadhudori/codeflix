<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller implements HasMiddleware
{
	public static function middleware()
	{
		return [
			'auth',
			'checkDeviceLimit'
		];
	}
    public function index()
	{
		$latestMovies = Movie::latest()->limit(8)->get();
		$popularMovie = Movie::with('ratings')->get()->sortByDesc('average_rating')->take(8);
		return view('movies.index', [
			'latestMovies' => $latestMovies,
			'popularMovies' => $popularMovie,
		]);
	}

	public function show(Movie $movie)
	{
		$userPlan = Auth::user()->getCurrentPlan();
		$streamingUrl = $movie->getStreamingUrl($userPlan->resolution);
		return view('movies.show', [
			'movie' => $movie,
			'streamingUrl' => $streamingUrl
		]);
	}

	public function search(Request $request)
	{
		$keyword = $request->input('q');
		$movies = Movie::where('title', 'like', "%$keyword%")->get();
		return view('movies.search', compact('movies', 'keyword'));
	}

	public function all(Request $request)
	{
		$movies = Movie::orderBy('release_date', 'desc')->paginate(8);
		if($request->ajax()) {
			$html = view('components.movie-list', compact('movies'))->render();
			return response()->json([
				'html' => $html,
				'next_page' => $movies->nextPageUrl()
			]);
		}

		return view('movies.all', compact('movies'));
	}
}
