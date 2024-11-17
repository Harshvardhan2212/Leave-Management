<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateDeductionOnLeave implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employeeId;
    protected $user_id;
    protected $salary;
    protected $leaves; // Collection of leave periods

    /**
     * Create a new job instance.
     *
     * @param int $user_id
     * @param int $employeeId
     * @param float $salary
     * @param \Illuminate\Support\Collection $leaves
     */
    public function __construct($employeeId,$user_id,$salary,$leaves)
    {
        $this->employeeId = $employeeId;
        $this->user_id = $user_id;
        $this->salary = $salary;
        $this->leaves = $leaves; // Expecting a collection of leave periods
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
            $startDate = Carbon::parse($this->leaves->start_date);
            $endDate = Carbon::parse($this->leaves->end_date);

            // Iterate over each month from startDate to endDate
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                $currentMonth = $currentDate->month;
                $currentYear = $currentDate->year;

                // Query for the number of absent days in the current month and year
                $absentDaysCount = Attendance::where('user_id', $this->user_id)
                    ->where('attendance_status', 'absent')
                    ->whereMonth('attendance_date', $currentMonth)
                    ->whereYear('attendance_date', $currentYear)
                    ->count();
                // Update the salary deductions for the employee in the current month
                $deduction = ($this->salary / 20) * $absentDaysCount;
                $check = Salary::where('employee_id', $this->employeeId)
                    ->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->update([
                        'deductions' => $deduction
                    ]);
                // $record = Salary::where('employee_id', $this->employeeId)
                // ->whereMonth('created_at', $currentMonth)
                // ->whereYear('created_at', $currentYear)->first();
                // Log::info('checking status',[$deduction,$this->salary,$absentDaysCount,$currentMonth,$currentYear,$record, $this->employeeId]);
                Log::info('checking status',[$check]);
                Log::info("Salary deduction updated for $currentMonth/$currentYear: " . ($this->salary / 20) * $absentDaysCount);

                // Move to the next month
                $currentDate->addMonth();
            }

        Log::info('Salary deductions have been updated for all leave periods.');
    }
}