<?php

namespace App\Http\Controllers;

use App\Jobs\AddAttendanceForLeave;
use App\Jobs\UpdateDeductionOnLeave;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeaveApprovalController extends Controller
{
    public function listLeaveData(Request $request)
    {
        // Get filter and search inputs
        $filter = $request->input('filter', 'name'); // Default filter is 'name'
        $search = $request->input('search', '');



        $leaveList = Leave::with('getLeaveType', 'getEmployee.userDetails', 'getEmployee.departmentDetails')->where('leave_status','pending');
        
        if ($search) {
            switch ($filter) {
                case 'department':
                    $leaveList->whereHas('getEmployee.departmentDetails', function ($q) use ($search) {
                        $q->where('department_name', 'like', '%' . $search . '%');
                    });
                    break;
                case 'leave_type':
                    $leaveList->whereHas('getLeaveType',function ($q) use ($search){
                        $q->where('leave_type_name', 'like', '%' . $search . '%');
                    });
                    break;
                case 'name':
                default:
                    $leaveList->whereHas('getEmployee.userDetails', function ($q) use ($search) {
                        $q->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%');
                    });
                    break;
            }
        }
        
        $leaveList = $leaveList->paginate(12);
        return view('admin.leaveApproval', compact('leaveList'));
    }

    public function viewLeave($id)
    {
        $leaveData = Leave::with('getLeaveType', 'getEmployee.userDetails', 'getEmployee.departmentDetails')->find($id);
        return response()->json($leaveData);
    }

    public function leaveStatusUpdate($id, Request $request)
    {
        $leave = Leave::find($id);
        Log::info($leave);
        $employeeId = $leave->employee_id;
        $employee = Employee::find($employeeId);
        $user_id = $employee->user_id;
        $salary = $employee->current_salary;
        if (!$leave) {
            return response()->json(['error' => 'Leave not found'], 404);
        }

        if ($request->query('status') == 'approve') {
            $leave->update(['leave_status' => 'approved']);
            AddAttendanceForLeave::dispatch($leave->id,$user_id); // job for make attendance of leave days absent
            UpdateDeductionOnLeave::dispatch($employeeId,$user_id,$salary,$leave);
        } elseif ($request->query('status') == 'reject') {
            $leave->update(['leave_status' => 'rejected']);
        } else {
            return response()->json(['error' => 'Invalid status'], 400);
        }
        return response()->json(['message' => 'leave status updated successfully']);
    }

    public function index()
    {
        $employeeId = Employee::where('user_id', Auth::user()->id)->first()->id;
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get the leave type IDs for Casual Leave and Medical Leave
        $casualLeaveId = LeaveType::where('leave_type_name', 'Casual Leave')->value('id');
        $medicalLeaveId = LeaveType::where('leave_type_name', 'Medical Leave')->value('id');

        // Get the total number of leave days for Casual Leave
        $casualLeaveDays = Leave::where('leave_type_id', $casualLeaveId)
            ->where('employee_id', $employeeId)
            ->whereYear('start_date', $currentYear)
            ->selectRaw('SUM(leave_days) as year_leave_days')
            ->selectRaw("SUM(CASE WHEN MONTH(start_date) = $currentMonth THEN leave_days ELSE 0 END) as month_leave_days")
            ->first();

        // Get the total number of leave days for Medical Leave
        $medicalLeaveDays = Leave::where('leave_type_id', $medicalLeaveId)
            ->where('employee_id', $employeeId)
            ->whereYear('start_date', $currentYear)
            ->selectRaw('SUM(leave_days) as year_leave_days')
            ->selectRaw("SUM(CASE WHEN MONTH(start_date) = $currentMonth THEN leave_days ELSE 0 END) as month_leave_days")
            ->first();

        $leave = [
            'casual_leave' => $casualLeaveDays,
            'medical_leave' => $medicalLeaveDays
        ];
        return view('user.leave-page', compact('leave', 'employeeId'));
    }


    public function createLeave()
    {
        $leaveTypes = LeaveType::get();
        return view('user.leave-form', compact('leaveTypes'));
    }

    public function leaveStore(Request $request)
    {
        $employeeId = Employee::where('user_id', Auth::user()->id)->first()->id;
        Leave::create([
            'employee_id' => $employeeId,
            'leave_type_id' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'applied_date' => Carbon::now(),
            'leave_status' => 'pending',
            'reason' => $request->reason,
        ]);
        return redirect('leave-page');
    }

    public function getLeaveData($id)
    {
        // Find the employee by the given ID
        $Employee = Employee::find($id);

        if (!$Employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        // Load the 'leave' relationship and filter by the current month and year
        $leaves = $Employee->leave()
            ->with('getLeaveType')->orderBy('start_date','desc')->get();

        // Return the leave data as JSON response
        return response()->json($leaves);
    }
}
