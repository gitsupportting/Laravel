<?php

namespace App\Console\Commands;

use App\Models\EmailReport;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class EmailReportSender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-report:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends email reports';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        EmailReport::dueNow()->chunk(100, function (Collection $emailReports) {
            $emailReports->each->send();
        });
    }
}
