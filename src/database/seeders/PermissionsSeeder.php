<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    private const SEE_PANEL = 'see-panel';
    private const ROLES_LIST = 'roles-list';
    private const ROLES_CREATE = 'roles-create';
    private const ROLES_EDIT = 'roles-edit';
    private const ROLES_DELETE = 'roles-delete';
    private const USERS_LIST = 'users-list';
    private const USERS_CREATE = 'users-create';
    private const USERS_EDIT = 'users-edit';
    private const USERS_DELETE = 'users-delete';
    private const USERS_DISABLE = 'users-disable';
    private const USERS_ENABLE = 'users-enable';
    private const PATIENT_LIST = 'patient-list';
    private const PATIENT_CREATE = 'patient-create';
    private const PATIENT_EDIT = 'patient-edit';
    private const PATIENT_DELETE = 'patient-delete';
    private const THERAPIST_LIST = 'therapist-list';
    private const THERAPIST_CREATE = 'therapist-create';
    private const THERAPIST_EDIT = 'therapist-edit';
    private const THERAPIST_DELETE = 'therapist-delete';
    private const ACTIVITY_LIST = 'activity-list';
    private const ACTIVITY_CREATE = 'activity-create';
    private const ACTIVITY_EDIT = 'activity-edit';
    private const ACTIVITY_DELETE = 'activity-delete';
    private const ACTIVITY_PATIENT_LIST = 'activity-patient-list';
    private const ACTIVITY_PATIENT_CREATE = 'activity-patient-create';
    private const ACTIVITY_PATIENT_EDIT = 'activity-patient-edit';
    private const ACTIVITY_PATIENT_DELETE = 'activity-patient-delete';

    private $permissions = [
        self::SEE_PANEL,
        self::ROLES_LIST,
        self::ROLES_CREATE,
        self::ROLES_EDIT,
        self::ROLES_DELETE,
        self::USERS_LIST,
        self::USERS_CREATE,
        self::USERS_EDIT,
        self::USERS_DELETE,
        self::USERS_DISABLE,
        self::USERS_ENABLE,
        self::PATIENT_LIST,
        self::PATIENT_CREATE,
        self::PATIENT_EDIT,
        self::PATIENT_DELETE,
        self::THERAPIST_LIST,
        self::THERAPIST_CREATE,
        self::THERAPIST_EDIT,
        self::THERAPIST_DELETE,
        self::ACTIVITY_LIST,
        self::ACTIVITY_CREATE,
        self::ACTIVITY_EDIT,
        self::ACTIVITY_DELETE,
        self::ACTIVITY_PATIENT_LIST,
        self::ACTIVITY_PATIENT_CREATE,
        self::ACTIVITY_PATIENT_EDIT,
        self::ACTIVITY_PATIENT_DELETE,
    ];

    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear todos los permisos
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
        
        // Root tiene TODOS los permisos
        $root = Role::firstOrCreate(['name' => 'root', 'guard_name' => 'web']);
        $root->syncPermissions(Permission::all());

        // Registered: solo ver el panel
        $registered_role = Role::firstOrCreate(['name' => 'registered', 'guard_name' => 'web']);
        $registered_role->syncPermissions([self::SEE_PANEL]);

        // Roles Admin: gestiona roles
        $roles_admin = Role::firstOrCreate(['name' => 'roles-admin', 'guard_name' => 'web']);
        $roles_admin->syncPermissions([
            self::SEE_PANEL,
            self::ROLES_LIST,
            self::ROLES_CREATE,
            self::ROLES_EDIT,
            self::ROLES_DELETE,
        ]);

        // Users Admin: gestiona usuarios
        $users_admin = Role::firstOrCreate(['name' => 'users-admin', 'guard_name' => 'web']);
        $users_admin->syncPermissions([
            self::SEE_PANEL,
            self::USERS_LIST,
            self::USERS_CREATE,
            self::USERS_EDIT,
            self::USERS_DELETE,
            self::USERS_DISABLE,
            self::USERS_ENABLE,
        ]);

        // Therapist: gestiona pacientes y actividades
        $therapist = Role::firstOrCreate(['name' => 'therapist', 'guard_name' => 'web']);
        $therapist->syncPermissions([
            self::SEE_PANEL,
            self::ACTIVITY_CREATE,
            self::ACTIVITY_EDIT,
            self::ACTIVITY_LIST,
            self::ACTIVITY_DELETE,
            self::ACTIVITY_PATIENT_CREATE,
            self::ACTIVITY_PATIENT_DELETE,
            self::ACTIVITY_PATIENT_EDIT,
            self::ACTIVITY_PATIENT_LIST,
            self::PATIENT_CREATE,
            self::PATIENT_DELETE,
            self::PATIENT_EDIT,
            self::PATIENT_LIST,
        ]);

        // Patient: solo puede ver sus propias actividades (no ve asignaciones globales)
        $patient = Role::firstOrCreate(['name' => 'patient', 'guard_name' => 'web']);
        $patient->syncPermissions([
            self::SEE_PANEL,
            self::ACTIVITY_LIST,
        ]);
    }
}