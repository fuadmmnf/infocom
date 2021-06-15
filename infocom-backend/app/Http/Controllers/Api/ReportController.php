<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complain;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private $department_id, $start, $end;

    public function __construct(Request $request)
    {
        $this->middleware(['role:support_agent']);
        $this->department_id = $request->query('department_id') ?? '';
        $this->start = $request->query('start') ?? '';
        $this->end = $request->query('end') ?? '';
    }

    public function fetchDepartmentActivityLog()
    {
        $assignedComplains = Complain::orderBy('assigned_time')->whereBetween('assigned_time', [$this->start, $this->end]);
        $finishedComplains = Complain::orderBy('finished_time')->whereBetween('finished_time', [$this->start, $this->end]);
        if ($this->department_id !== '') {
            $assignedComplains = $assignedComplains->where('department_id', $this->department_id);
            $finishedComplains = $finishedComplains->where('department_id', $this->department_id);
        }
        $assignedComplains = $assignedComplains->get();
        $finishedComplains = $finishedComplains->get();

        $assignedComplains->load('customer', 'customer.user', 'department', 'editor', 'editor.user');
        $finishedComplains->load('customer', 'customer.user', 'department', 'editor', 'editor.user');

        $complains = [];
        foreach ($assignedComplains as $complain) {
            $complain->type = 'assigned';
            $complain->time = $complain->assigned_time;
            $complains[] = $complain;
        }

        foreach ($finishedComplains as $complain) {
            $complain->type = 'finished';
            $complain->time = $complain->finished_time;
            $complains[] = $complain;
        }

        usort($complains, function ($a, $b) {
            return $a->time->lte($b->time) ? -1 : 1;
        });

        $complains = array_map(function ($c) {
            return ($this->department_id != '' ? [] : [
                    'department' => $c->department->name
                ]) + [
                    'time' => $c->time->format('Y-m-d H:i'),
                    'member' => `{$c->editor->user->name} ({$c->editor->user->phone})`,
                    'task' => $c->type,
                    'complain' => `TT{$c->id}`,
                    'customer' => `{$c->customer->user->name} ({$c->customer->user->phone})`
                ];
        }, $complains);

        return response()->json($complains);
    }

    public function fetchComplainStatusLog()
    {

    }

    public function fetchTopicWisePopLog()
    {

    }

    public function fetchServiceTimeLog()
    {

    }

    public function fetchPopLog()
    {

    }
}
