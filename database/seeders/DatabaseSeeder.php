<?php

namespace Database\Seeders;

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
			Animal::factory(10000)->create();

			Schema::enableForeignKeyConstraints();
        // \App\Models\User::factory(10)->create();
    }
}
