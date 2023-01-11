<?php

namespace Database\Seeders;

use App\Models\Complain;
use Illuminate\Database\Seeder;

class ComplainSeeder extends Seeder
{
    public function run()
    {
        //create 5 pending complains
        foreach (range(1,5) as $i){

            Complain::factory()
                ->create([
                ]);
        }
    }
}
