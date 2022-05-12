<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ['name' => 'ROLE_ADMIN', 'description' => 'Administrator'],
            ['name' => 'ROLE_CANDIDATE', 'description' => 'Candidate'],
            ['name' => 'ROLE_COMPANY', 'description' => 'Company']
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@hust.edu.vn',
            'password' => bcrypt('admin@123'),
            'phone' => '20183966',
            'address' => 'Hà Nội',
            'avatar' => '@/assets/images/logo/logo.svg',
            'role_id' => 1,
            'introduction' => 'Administrator',
            'social_network' => null,
            'major' => '',
            'candidate_id' => null,
            'company_id' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
