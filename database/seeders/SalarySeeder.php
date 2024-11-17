<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Salary;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::get();
        $today = Carbon::now();
        foreach ($users as $user) {
            $employee = Employee::where('user_id', $user->id)->first();
            if ($user->id === '6e538535-157c-48e7-b17c-79b2f873b01b') {
                continue;
            }
            Salary::create([
                'employee_id'=>$employee->id,
                'basic_salary'=>$employee->current_salary,
                'allowances'=>,
                'deductions',
                'net_salary',
                'payment_date',
                'payment_status',
            ]);
        }
    }
}
