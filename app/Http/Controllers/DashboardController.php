<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class 
DashboardController extends Controller
{
    public function showDashBoard(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if ($user->role) {
            $leavesCount = Leave::selectRaw("COUNT(CASE WHEN leave_status = 'approve' THEN 1 END) as approved_count")
                ->selectRaw("COUNT(CASE WHEN leave_status = 'pending' THEN 1 END) as pending_count")
                ->first();

            $EmployeeCount = Employee::count();

            $today = Carbon::today();

            $todayPresentCount = Attendance::where('attendance_status', 'present')
                ->whereDate('attendance_date', $today)
                ->count();

            $salaryCount = Salary::selectRaw("
                SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_count,
                SUM(CASE WHEN payment_status = 'pending' THEN 1 ELSE 0 END) as pending_count
                ")
                ->whereMonth('payment_date', Carbon::now()->subMonth()->month)
                ->whereYear('payment_date', Carbon::now()->year)
                ->first();
            $holidayCounts = Holiday::selectRaw("
        SUM(CASE WHEN holiday_date >= ? THEN 1 ELSE 0 END) as upcoming_count,
        SUM(CASE WHEN holiday_date < ? THEN 1 ELSE 0 END) as passed_count
    ", [$today, $today])
                ->whereYear('holiday_date', Carbon::now()->year)
                ->first();
            $holydayDate = Holiday::pluck('holiday_date');
            $holidayTable = Holiday::whereDate('holiday_date', '>=', $today) // Use whereDate to ignore time
                ->orderBy('holiday_date') // Order by date to get the closest holidays first
                ->limit(5) // Limit to the next 5 holidays
                ->get(['holiday_name', 'holiday_date']);

                
            return view('admin.welcome', compact(
                'holidayTable',
                'holydayDate',
                'employee',
                'leavesCount',
                'EmployeeCount',
                'todayPresentCount',
                'salaryCount',
                'holidayCounts'
            ));
        } else {
            $Employee = Employee::where('user_id',Auth::user()->id)->first();
            $Employee->load('departmentDetails', 'userDetails');
            return view('user.dashboard', compact('Employee'));
        }
    }
}
