<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\PopAddress;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $users = User::factory()->count(10)->create();
        $pops = PopAddress::all();
        foreach ($users as $user){
            $user->assignRole('customer');
            Customer::factory()
                ->create([
                    'user_id' => $user->id,
                    'popaddress_id' => $pops->random()->id
                ]);
        }


    }
}
