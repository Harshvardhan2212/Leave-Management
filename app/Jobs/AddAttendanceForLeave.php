<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AddAttendanceForLeave implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $leaveId;
    protected $user_id;
    /**
     * Create a new job instance.
     */
    public function __construct($leaveId,$user_id)
    {
        $this->leaveId = $leaveId;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Add attendance leave queue running');
        $leave = Leave::find($this->leaveId,);
    
        if (!$leave) {
            Log::warning('Leave record not found: ' . $this->leaveId);
            return; // Leave record not found
        }
    
        $startDate = Carbon::parse($leave->start_date);
        $endDate = Carbon::parse($leave->end_date);
    
        // Iterate through each day between start_date and end_date
        while ($startDate->lte($endDate)) {
            // Check if the current day is a holiday or a weekend
            $isHoliday = Holiday::where('holiday_date', $startDate->format('Y-m-d'))->exists();
            $isWeekend = $startDate->isWeekend(); // Check if it's Saturday or Sunday
    
            // If today is a holiday or weekend, skip this iteration
            if ($isHoliday || $isWeekend) {
                Log::info("Skipping " . ($isHoliday ? "holiday" : "weekend") . " on: " . $startDate->toDateString());
            } else {
                // Create an attendance record for each weekday of leave
                Attendance::updateOrCreate(
                    [
                        'user_id' => $this->user_id,
                        'attendance_date' => $startDate->format('Y-m-d')
                    ],
                    [
                        'attendance_status' => 'absent'
                    ]
                );
            }
    
            // Move to the next day
            $startDate->addDay();
        }
    }
}
