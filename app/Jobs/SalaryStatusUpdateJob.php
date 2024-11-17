<?php

namespace App\Jobs;

use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SalaryStatusUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $EmployeeId;
    /**
     * Create a new job instance.
     */
    public function __construct($EmployeeId)
    {
        $this->EmployeeId = $EmployeeId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         $currentDate = Carbon::today();

        // Update the salary status for today's payment date
        Salary::where('employee_id', $this->EmployeeId)
            ->whereDate('payment_date', $currentDate)
            ->update([
                'payment_status' => 'paid'
            ]);
    }
}
