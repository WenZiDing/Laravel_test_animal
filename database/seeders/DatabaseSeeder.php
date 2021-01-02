<?php

namespace Database\Seeders;
use App\Models\Animal;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
			Schema::disableForeignKeyConstraints();
			Animal::truncate();
			User::truncate();

			User::factory(5)->create();
			Animal::factory(100)->create();

			Schema::enableForeignKeyConstraints();
        // \App\Models\User::factory(10)->create();
    }
}
