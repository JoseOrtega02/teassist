<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate users table before seeding
        DB::table('users')->truncate();

        User::factory()->rootUser()->create()->assignRole('root');
        User::factory()->create()->assignRole('roles-admin');
        User::factory()->create()->assignRole('users-admin');

        User::factory()->count(7)->create()->each(function ($user) {
            $user->assignRole('registered');
        });

    }
}
