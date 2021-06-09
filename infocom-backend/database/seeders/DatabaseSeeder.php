<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('migrate:fresh');
        Artisan::call('passport:client --personal --name=infocom');
        $this->call(AuthorizationSeeder::class);
        // \App\Models\User::factory(10)->create();
    }
}
