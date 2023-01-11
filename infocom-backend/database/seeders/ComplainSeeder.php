<?php

namespace Database\Seeders;

use App\Models\CallcenterAgent;
use App\Models\Complain;
use App\Models\Customer;
use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\SlaPlan;
use Illuminate\Database\Seeder;

class ComplainSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::all();
        $callcenters = CallcenterAgent::all();
        $topics = HelpTopic::all();
        $depts = Department::all();
        //create 5 pending complains
        foreach (range(1,5) as $i){
            $topic = $topics->random();
            Complain::factory()
                ->create([
                    'customer_id' => $customers->random()->id,
                    'agent_id' => $callcenters->random()->id,
                    'helptopic_id' => $topic->id,
                    'department_id' => $depts->random()->id,
                    'slaplan_id' => SlaPlan::where('helptopic_id', $topic->id)->first()->id,
                ]);
        }
    }
}
