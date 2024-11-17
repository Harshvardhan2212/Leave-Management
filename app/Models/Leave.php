<?php

namespace App\Models;

use App\Jobs\AddAttendanceForLeave;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'applied_date',
        'leave_status',
        'reason',
        'leave_days'
    ];

    // Define the primary key as a UUID
    protected $primaryKey = 'id';

    // Set the primary key type to string
    protected $keyType = 'string';

    // Disable auto-incrementing of the primary key
    public $incrementing = false;

    // Automatically generate UUID for new records
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->updateLeaveDays();
        });

        static::created(function ($model) {
            // Dispatch job to add attendance records for the new leave

        });
    }


    public function getLeaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'id');
    }

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function updateLeaveDays()
    {
        if ($this->start_date && $this->end_date) {
            $startDate = Carbon::parse($this->start_date);
            $endDate = Carbon::parse($this->end_date);

            // Initialize a counter for the leave days
            $leaveDays = 0;

            // Iterate through each day from start date to end date
            while ($startDate <= $endDate) {
                // Check if the current day is a weekday (Monday to Friday)
                if ($startDate->isWeekday()) {
                    $leaveDays++;
                }
                // Move to the next day
                $startDate->addDay();
            }

            // Update the leave_days field
            $this->leave_days = $leaveDays;
        }
    }
}
