<?php

namespace Database\Seeders;

use App\Models\CallcenterAgent;
use App\Models\Complain;
use App\Models\Customer;
use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\SlaPlan;
use App\Models\SupportAgent;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;



class ComplainSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        $customers = Customer::all();
        $callcenters = CallcenterAgent::all();
        $topics = HelpTopic::all();
        $depts = Department::all();
        //create 5 pending complains
        foreach (range(1, 25) as $i) {
            $topic = $topics->random();
            Complain::factory()
                ->create([
                    'customer_id' => $customers->random()->id,
//                    'agent_id' => $callcenters->random()->id,
                    'helptopic_id' => $topic->id,
                    'department_id' => $depts->random()->id,
                    'slaplan_id' => SlaPlan::where('helptopic_id', $topic->id)->first()->id,
                ]);
        }

        foreach (range(1, 25) as $i) {
            $topic = $topics->random();
            $time = Carbon::now()->subDays(5);
            Complain::factory()
                ->create([
                    'customer_id' => $customers->random()->id,
                    'agent_id' => $callcenters->random()->id,
                    'helptopic_id' => $topic->id,
                    'department_id' => $depts->random()->id,
                    'slaplan_id' => SlaPlan::where('helptopic_id', $topic->id)->first()->id,
                    'status' => 'assigned',
                    'complain_time' => $time,
                    'assigned_time' => $time->clone()->addDay()
                ]);
        }


        foreach (range(1, 25) as $i) {
            $topic = $topics->random();
            $dept = $depts->random();
            $time = Carbon::now()->subDays(5);
            Complain::factory()
                ->create([
                    'customer_id' => $customers->random()->id,
                    'editor_id' => SupportAgent::where('department_id', $dept->id)->get()->random()->id,
                    'agent_id' => $callcenters->random()->id,
                    'helptopic_id' => $topic->id,
                    'department_id' => $dept->id,
                    'slaplan_id' => SlaPlan::where('helptopic_id', $topic->id)->first()->id,
                    'status' => 'working',
                    'complain_time' => $time,
                    'assigned_time' => $time->clone()->addDay()
                ]);
        }


        foreach (range(1, 25) as $i) {
            $topic = $topics->random();
            $dept = $depts->random();
            $time = Carbon::now()->subDays(5);
            Complain::factory()
                ->create([
                    'customer_id' => $customers->random()->id,
                    'editor_id' => SupportAgent::where('department_id', $dept->id)->get()->random()->id,
                    'agent_id' => $callcenters->random()->id,
                    'helptopic_id' => $topic->id,
                    'department_id' => $dept->id,
                    'slaplan_id' => SlaPlan::where('helptopic_id', $topic->id)->first()->id,
                    'status' => 'finished',
                    'complain_feedback' => $faker->paragraph,
                    'complain_time' => $time,
                    'assigned_time' => $time->clone()->addDay(),
                    'finished_time' => $time->clone()->addDays(2),
                ]);
        }


        foreach (range(1, 25) as $i) {
            $topic = $topics->random();
            $dept = $depts->random();
            $time = Carbon::now()->subDays(5);
            Complain::factory()
                ->create([
                    'customer_id' => $customers->random()->id,
                    'editor_id' => SupportAgent::where('department_id', $dept->id)->get()->random()->id,
                    'agent_id' => $callcenters->random()->id,
                    'helptopic_id' => $topic->id,
                    'department_id' => $dept->id,
                    'slaplan_id' => SlaPlan::where('helptopic_id', $topic->id)->first()->id,
                    'status' => 'approved',
                    'complain_feedback' => $faker->paragraph,
                    'complain_time' => $time,
                    'assigned_time' => $time->clone()->addDay(),
                    'finished_time' => $time->clone()->addDays(2),
                    'approved_time' => $time->clone()->addDays(3),
                ]);
        }


        foreach (range(1, 25) as $i) {
            $topic = $topics->random();
            $dept = $depts->random();
            $time = Carbon::now()->subDays(5);
            Complain::factory()
                ->create([
                    'customer_id' => $customers->random()->id,
                    'editor_id' => SupportAgent::where('department_id', $dept->id)->get()->random()->id,
                    'agent_id' => $callcenters->random()->id,
                    'helptopic_id' => $topic->id,
                    'department_id' => $dept->id,
                    'slaplan_id' => SlaPlan::where('helptopic_id', $topic->id)->first()->id,
                    'status' => 'overdue',
                    'complain_feedback' => $faker->paragraph,
                    'complain_time' => $time,
                    'assigned_time' => $time->clone()->addDay(),
//                    'finished_time' => $time->clone()->addDays(2),
//                    'approved_time' => $time->clone()->addDays(3),
                ]);
        }
    }
}
