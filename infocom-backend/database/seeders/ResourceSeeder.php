<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\PopAddress;
use App\Models\Service;
use App\Models\SlaPlan;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run()
    {
        $departments = ['dept1', 'dept2', 'dept3', 'dept4'];
        $slaPlans = ['sla1', 'sla2', 'sla3', 'sla4'];
        $pops = ['pop1', 'pop2', 'pop3', 'pop4'];
        $services = ['service1', 'service2', 'service3', 'service4'];
        $topics = ['topic1', 'topic2', 'topic3', 'topic4'];

        foreach ($departments as $department) {
            Department::create(['name' => $department]);
        }


        foreach ($pops as $pop) {
            PopAddress::create(['name' => $pop]);
        }
        foreach ($services as $service) {
            Service::create(['name' => $service]);
        }
        foreach ($topics as $topic) {
            HelpTopic::create(['name' => $topic]);
        }
        foreach (range(0, count($topics)-1) as $i) {
            SlaPlan::create([
                'name' => $slaPlans[$i],
                'timelimit' => 2,
                'helptopic_id' => $i+1
            ]);
        }
    }
}
