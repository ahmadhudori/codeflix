<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CategoryMovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks sementara
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel sebelum melakukan seed
        DB::table('category_movie')->truncate();

        // Menghubungkan kategori dan movie di tabel pivot 'category_movie'
        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        $movieIds = DB::table('movies')->pluck('id')->toArray();

        foreach ($movieIds as $movieId) {
            // Ambil kategori secara acak dan hubungkan dengan film
            $randomCategories = array_rand($categoryIds, rand(1, 3)); // Pilih antara 1-3 kategori untuk setiap film
            $randomCategories = (array) $randomCategories; // Pastikan hasilnya berupa array

            foreach ($randomCategories as $categoryId) {
                DB::table('category_movie')->insert([
                    'category_id' => $categoryIds[$categoryId],
                    'movie_id' => $movieId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();
    }
}
