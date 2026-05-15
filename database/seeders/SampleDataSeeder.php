<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Due;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $programmes = [
            'Geomatic Engineering',
            'Land Administration',
            'Spatial Planning'
        ];

        $academicYear = '2025/2026';
        
        $students = [];
        
        // Create 30 students
        for ($i = 1; $i <= 30; $i++) {
            $programme = $programmes[array_rand($programmes)];
            $year = (string) rand(1, 4);
            $firstName = ['Kwame', 'Kofi', 'Yaw', 'Ama', 'Akua', 'Afia', 'Ekow', 'Nana', 'Panyin', 'Kakiri'][array_rand(['Kwame', 'Kofi', 'Yaw', 'Ama', 'Akua', 'Afia', 'Ekow', 'Nana', 'Panyin', 'Kakiri'])];
            $lastName = ['Osei', 'Appiah', 'Mensah', 'Boateng', 'Asare', 'Dankwa', 'Gyamfi', 'Kyeremeh', 'Antwi', 'Baffour'][array_rand(['Osei', 'Appiah', 'Mensah', 'Boateng', 'Asare', 'Dankwa', 'Gyamfi', 'Kyeremeh', 'Antwi', 'Baffour'])];
            
            $fullname = $firstName . ' ' . $lastName;
            $username = strtolower($firstName) . $i;
            
            $student = User::create([
                'username' => $username,
                'fullname' => $fullname,
                'email' => $username . '@example.com',
                'password' => Hash::make('password'),
                'phone_number' => '024' . rand(1000000, 9999999),
                'index_number' => rand(10000000, 99999999),
                'class' => $programme,
                'year' => $year,
                'role' => 'student',
                'is_graduated' => false,
                'email_verified_at' => now(),
            ]);
            
            $students[] = $student;
        }

        // Create Dues for each student
        foreach ($students as $student) {
            $statusSeed = rand(1, 10);
            
            // Status distribution: 60% Paid, 20% Owing, 20% Pending
            if ($statusSeed <= 6) {
                $status = 'paid';
            } elseif ($statusSeed <= 8) {
                $status = 'owing';
            } else {
                $status = 'pending_verification';
            }

            Due::create([
                'student_id' => $student->user_id,
                'description' => 'GESA Annual Dues',
                'amount' => 150.00,
                'academic_year' => $academicYear,
                'payment_status' => $status,
                'due_date' => now()->subMonths(2),
                'payment_method' => $status !== 'owing' ? 'Mobile Money' : null,
                'payment_reference' => $status !== 'owing' ? 'TXN' . strtoupper(Str::random(8)) : null,
                'payment_date' => $status !== 'owing' ? now()->subDays(rand(1, 30)) : null,
                'verification_date' => $status === 'paid' ? now()->subDays(rand(1, 10)) : null,
                'is_active' => true,
            ]);

            // Add a second due (e.g. SRC Dues) for some students
            if (rand(1, 2) === 1) {
                Due::create([
                    'student_id' => $student->user_id,
                    'description' => 'SRC Development Fund',
                    'amount' => 50.00,
                    'academic_year' => $academicYear,
                    'payment_status' => rand(1, 2) === 1 ? 'paid' : 'owing',
                    'due_date' => now()->subMonths(1),
                    'payment_method' => 'Cash',
                    'is_active' => true,
                ]);
            }
        }
    }
}
