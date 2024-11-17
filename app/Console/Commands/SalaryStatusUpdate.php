<?php

namespace App\Console\Commands;

use App\Jobs\SalaryStatusUpdateJob;
use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SalaryStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:salary-status-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to check all employees payment date and update payment status accordingly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $Employees = Employee::get();
        foreach($Employees as $Employee){
            SalaryStatusUpdateJob::dispatch($Employee->id);
        }
        Log::info("daily check for payment date");
    }
}
