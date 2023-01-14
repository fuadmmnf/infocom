<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complain;
use App\Models\Customer;
use App\Models\HelpTopic;
use App\Models\PopAddress;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;


class ReportController extends Controller
{
    private $department_id, $start, $end, $isPDF;

    public function __construct(Request $request)
    {
//        $this->middleware(['auth:api']);
        $this->department_id = $request->query('department_id') ?? '';
        $this->start = $request->query('start') ?? '';
        $this->end = $request->query('end') ?? '';
        $this->isPDF = filter_var($request->query('pdf'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    private function generateWeekDistribution()
    {
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


    public function exportCustomers()
    {
        $customers = Customer::with('user')->get();
        $customers = $customers->transform(function ($c) {
            $address_arr = explode(',', $c->address);
            return [
                'client_type' => $c->client_type,
                'connection_type' => $c->connection_type,
                'client_name' => $c->user->name,
                'bandwidth_distribution_point' => $c->bandwidth_distribution_point,
                'connectivity_type' => $c->connectivity_type,
                'activation_date' => Carbon::create($c->installation_date)->format('d/m/Y'),
                'bandwidth_allocation' => $c->bandwidth_allocation,
                'allocated_ip' => $c->allocated_ip,
                'division' => $address_arr[count($address_arr) - 1],
                'district' => $address_arr[count($address_arr) - 2],
                'thana' => $address_arr[count($address_arr) - 3],
                'address' => join(",", array_slice($address_arr, 0, count($address_arr) - 3)),
                'client_mobile' => $c->user->phone,
                'client_email' => $c->user->email,
                'selling_price_bdt_excluding_vat' => $c->selling_price_bdt_excluding_vat,
            ];
        })->toArray();


        return response()->json([
            'title' => 'Customers-' . Carbon::now()->format('Y_m_d-H-i'),
            'headers' => (count($customers) > 0 ? array_keys($customers[0]) : []),
            'rows' => $customers
        ]);
    }

    public function fetchDepartmentActivityLog()
    {
        $assignedComplains = Complain::orderBy('assigned_time')->whereBetween('assigned_time', [$this->start, $this->end]);
        $finishedComplains = Complain::orderBy('finished_time')->whereBetween('finished_time', [$this->start, $this->end]);
        if ($this->department_id !== '') {
            $assignedComplains = $assignedComplains->where('department_id', $this->department_id);
            $finishedComplains = $finishedComplains->where('department_id', $this->department_id);
        }
        $assignedComplains = $assignedComplains->withTrashed()->get();
        $finishedComplains = $finishedComplains->withTrashed()->get();

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
                    'Department' => $c->department->name
                ]) + [
                    'Time' => $c->time->format('Y-m-d H:i'),
                    'Member' => $c->editor == null ? "" : "{$c->editor->user->name} ({$c->editor->user->phone})",
                    'Task' => $c->type,
                    'Complain' => "TT#{$c->id}",
                    'Customer' => "{$c->customer->user->name} ({$c->customer->user->phone})"
                ];
        }, $complains);


        if ($this->isPDF) {
            $pdf = PDF::loadView(
                'report.generic',
                ['title'=> 'Activity Log Report', 'start' => $this->start, 'end' => $this->end, 'logs' => $complains]
            );
            return response()->json(base64_encode($pdf->output()));
        } else {
            return response()->json([
                'title' => 'Activity log for ' . $this->start . ' - ' . $this->end,
                'headers' => ($this->department_id != '' ? [] : ['Department']) + ['Time', 'Member', 'Task', 'Complain', 'Customer'],
                'rows' => $complains
            ]);
        }


    }

//    public function fetchComplainStatusLog() {
//
//    }

    public function fetchTopicWisePopLog()
    {
        $helptopics = HelpTopic::all();
        $popaddresses = PopAddress::all();
        $approvedcomplains = Complain::orderBy('approved_time')->whereBetween('approved_time', [$this->start, $this->end]);
        if ($this->department_id !== '') {
            $approvedcomplains = $approvedcomplains->where('department_id', $this->department_id);
        }

        $approvedcomplains = $approvedcomplains->with('customer')->withTrashed()->get();
        $topicWisePopLog = $helptopics->map(function ($helptopic, $key) use ($approvedcomplains, $popaddresses) {

            $topicComplains = $approvedcomplains->filter(function ($complain) use ($helptopic) {
                return $complain->helptopic_id == $helptopic->id;
            });

            $popCounts = [];
            foreach ($popaddresses as $popaddress) {
                $count = $topicComplains->filter(function ($complain) use ($popaddress) {
                    return $complain->customer->popaddress_id == $popaddress->id;
                })->count();
                $popCounts[$popaddress->name] = $count;
            }

            return [
                    'S/N' => $key + 1,
                    'Help/Complaint Issue' => $helptopic->name,
                    'Count' => array_sum($popCounts),
                ] + $popCounts;
        });

        $totalCount = $topicWisePopLog->sum('Count');
        $popWisePercentage = $popaddresses->mapWithKeys(function ($popaddress) use ($topicWisePopLog, $totalCount) {
            return [$popaddress->name => $totalCount ? (($topicWisePopLog->sum($popaddress->name) / (float)$totalCount) * 100.0) : 0];
        })->all();

        $topicWisePopLog = $topicWisePopLog->add(collect([
                'S/N' => '',
                'Help/Complaint Issue' => '',
                'Count' => $totalCount,

            ] + $popaddresses->mapWithKeys(function ($popaddress) use ($topicWisePopLog) {
                return [$popaddress->name => $topicWisePopLog->sum($popaddress->name)];
            })->all()
        ));


        $topicWisePopLog = $topicWisePopLog->add(collect([
                'S/N' => '',
                'Help/Complaint Issue' => '',
                'Count' => 'Percentage',

            ] + $popWisePercentage
        ));


        if ($this->isPDF) {
            $pdf = PDF::loadView(
                'report.generic',
                ['title'=> 'Topic Wise Pop Report', 'start' => $this->start, 'end' => $this->end, 'logs' => $topicWisePopLog]
            );
            return response()->json(base64_encode($pdf->output()));
        } else {
            return response()->json([
                'title' => 'Topic Wise Pop Report from ' . $this->start . ' - ' . $this->end,
                'headers' => ($topicWisePopLog->count()) ? array_keys($topicWisePopLog[0]) : ['S\N', 'Help/Complaint Issue', 'Count'],
                'rows' => $topicWisePopLog
            ]);
        }


    }

    public function fetchServiceTimeLog()
    {
        $helptopics = HelpTopic::all();
        $approvedcomplains = Complain::orderBy('approved_time')->whereBetween('approved_time', [$this->start, $this->end]);
        if ($this->department_id !== '') {
            $approvedcomplains = $approvedcomplains->where('department_id', $this->department_id);
        }
        $approvedcomplains = $approvedcomplains->withTrashed()->get();
        $durations = [
            'Less than 2 hours' => [0, 2],
            'Less than 4 hours' => [2, 4],
            'Less than 8 hours' => [4, 8],
            'Less than 24 hours' => [8, 24],
            'Less than 48 hours' => [24, 48],
            '48 hours plus' => [48, 20000],
        ];

        $topicServiceLog = $helptopics->map(function ($helptopic, $key) use ($approvedcomplains, $durations) {
            $topicComplains = $approvedcomplains->filter(function ($complain) use ($helptopic) {
                return $complain->helptopic_id == $helptopic->id;
            });

            $serviceHourCounts = [];
            foreach ($durations as $duration => $range) {
                $count = $topicComplains->filter(function ($complain) use ($duration, $range) {
                    $diff = $complain->approved_time->floatDiffInHours($complain->complain_time);
                    return ($duration != '48 hours plus' && $diff > $range[0] && $diff <= $range[1]) || ($duration == '48 hours plus' && $diff > $range[0]);
                })->count();
                $serviceHourCounts[$duration] = $count;
            }

            return [
                    'S/N' => $key + 1,
                    'Help/Complaint Issue' => $helptopic->name,
                    'Count' => array_sum($serviceHourCounts),
                ] + $serviceHourCounts;
        });

        $topicServiceLog = $topicServiceLog->add(collect([
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


        if ($this->isPDF) {
            $pdf = PDF::loadView(
                'report.generic',
                ['title'=> 'Service Time Report', 'start' => $this->start, 'end' => $this->end, 'logs' => $topicServiceLog]
            );
            return response()->json(base64_encode($pdf->output()));
        } else {
            return response()->json([
                'title' => 'Report on Service Time ' . $this->start . ' - ' . $this->end,
                'headers' => ($topicServiceLog->count()) ? array_keys($topicServiceLog[0]) : ['S\N', 'Help/Complaint Issue', 'Count'],
                'rows' => $topicServiceLog
            ]);
        }


    }

    public function fetchPopLog()
    {
        $popaddresses = PopAddress::withCount('customers')->get(); //customers_count
        $approvedcomplains = Complain::orderBy('approved_time')->whereBetween('approved_time', [$this->start, $this->end]);
        if ($this->department_id !== '') {
            $approvedcomplains = $approvedcomplains->where('department_id', $this->department_id);
        }

        $approvedcomplains = $approvedcomplains->with('customer')->withTrashed()->get();
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
                $weeklyComplainCounts['Column-' . $idx] = ($popaddress->customers_count ? round(($count / (float)$popaddress->customers_count) * 100.0, 2) : 0) . '%';
            }

            $overallTotal = array_sum(array_filter($weeklyComplainCounts, function ($v, $k) {
                return str_starts_with($k, 'Week-');
            }, ARRAY_FILTER_USE_BOTH));
            $overallPercentage = $popaddress->customers_count ? round(($overallTotal / (float)$popaddress->customers_count) * 100.0, 2) : 0;

            return [
                    'S/N' => $key + 1,
                    'POP' => $popaddress->name,
                    'Client Number' => $popaddress->customers_count,
                    'Overall (%)' => "{$overallTotal} ({$overallPercentage}%)"
                ] + $weeklyComplainCounts;
        });

        $poplog = $poplog->add(collect([
            'S/N' => 'Total',
            'POP' => '',
            'Client Number' => $poplog->sum('Client Number'),

        ]));

        if ($this->isPDF) {
            $pdf = PDF::loadView(
                'report.generic',
                ['title' => 'Complain Service Time Report', 'start' => $this->start, 'end' => $this->end, 'logs' => $poplog]
            );
//            $fileName = 'PopLogReport' . '.pdf';
            return response()->json(base64_encode($pdf->output()));
        } else {
            return response()->json([
                'title' => 'Report for ' . $this->start . ' - ' . $this->end,
                'headers' => ($poplog->count()) ? array_keys($poplog[0]) : ['S\N', 'POP', 'Client Number', 'Overall (%)'],
                'rows' => $poplog
            ]);
        }
    }

}
