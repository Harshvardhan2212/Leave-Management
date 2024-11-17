<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Salary;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    public function getSalary($id, Request $request)
    {
        // Find the employee by ID
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        // Determine the start and end date based on the duration parameter
        switch ($request->input('duration')) {
            case 'current_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;

            case 'last_6_months':
                $startDate = now()->subMonths(6)->startOfMonth();
                $endDate = now()->endOfMonth();
                break;

            case 'full_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;

            default:
                return response()->json(['error' => 'Invalid duration parameter'], 400);
        }

        // Query the salary details within the specified date range
        $salaryDetails = $employee->salaryDetails()
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->get();

        if ($salaryDetails->isEmpty()) {
            return response()->json(['message' => 'No salary records found for the given duration'], 404);
        }

        // Prepare the salary data for the response
        $salaryChart = [
            'payment_date' => $salaryDetails->pluck('payment_date'),
            'basic_salary' => $salaryDetails->pluck('basic_salary'),
            'allowances'   => $salaryDetails->pluck('allowances'),
            'deductions'   => $salaryDetails->pluck('deductions'),
        ];

        return response()->json($salaryChart);
    }


    public function salaryPage()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view your salary page.');
        }

        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Retrieve the employee ID for the authenticated user
        $employee = Employee::where('user_id', Auth::user()->id)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        $employeeId = $employee->id;

        // Get the count of absent days for the current month and year
        $leaveCount = Attendance::whereMonth('attendance_date', $currentMonth)
            ->whereYear('attendance_date', $currentYear)
            ->where('attendance_status', 'absent')
            ->count();

        // Get the salary record for the current month and year
        $salary = Salary::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('employee_id', $employeeId)
            ->first();
        // Handle the case where no salary record is found
        if (!$salary) {

            $salary = [
                'basic_salary' => 0,
                'allowances' => 0,
                'deductions' => 0,
                'net_salary' => 0,
                'payment_date' => null,
                'payment_status' => 'pending'
            ];
        }
        // Return the view with all necessary data
        return view('user.salary-page', compact('employeeId', 'leaveCount', 'salary'));
    }

    public function getSalaryDetailsForForm(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $salaryDetails = Salary::where('employee_id', $request->employeeId)->whereMonth('created_at', $currentMonth)->first();
        return response()->json($salaryDetails);
    }

    public function salaryEditFormHandle(Request $request, $employeeId)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        Salary::where('employee_id')->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->update([
                'basic_salary' => $request->basicSalary,
                'allowances' => $request->allowances,
                'deductions' => $request->deduction,
                'payment_date' => $request->paymentDate
            ]);
        return response()->json(['message' => 'Salary Edited successfully']);
    }

    public function getPayslip(Request $request)
    {
        $employeeId = $request->employeeId;
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $data = Salary::where('employee_id', $employeeId)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->with('employee.userDetails', 'employee.departmentDetails')
            ->first();
        return response()->json($data);
    }
    public function downloadPayslip(Request $request)
    {
        $employeeId = $request->employeeId;
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $data = Salary::where('employee_id', $employeeId)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->with('employee.userDetails', 'employee.departmentDetails')
            ->first();

        $result = [
            'firstName' => $data->employee->userDetails->first_name . " " . $data->employee->userDetails->last_name,
            'designation' => $data->employee->designation,
            'department' => $data->employee->departmentDetails->department_name,
            'basicSalary' => $data->basic_salary,
            'allowances' => $data->allowances,
            'deductions' => $data->deductions,
            'netSalary' => $data->net_salary,
            'paymentDate' => Carbon::parse($data->payment_date)->format('d F Y'),
            'paymentStatus' => $data->payment_status,
        ];
        $pdf = FacadePdf::loadView('payslip', $result);
        return $pdf->download('payslip.pdf');
    }
}
