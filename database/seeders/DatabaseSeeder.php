<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Departments
        $dept1 = Department::create([
            'Nama_departemen' => 'IT (Information Technology)',
            'Keterangan' => 'Handles hardware, software, networking, and system development.',
        ]);

        $dept2 = Department::create([
            'Nama_departemen' => 'HR (Human Resources)',
            'Keterangan' => 'Manages employee recruitment, benefits, training, and relations.',
        ]);

        $dept3 = Department::create([
            'Nama_departemen' => 'Finance & Accounting',
            'Keterangan' => 'Manages corporate financial planning, tracking, and reports.',
        ]);

        $depts = [$dept1, $dept2, $dept3];

        // 2. Seed Users
        User::create([
            'Nama' => 'Admin Boss',
            'Username' => 'admin',
            'Email' => 'admin@example.com',
            'Role' => 'admin',
            'Password' => bcrypt('admin123'),
            'Status' => 'Aktif',
        ]);

        User::create([
            'Nama' => 'Rama',
            'Username' => 'rama',
            'Email' => 'rama@example.com',
            'Role' => 'user',
            'Password' => bcrypt('123456'),
            'Status' => 'Aktif',
        ]);

        // 3. Seed Employees (Karyawan)
        $employees = [];
        $genders = ['Laki-laki', 'Perempuan'];
        $jabatans = ['Staff', 'Supervisor', 'Manager', 'Lead Developer', 'Accountant'];

        for ($i = 0; $i < 5; $i++) {
            $employees[] = Employee::create([
                'Nip' => 'NIP' . (10000 + $i),
                'Nama' => 'Karyawan ' . ($i + 1),
                'Jenis_Kelamin' => $genders[$i % 2],
                'Jabatan' => $jabatans[$i],
                'id_departemen' => $depts[$i % 3]->id_departemen,
                'id_fingerprint' => (string)rand(1, 100),
                'id_RFID' => 'RF' . rand(1000, 9999),
                'Tanggal_bergabung' => Carbon::now()->subMonths(rand(6, 24))->toDateString(),
                'Status' => 'aktif',
            ]);
        }

        // 4. Generate random attendance data for the past 7 days
        $statuses = ['hadir', 'telat', 'izin', 'sakit'];

        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->subDays($i);
            
            // Skip weekends
            if ($date->isWeekend()) {
                continue;
            }

            foreach ($employees as $employee) {
                // 85% chance of showing up/having an attendance log
                if (rand(1, 100) > 15) {
                    $status = rand(1, 100) > 20 ? 'hadir' : 'telat';
                    
                    // Determine arrival time
                    if ($status == 'hadir') {
                        // Before 07:00
                        $hour = rand(6, 6);
                        $minute = rand(0, 59);
                    } else {
                        // After 07:00 (late)
                        $hour = 7;
                        $minute = rand(1, 45); // late by 1 to 45 mins
                    }

                    $jamMasuk = sprintf('%02d:%02d:00', $hour, $minute);
                    
                    // Check out time
                    $jamKeluar = sprintf('16:%02d:00', rand(0, 59));
                    
                    // Calculate duration in minutes
                    $inTime = Carbon::createFromFormat('H:i:s', $jamMasuk);
                    $outTime = Carbon::createFromFormat('H:i:s', $jamKeluar);
                    $duration = $outTime->diffInMinutes($inTime);

                    Attendance::create([
                        'id_karyawan' => $employee->id_Karyawan,
                        'Tanggal' => $date->toDateString(),
                        'Jam_masuk' => $jamMasuk,
                        'Jam_keluar' => $jamKeluar,
                        'Durasi' => $duration,
                        'Status' => $status,
                        'Keterangan' => $status == 'telat' ? 'Terlambat masuk kerja' : 'Hadir tepat waktu',
                    ]);
                } else {
                    // Sick or Permitted (izin)
                    $status = rand(1, 2) == 1 ? 'izin' : 'sakit';
                    
                    Attendance::create([
                        'id_karyawan' => $employee->id_Karyawan,
                        'Tanggal' => $date->toDateString(),
                        'Jam_masuk' => null,
                        'Jam_keluar' => null,
                        'Durasi' => 0,
                        'Status' => $status,
                        'Keterangan' => $status == 'izin' ? 'Izin keperluan keluarga' : 'Sakit dengan surat dokter',
                    ]);
                }
            }
        }
        
        // Ensure at least two employees check-in today for active/inside list
        // Employee 0: Checked-in, not checked-out (inside)
        Attendance::create([
            'id_karyawan' => $employees[0]->id_Karyawan,
            'Tanggal' => Carbon::today()->toDateString(),
            'Jam_masuk' => '06:45:00',
            'Jam_keluar' => null,
            'Durasi' => 0,
            'Status' => 'hadir',
            'Keterangan' => 'Check-in pagi',
        ]);

        // Employee 1: Checked-in late, not checked-out (inside)
        Attendance::create([
            'id_karyawan' => $employees[1]->id_Karyawan,
            'Tanggal' => Carbon::today()->toDateString(),
            'Jam_masuk' => '07:15:00',
            'Jam_keluar' => null,
            'Durasi' => 0,
            'Status' => 'telat',
            'Keterangan' => 'Terlambat 15 menit',
        ]);
    }
}
