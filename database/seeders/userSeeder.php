<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $nonAdminUsers = [
            [
            'first_name'=>'harsh',
            'last_name'=>'zala',
            'email'=>'harsh@webcodegenie.com',
            'phone_number'=>1234567890,
            'password'=>Hash::make('harsh@2024')    
            ],
            [
                'first_name'=>'mit',
                'last_name'=>'zala',
                'email'=>'mit@webcodegenie.com',
                'phone_number'=>1234567890,
                'password'=>Hash::make('mit@2024')    
            ],
            [
                'first_name'=>'Krishna',
                'last_name'=>'Patel',
                'email'=>'krishna@webcodegenie.com',
                'phone_number'=>1234567890,
                'password'=>Hash::make('Krishna@2024')    
            ],
            [
                'first_name'=>'Sunil',
                'last_name'=>'Sorani',
                'email'=>'sunil@webcodegenie.com',
                'phone_number'=>1234567890,
                'password'=>Hash::make('Sunil@2024')    
                ]
        ];

        // User::create([
        //     'first_name'=>'Harshvardhan',
        //     'last_name'=>'Zala',
        //     'email'=>'harshvardhan.z@webcodegenie.com',
        //     'phone_number'=>6252814567,
        //     'role'=>1,
        //     'password'=>Hash::make('harshvardhan@2024')
        // ]);
        foreach($nonAdminUsers as $user){
            User::create($user);
        }
    }
}
