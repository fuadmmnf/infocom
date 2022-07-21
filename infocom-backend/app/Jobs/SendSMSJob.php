<?php

namespace App\Jobs;

use App\Handlers\SMSHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $info;

    public function __construct($info)
    {
        $this->info = $info;
    }


    public function handle()
    {
        SMSHandler::sendSMS($this->info['receiver'], $this->info['message']);
    }
}
