<?php

namespace Database\Seeders;

use App\Models\CallcenterAgent;
use App\Models\Department;
use App\Models\SupportAgent;
use App\Models\User;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $callcenterUsers = User::factory()->count(5)->create();
        $departments = Department::all();
        foreach ($callcenterUsers as $user) {
            $user->assignRole('callcenter_agent');
            $callcenter = new CallcenterAgent();
            $callcenter->user_id = $user->id;
            $callcenter->save();
        }

        $supportUsers = User::factory()->count($departments->count() * 2)->create();
        $departments = Department::all();
        foreach (range(0, $departments->count()) as $i) {
            $dept = $departments->slice($i, 1);
            foreach (range(0, 1) as $j) {

                $user->assignRole('support_agent');
                $supportagent = new SupportAgent();
                $supportagent->user_id = $supportUsers->slice($i * 2 + $j, 1)->id;
                $supportagent->department_id = $dept->id;
                $supportagent->save();

                if ($j == 0) { //assign dept head
                    $dept->leader_id = $supportagent->id;
                    $dept->save();

                }
            }
        }
    }
}
