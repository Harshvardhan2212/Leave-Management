<?php

namespace App\Console\Commands;

use App\Jobs\CreateSalaryRecordForEmployee;
use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateSalaryRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-salary-record';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this will create salary record of each employee at date 1st of each month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $Employees = Employee::get();
        foreach($Employees as $Employee){
            CreateSalaryRecordForEmployee::dispatch($Employee);
        }
        Log::info("all employees salary details are created for this month");
    }
}
