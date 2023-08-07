<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() == 0) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'info@employeerating.live',
                'password' => bcrypt('Admin123,.'),
                'is_admin' => 1,
                'status' => 1,
            ]);

        }
    }
}
    