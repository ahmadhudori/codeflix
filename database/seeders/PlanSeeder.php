<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

		  DB::table('plans')->truncate();

		  DB::table('plans')->insert([
			  [
				  'title' => 'Basic',
				  'price' => 35000,
				  'duration' => 30,
				  'resolution' => '720p',
				  'max_devices' => 1,
				  'created_at' => now(),
				  'updated_at' => now(),
			  ],
			  [
				  'title' => 'Standard',
				  'price' => 50000,
				  'duration' => 60,
				  'resolution' => '1080p',
				  'max_devices' => 4,
				  'created_at' => now(),
				  'updated_at' => now(),
			  ],
			  [
				  'title' => 'Premium',
				  'price' => 100000,
				  'duration' => 90,
				  'resolution' => '4k',
				  'max_devices' => 8,
				  'created_at' => now(),
				  'updated_at' => now(),
			  ]
			  ]);
		  Schema::enableForeignKeyConstraints();
    }
}
