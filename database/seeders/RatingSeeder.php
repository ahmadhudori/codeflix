<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
		DB::table('ratings')->truncate();

		 // Ambil semua movie dan user
        $movies = Movie::all();
        $users = User::all();

        // Untuk setiap movie, pastikan setiap user memberikan rating
        $ratings = [];
        foreach ($movies as $movie) {
            foreach ($users as $user) {
                $ratings[] = [
                    'user_id' => $user->id,
                    'movie_id' => $movie->id,
                    'rating' => fake()->randomFloat(1, 0, 10)
                ];
            }
        }
        Rating::insert($ratings);
		
		Schema::enableForeignKeyConstraints();
    }
}
