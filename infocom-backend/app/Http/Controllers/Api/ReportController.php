<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complain;
use App\Models\HelpTopic;
use App\Models\PopAddress;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReportController extends Controller {
    private $department_id, $start, $end;

    public function __construct(Request $request) {
        $this->middleware(['role:support_agent']);
        $this->department_id = $request->query('department_id') ?? '';
        $this->start = $request->query('start') ?? '';
        $this->end = $request->query('end') ?? '';
    }

    private function generateWeekDistribution() {
        $period = CarbonPeriod::create($this->start, $this->end);
        $weekNumber = 1;
        $weeks = array();
        foreach ($period as $date) {
            $weeks[$weekNumber][] = $date;
            if ($date->format('w') == 6) {
                $weekNumber++;
            }
        }
        $ranges = array_map(function ($week) {
            return ['start' => array_shift($week), 'end' => array_pop($week)];
        }, $weeks);
        return $ranges;
    }

    public function fetchDepartmentActivityLog() {
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
            return $c->activitylog();
        }, $complains);


        return response()->json([
            'title' => `Activity log for ` . $this->start . ' - ' . $this->end,
            'headers' => ($this->department_id != '' ? [] : ['Department']) + ['Time', 'Member', 'Task', 'Complain', 'Customer'],
            'rows' => $complains
        ]);
    }

//    public function fetchComplainStatusLog() {
//
//    }

    public function fetchTopicWisePopLog() {
        $helptopics = HelpTopic::all();
        $popaddresses = PopAddress::all();
        $approvedcomplains = Complain::orderBy('approved_time')->whereBetween('approved_time', [$this->start, $this->end]);
        if ($this->department_id !== '') {
            $approvedcomplains = $approvedcomplains->where('department_id', $this->department_id);
        }

        $approvedcomplains = $approvedcomplains->with('customer')->get();
        $topicWisePopLog = $helptopics->map(function ($helptopic, $key) use ($approvedcomplains, $popaddresses) {

            $topicComplains = $approvedcomplains->filter(function ($complain) use ($helptopic) {
                return $complain->customer->helptopic_id == $helptopic->id;
            });

            $popCounts = [];
            foreach ($popaddresses as $popaddress) {
                $count = $topicComplains->filter(function ($complain) use ($popaddress) {
                    return $complain->customer->popaddress_id = $popaddress->id;
                })->count();
                $popCounts[$popaddress->name] = $count;
            }

            return [
                    'S/N' => $key,
                    'Help/Complaint Issue' => $helptopic->name,
                    'Count' => array_sum($popCounts),
                ] + $popCounts;
        });

        $totalCount = $topicWisePopLog->sum('Count');
        $topicWisePopLog = $topicWisePopLog->merge(collect([
                'S/N' => '',
                'Help/Complaint Issue' => '',
                'Count' => $totalCount,

            ] + $popaddresses->map(function ($popaddress) use ($topicWisePopLog) {
                return [$popaddress->name => $topicWisePopLog->sum($popaddress->name)];
            })->all()
        ));


        $topicWisePopLog = $topicWisePopLog->merge(collect([
                'S/N' => '',
                'Help/Complaint Issue' => '',
                'Count' => '',

            ] + $popaddresses->map(function ($popaddress) use ($topicWisePopLog) {
                return [$popaddress->name => ($topicWisePopLog->sum($popaddress->name) / (float)$totalCount) * 100.0];
            })->all()
        ));


        return response()->json([
            'title' => `Pop wise service report from ` . $this->start . ' - ' . $this->end,
            'headers' => ($topicWisePopLog->count()) ? array_keys($topicWisePopLog[0]) : ['S\N', 'Help/Complaint Issue', 'Count'],
            'rows' => $topicWisePopLog
        ]);
    }

    public function fetchServiceTimeLog() {
        $helptopics = HelpTopic::all();
        $approvedcomplains = Complain::orderBy('approved_time')->whereBetween('approved_time', [$this->start, $this->end]);
        if ($this->department_id !== '') {
            $approvedcomplains = $approvedcomplains->where('department_id', $this->department_id);
        }
        $approvedcomplains = $approvedcomplains->get();
        $durations = [
            'Less than 2 hours' => [0, 2],
            'Less than 4 hours' => [2, 4],
            'Less than 8 hours' => [4, 8],
            'Less than 24 hours' => [8, 24],
            'Less than 48 hours' => [24, 48],
            '48 hours plus' => [48, 2000],
        ];

        $topicServiceLog = $helptopics->map(function ($helptopic, $key) use ($approvedcomplains, $durations) {
            $topicComplains = $approvedcomplains->filter(function ($complain) use ($helptopic) {
                return $complain->helptopic_id == $helptopic->id;
            });

            $serviceHourCounts = [];
            foreach ($durations as $key => $range) {
                $count = $topicComplains->filter(function ($complain) use ($range) {
                    $diff = $complain->approved_time->floatDiffInHours($complain->complain_time);
                    return $diff > $range[0] && $diff <= $range[1];
                })->count();
                $serviceHourCounts[$key] = $count;
            }

            return [
                    'S/N' => $key,
                    'Help/Complaint Issue' => $helptopic->name,
                    'Count' => array_sum($serviceHourCounts),
                ] + $serviceHourCounts;
        });

        $topicServiceLog = $topicServiceLog->merge(collect([
            'S/N' => '',
            'Help/Complaint Issue' => '',
            'Count' => $topicServiceLog->sum('Count'),
            'Less than 2 hours' => $topicServiceLog->sum('Less than 2 hours'),
            'Less than 4 hours' => $topicServiceLog->sum('Less than 4 hours'),
            'Less than 8 hours' => $topicServiceLog->sum('Less than 8 hours'),
            'Less than 24 hours' => $topicServiceLog->sum('Less than 24 hours'),
            'Less than 48 hours' => $topicServiceLog->sum('Less than 48 hours'),
            '48 hours plus' => $topicServiceLog->sum('48 hours plus'),
        ]));

        return response()->json([
            'title' => `Report on Service Time ` . $this->start . ' - ' . $this->end,
            'headers' => ($topicServiceLog->count()) ? array_keys($topicServiceLog[0]) : ['S\N', 'Help/Complaint Issue', 'Count'],
            'rows' => $topicServiceLog
        ]);
    }

    public function fetchPopLog() {
        $popaddresses = PopAddress::withCount('customers')->get(); //customers_count
        $approvedcomplains = Complain::orderBy('approved_time')->whereBetween('approved_time', [$this->start, $this->end]);
        if ($this->department_id !== '') {
            $approvedcomplains = $approvedcomplains->where('department_id', $this->department_id);
        }

        $approvedcomplains = $approvedcomplains->with('customer')->get();
        $weeks = $this->generateWeekDistribution();

        $poplog = $popaddresses->map(function ($popaddress, $key) use ($approvedcomplains, $weeks) {
            $popComplains = $approvedcomplains->filter(function ($complain) use ($popaddress) {
                return $complain->customer->popaddress_id == $popaddress->id;
            });

            $weeklyComplainCounts = [];
            foreach ($weeks as $idx => $week) {
                $count = $popComplains->filter(function ($complain) use ($week) {
                    return $complain->approved_time->between($week['start'], $week['end']);
                })->count();
                $weeklyComplainCounts['Week-' . $idx] = $count;
                $weeklyComplainCounts['Column-' . $idx] = ($count / (float)$popaddress->customers_count) * 100.0;
            }

            $overallTotal = array_sum(array_filter($weeklyComplainCounts, function ($v, $k) {
                return str_starts_with($k, 'Week-');
            }, ARRAY_FILTER_USE_BOTH));
            $overallPercentage = ($overallTotal / (float)$popaddress->customers_count) * 100.0;

            return [
                    'S/N' => $key,
                    'POP' => $popaddress->name,
                    'Client Number' => $popaddress->customers_count,
                    'Overall (%)' => `{$overallTotal} ({$overallPercentage}%)`
                ] + $weeklyComplainCounts;
        });

        $poplog = $poplog->merge(collect([
            'S/N' => '',
            'POP' => '',
            'Client Number' => $poplog->sum('Client Number'),
        ]));

        return response()->json([
            'title' => `Report for ` . $this->start . ' - ' . $this->end,
            'headers' => ($poplog->count()) ? array_keys($poplog[0]) : ['S\N', 'POP', 'Client Number', 'Overall (%)'],
            'rows' => $poplog
        ]);
    }
}
