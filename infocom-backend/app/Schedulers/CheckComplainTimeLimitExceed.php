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
                if (Carbon::now()->gt($complain->complain_time->addHours($limit))) {
                    $complain->update([
                        'status' => 'overdue'
                    ]);
                }
            }
        }
    }

}
