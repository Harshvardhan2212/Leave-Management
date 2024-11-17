<?php

namespace App\Jobs;

use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateSalaryRecordForEmployee implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $Employee;
    /**
     * Create a new job instance.
     */
    public function __construct($Employee)
    {
        $this->Employee = $Employee;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Salary::create([
            'employee_id'=>$this->Employee->id,
            'basic_salary'=>$this->Employee->current_salary,
            'payment_status'=>'pending',
            'payment_date'=>Carbon::now()->day(10)
        ]);
        Log::info("$this->Employee->id record created");
    }
}
