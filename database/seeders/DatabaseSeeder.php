<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin Boss',
            'username' => 'admin',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        // Normal user
        User::create([
            'name' => 'Rama',
            'username' => 'rama',
            'role' => 'user',
            'password' => bcrypt('123456'),
        ]);

        // Employees
        $employees = [];
        for ($i = 0; $i < 5; $i++) {
            $employees[] = Employee::create([
                'name' => 'Karyawan ' . ($i + 1),
                'rfid_uid' => 'RF' . rand(1000, 9999),
                'finger_id' => rand(1, 100),
                'is_active' => true,
            ]);
        }

        // Generate random attendance data for the past 7 days
        $types = ['absen_finger', 'tap_in_rfid', 'tap_out_rfid'];
        
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->subDays($i);
            
            // Random number of scans per day
            $scanCount = rand(5, 15);
            
            for ($j = 0; $j < $scanCount; $j++) {
                $time = $date->copy()->addHours(rand(7, 18))->addMinutes(rand(0, 59));
                
                Attendance::create([
                    'employee_id' => $employees[array_rand($employees)]->id,
                    'type' => $types[array_rand($types)],
                    'scanned_at' => $time,
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
        
        // Ensure at least someone is inside today for monitoring
        Attendance::create([
            'employee_id' => $employees[0]->id,
            'type' => 'tap_in_rfid',
            'scanned_at' => Carbon::now()->subMinutes(10),
            'created_at' => Carbon::now()->subMinutes(10),
            'updated_at' => Carbon::now()->subMinutes(10),
        ]);
        Attendance::create([
            'employee_id' => $employees[1]->id,
            'type' => 'absen_finger',
            'scanned_at' => Carbon::now()->subMinutes(50),
            'created_at' => Carbon::now()->subMinutes(50),
            'updated_at' => Carbon::now()->subMinutes(50),
        ]);
    }
}
