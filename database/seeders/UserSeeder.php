<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@campusos.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('password123')]
        );
        $admin->assignRole('Super Admin');

        $student = \App\Models\User::firstOrCreate(
            ['email' => 'student@campusos.com'],
            ['name' => 'Mahasiswa Teladan', 'password' => bcrypt('password123')]
        );
        $student->assignRole('Student');
    }
}
