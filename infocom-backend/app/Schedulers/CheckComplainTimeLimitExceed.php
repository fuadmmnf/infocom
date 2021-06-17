<?php

namespace App\Schedulers;

use App\Models\Complain;
use Carbon\Carbon;

class CheckComplainTimeLimitExceed
{
    public function __invoke()
    {
        $unfinishedComplains = Complain::orderByDesc('complain_time')->whereNull('approved_time')->where('status', '!=', 'overdue')->with('slaplan')->get();
        foreach ($unfinishedComplains as $complain) {
            $limit = $complain->slaplan->timelimit;
            if ($limit > 0) {
                $diff = Carbon::now()->diffInMinutes($complain->complain_time->addHours($limit));
                if ($diff > 0) {
                    $complain->update([
                        'status' => 'overdue'
                    ]);
                }
            }
        }
    }

}
