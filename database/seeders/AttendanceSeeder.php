<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::get();
        foreach($users as $user){
            if($user->id === '6e538535-157c-48e7-b17c-79b2f873b01b'){
                continue;
            }
            $userId = $user->id;

            // Set the start and end dates for the year 2023
            $startDate = Carbon::create(2024, 2, 1);
            $endDate = Carbon::today();
    
            while ($startDate <= $endDate) {
                Attendance::create([
                    'user_id' => $userId,
                    'attendance_date' => $startDate->format('Y-m-d'),
                    'attendance_status' => 'present',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                // Move to the next day
                $startDate->addDay();
            }
        }
        
    }
}
