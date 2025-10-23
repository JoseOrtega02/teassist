<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate users table before seeding
        DB::table('users')->truncate();

        // Asegurar que el rol root tenga todos los permisos
        $rootRole = Role::firstOrCreate(['name' => 'root', 'guard_name' => 'web']);
        $rootRole->givePermissionTo(Permission::all());

        // Obtener otros roles por nombre
        $rolesAdminRole = Role::where('name', 'roles-admin')->first();
        $usersAdminRole = Role::where('name', 'users-admin')->first();
        $registeredRole = Role::where('name', 'registered')->first();
        $therapistRole = Role::where('name', 'therapist')->first();

        // Crear usuario root
        $rootUser = User::factory()->rootUser()->create(['role' => $rootRole->id]);
        $rootUser->assignRole('root');

        // Crear admin de roles
        $rolesAdmin = User::factory()->create(['role' => $rolesAdminRole?->id]);
        $rolesAdmin->assignRole('roles-admin');

        // Crear admin de usuarios
        $usersAdmin = User::factory()->create(['role' => $usersAdminRole?->id]);
        $usersAdmin->assignRole('users-admin');

        // Crear usuario Terapeuta
        
        $therapistUser = User::factory()->therapistUser()->create(['role' => $therapistRole?->id]);
        $therapistUser->assignRole('therapist');

        // Crear usuarios registrados
        User::factory()->count(7)->create()->each(function ($user) use ($registeredRole) {
            $user->update(['role' => "registered"]);
            $user->assignRole('registered');
        });
    }
}
