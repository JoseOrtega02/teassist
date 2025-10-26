# 🔐 Guía Completa: Sistema de Roles y Permisos con Spatie

## 📋 Índice
1. [Introducción](#introducción)
2. [Estructura de la Base de Datos](#estructura-de-la-base-de-datos)
3. [Modelos y Relaciones](#modelos-y-relaciones)
4. [Seeders: Roles, Permisos y Usuarios](#seeders-roles-permisos-y-usuarios)
5. [Controladores y Lógica de Negocio](#controladores-y-lógica-de-negocio)
6. [Rutas y Middlewares](#rutas-y-middlewares)
7. [Vistas Blade y Directivas](#vistas-blade-y-directivas)
8. [Flujo Completo: Ejemplo Therapist](#flujo-completo-ejemplo-therapist)
9. [Testing y Verificación](#testing-y-verificación)
10. [Comandos Útiles](#comandos-útiles)

---

## 🎯 Introducción

Este proyecto implementa un **sistema de control de acceso basado en roles y permisos** (RBAC) usando el paquete **Spatie Laravel Permission**.

### Roles implementados:
- **root**: Super administrador (todos los permisos)
- **users-admin**: Administrador de usuarios
- **roles-admin**: Administrador de roles
- **therapist**: Terapeuta (gestiona pacientes y actividades)
- **patient**: Paciente (login por código; solo ve sus actividades asignadas)
- **registered**: Usuario registrado básico

### Tecnologías:
- Laravel 11
- Spatie Laravel Permission 6.9
- Laravel Jetstream (autenticación)
- Livewire 3.0
---

## 📊 Estructura de la Base de Datos

### Tablas principales:

```sql
-- Tabla de usuarios (Laravel default + columna role)
users
├── id
├── name
├── email
├── password
├── role (string) -- Columna adicional para consultas rápidas
├── created_at
└── updated_at

-- Tabla de terapeutas (perfil extendido)
therapists
├── id
├── user_id (FK -> users.id) -- Relación 1:1 con User
├── nombres
├── apellidos
├── dni (unique)
├── nacimiento
├── sexo
├── telefono
├── email (unique)
├── direccion

-- Tabla de pacientes
patients
├── id
├── user_id (FK -> users.id) -- Relación 1:1 con User
├── codigo (unique)
├── nacimiento
├── telefono
├── email (unique)
├── direccion
├── observaciones
├── created_at
└── updated_at

-- Tabla pivot paciente-terapeuta (many-to-many)
patient_therapist
├── id
├── patient_id (FK -> patients.id)
├── therapist_id (FK -> therapists.id)
├── created_at
└── updated_at
├── codigo (unique)
├── nacimiento
├── telefono
├── email (unique)
├── direccion
├── observaciones
├── created_at
└── updated_at

-- Tablas de Spatie (auto-generadas)
roles
├── id
├── name
├── guard_name
└── ...

permissions
├── id
├── name
├── guard_name
└── ...

model_has_roles (tabla pivot)
model_has_permissions (tabla pivot)
role_has_permissions (tabla pivot)
```

### Diagrama de relaciones:

```
User (1) ──── (1) Therapist
                      │
                      │ therapist_id
                      │
                      │
                      │  (many-to-many via patient_therapist)
                      │
                      └─── (N) Patient ──── (1) User
```

---

## 🏗️ Modelos y Relaciones

### 1. User Model (`app/Models/User.php`)

```php
<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasRoles; // ← Trait de Spatie para roles/permisos

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // ← Columna adicional para filtros rápidos
    ];

    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    // Relación 1:1 con Therapist
    public function therapist()
    {
        return $this->hasOne(Therapist::class);
    }

    // Relación 1:1 con Patient
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }
}
```

**¿Por qué la columna `role`?**
- Spatie usa tablas pivot para roles (más flexible pero más lento)
- La columna `role` permite consultas rápidas: `WHERE role = 'therapist'`
- Es redundante pero optimiza queries frecuentes

---

### 2. Therapist Model (`app/Models/Therapist.php`)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Therapist extends Model
{
    protected $fillable = [
        'user_id',
        'nombres',
        'apellidos',
        'dni',
        'nacimiento',
        'sexo',
        'telefono',
        'email',
        'direccion',
    ];

    // Relación N:1 con User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación 1:N con Patient
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'therapist_id');
    }
}
```

**Uso de relaciones:**
```php
// Obtener usuario del terapeuta
$therapist->user->name;

// Obtener pacientes del terapeuta
$therapist->patients;

// Contar pacientes
$therapist->patients()->count();
```

---

## 🌱 Seeders: Roles, Permisos y Usuarios

### 1. PermissionsSeeder (`database/seeders/PermissionsSeeder.php`)

**Responsabilidad:** Crear todos los roles y asignar permisos.

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    // Definir permisos como constantes
    private const SEE_PANEL = 'see-panel';
    private const THERAPIST_LIST = 'therapist-list';
    private const THERAPIST_CREATE = 'therapist-create';
    private const THERAPIST_EDIT = 'therapist-edit';
    private const THERAPIST_DELETE = 'therapist-delete';
    // ... más permisos

    public function run(): void
    {
        // Limpiar caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear todos los permisos
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
        
        // Crear rol ROOT con TODOS los permisos
        $root = Role::firstOrCreate(['name' => 'root', 'guard_name' => 'web']);
        $root->syncPermissions(Permission::all());

        // Crear rol THERAPIST con permisos específicos
        $therapist = Role::firstOrCreate(['name' => 'therapist', 'guard_name' => 'web']);
        $therapist->syncPermissions([
            self::SEE_PANEL,
            self::PATIENT_LIST,
            self::PATIENT_CREATE,
            self::PATIENT_EDIT,
            self::PATIENT_DELETE,
            self::ACTIVITY_LIST,
            self::ACTIVITY_CREATE,
            // ... más permisos
        ]);

        // Crear rol PATIENT con permisos limitados
        $patient = Role::firstOrCreate(['name' => 'patient', 'guard_name' => 'web']);
        $patient->syncPermissions([
            self::SEE_PANEL,
            self::ACTIVITY_LIST,
        ]);
    }
}
```

**Orden de ejecución:**
1. Se ejecuta **PRIMERO** en `DatabaseSeeder`
2. Crea permisos y roles antes de crear usuarios

---

### 2. UsersSeeder (`database/seeders/UsersSeeder.php`)

**Responsabilidad:** Crear usuarios administrativos (root, admins).

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->truncate();

        // Crear usuario ROOT
        $rootRole = Role::firstOrCreate(['name' => 'root', 'guard_name' => 'web']);
        $rootRole->givePermissionTo(Permission::all()); // Todos los permisos

        $rootUser = User::factory()->rootUser()->create(['role' => 'root']);
        $rootUser->assignRole('root');

        // Crear admin de usuarios
        $usersAdmin = User::factory()->create(['role' => 'users-admin']);
        $usersAdmin->assignRole('users-admin');

        // Crear usuarios registrados
        User::factory()->count(7)->create()->each(function ($user) {
            $user->update(['role' => 'registered']);
            $user->assignRole('registered');
        });
    }
}
```

---

### 3. TherapistSeeder (`database/seeders/TherapistSeeder.php`)

**Responsabilidad:** Crear usuarios therapist + perfiles de terapeuta.

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Therapist;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TherapistSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el rol therapist
        $therapistRole = Role::where('name', 'therapist')->first();

        // Crear 10 usuarios therapist
        $users = User::factory()
            ->count(10)
            ->create(['role' => 'therapist']);

        foreach ($users as $user) {
            // Asignar rol therapist (Spatie)
            $user->assignRole('therapist');

            // Crear perfil de terapeuta
            Therapist::create([
                'user_id' => $user->id,
                'nombres' => explode(' ', $user->name)[0],
                'apellidos' => explode(' ', $user->name)[1] ?? 'Apellido',
                'dni' => fake()->unique()->numerify('########'),
                'nacimiento' => fake()->date(),
                'sexo' => fake()->randomElement(['M', 'F']),
                'telefono' => fake()->phoneNumber(),
                'email' => $user->email,
                'direccion' => fake()->address(),
            ]);
        }
    }
}
```

**Importante:**
- Crea **User** primero (con `role = 'therapist'`)
- Asigna **rol de Spatie** con `assignRole()`
- Crea **perfil Therapist** vinculado al usuario

---

### 4. DatabaseSeeder (`database/seeders/DatabaseSeeder.php`)

**Responsabilidad:** Orquestar el orden de ejecución.

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        fake()->seed(10); // Semilla para datos consistentes

        // ⚠️ ORDEN IMPORTANTE:
        $this->call(PermissionsSeeder::class);  // 1. Primero roles y permisos
        $this->call(UsersSeeder::class);         // 2. Usuarios admin
        $this->call(TherapistSeeder::class);     // 3. Terapeutas
        $this->call(PatientSeeder::class);       // 4. Pacientes
        $this->call(ActivitySeeder::class);      // 5. Actividades
        $this->call(PatientActivitySeeder::class); // 6. Asignaciones
    }
}
```

**Ejecutar seeders:**
```powershell
# Limpiar BD y seedear todo
php artisan migrate:fresh --seed

# Solo seedear (sin borrar)
php artisan db:seed

# Seedear una clase específica
php artisan db:seed --class=TherapistSeeder
```

---

## 🎮 Controladores y Lógica de Negocio

### TherapistController (`app/Http/Controllers/TherapistController.php`)

**Responsabilidad:** CRUD de terapeutas con validación de permisos.

#### 1. **index()** - Listar terapeutas

```php
public function index()
{
    // Verificar permiso
    abort_unless(auth()->user()->can('therapist-list'), 403);

    // Cargar relación 'user' para evitar N+1 queries
    $data = Therapist::with('user')->latest()->paginate(15);
    
    return view('therapists.index', compact('data'));
}
```

**¿Qué hace?**
- Verifica que el usuario tenga el permiso `therapist-list`
- Si no tiene permiso, lanza error 403 (Forbidden)
- Carga terapeutas con eager loading (`with('user')`)
- Pagina resultados (15 por página)

---

#### 2. **create()** - Mostrar formulario

```php
public function create()
{
    abort_unless(auth()->user()->can('therapist-create'), 403);
    
    return view('therapists.create');
}
```

---

#### 3. **store()** - Crear terapeuta

```php
public function store(Request $request)
{
    abort_unless(auth()->user()->can('therapist-create'), 403);

    // Validación completa
    $validated = $request->validate([
        'nombres' => 'required|string|max:255',
        'dni' => 'required|string|size:8|unique:therapists,dni',
        'user_email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        // ... más reglas
    ]);

    DB::beginTransaction(); // ← Transacción para integridad

    try {
        // 1. Crear usuario
        $user = User::create([
            'name' => $validated['user_name'],
            'email' => $validated['user_email'],
            'password' => Hash::make($validated['password']),
            'role' => 'therapist', // ← Columna rápida
        ]);

        // 2. Asignar rol Spatie
        $user->assignRole('therapist');

        // 3. Crear perfil terapeuta
        $therapist = Therapist::create([
            'user_id' => $user->id,
            'nombres' => $validated['nombres'],
            'dni' => $validated['dni'],
            // ... más campos
        ]);

        DB::commit(); // ← Confirmar transacción

        return redirect()
            ->route('therapists.index')
            ->with('success', 'Terapeuta creado exitosamente');

    } catch (\Exception $e) {
        DB::rollBack(); // ← Revertir si hay error
        
        return back()
            ->withInput()
            ->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }
}
```

**¿Por qué transacciones?**
- Si falla la creación del `Therapist`, el `User` también se revierte
- Mantiene integridad referencial
- Evita usuarios huérfanos o terapeutas sin usuario

---

#### 4. **update()** - Actualizar terapeuta

```php
public function update(Request $request, Therapist $therapist)
{
    abort_unless(auth()->user()->can('therapist-edit'), 403);

    $validated = $request->validate([
        'nombres' => 'required|string|max:255',
        'dni' => 'required|string|size:8|unique:therapists,dni,' . $therapist->id,
        'password' => 'nullable|string|min:8|confirmed', // ← Opcional
        // ... más reglas
    ]);

    DB::beginTransaction();

    try {
        // Actualizar terapeuta
        $therapist->update([
            'nombres' => $validated['nombres'],
            'dni' => $validated['dni'],
            // ...
        ]);

        // Actualizar usuario (opcional)
        if ($therapist->user) {
            $userData = [];

            if (!empty($validated['user_email'])) {
                $userData['email'] = $validated['user_email'];
            }

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            if (!empty($userData)) {
                $therapist->user->update($userData);
            }
        }

        DB::commit();

        return redirect()
            ->route('therapists.show', $therapist->id)
            ->with('success', 'Terapeuta actualizado exitosamente');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
```

---

#### 5. **destroy()** - Eliminar terapeuta

```php
public function destroy(Therapist $therapist)
{
    abort_unless(auth()->user()->can('therapist-delete'), 403);

    DB::beginTransaction();

    try {
        // Eliminar usuario asociado (cascade)
        if ($therapist->user) {
            $therapist->user->delete();
        }

        // Eliminar terapeuta
        $therapist->delete();

        DB::commit();

        return redirect()
            ->route('therapists.index')
            ->with('success', 'Terapeuta eliminado exitosamente');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
```

**Cascada de eliminación:**
1. Elimina `User` primero
2. Laravel elimina automáticamente el `Therapist` (si está configurado `onDelete('cascade')`)
3. También elimina asignaciones de roles en tablas pivot de Spatie

---

## 🛣️ Rutas y Middlewares

### Archivo de rutas (`routes/web.php`)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TherapistController;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {
    
    // Dashboard
    Route::middleware('permission:see-panel')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });

    // Gestión de terapeutas (solo root)
    Route::middleware('permission:therapist-list')
        ->prefix('therapists')
        ->name('therapists.')
        ->group(function () {
            
            Route::get('/', [TherapistController::class, 'index'])
                ->name('index');
            
            Route::get('/create', [TherapistController::class, 'create'])
                ->middleware('permission:therapist-create')
                ->name('create');
            
            Route::post('/', [TherapistController::class, 'store'])
                ->middleware('permission:therapist-create')
                ->name('store');
            
            Route::get('/{therapist}', [TherapistController::class, 'show'])
                ->name('show');
            
            Route::get('/{therapist}/edit', [TherapistController::class, 'edit'])
                ->middleware('permission:therapist-edit')
                ->name('edit');
            
            Route::put('/{therapist}', [TherapistController::class, 'update'])
                ->middleware('permission:therapist-edit')
                ->name('update');
            
            Route::delete('/{therapist}', [TherapistController::class, 'destroy'])
                ->middleware('permission:therapist-delete')
                ->name('destroy');
        });
});
```

### Capas de seguridad:

```
1. Middleware: auth + verified
   ↓
2. Middleware: permission:therapist-list (grupo)
   ↓
3. Middleware: permission:therapist-create (ruta específica)
   ↓
4. Controller: abort_unless() (doble verificación)
   ↓
5. Blade: @can() (UI condicional)
```

**¿Por qué 3 capas?**
- **Middleware en rutas:** Primera línea de defensa (bloquea antes de llegar al controlador)
- **Verificación en controlador:** Seguridad adicional (por si alguien llama directamente)
- **Directivas Blade:** Experiencia de usuario (oculta botones no disponibles)

---

## 🎨 Vistas Blade y Directivas

### 1. Navigation Menu (`resources/views/navigation-menu.blade.php`)

**Menú dinámico con permisos:**

```blade
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    {{-- Solo usuarios con permiso see-panel ven el dashboard --}}
    @can('see-panel')
        <x-nav-link href="{{ route('dashboard') }}">
            {{ __('Inicio') }}
        </x-nav-link>
    @endcan

    {{-- Solo usuarios con permiso patient-list ven pacientes --}}
    @can('patient-list')
        <x-nav-link href="{{ route('patients.index') }}">
            {{ __('Pacientes') }}
        </x-nav-link>
    @endcan

    {{-- Solo root puede ver terapeutas --}}
    @can('therapist-list')
        <x-nav-link href="{{ route('therapists.index') }}">
            {{ __('Terapeutas') }}
        </x-nav-link>
    @endcan

    {{-- Solo admins ven usuarios --}}
    @can('users-list')
        <x-nav-link href="{{ route('users.index') }}">
            {{ __('Usuarios') }}
        </x-nav-link>
    @endcan
</div>
```

**Badges de rol:**

```blade
<div class="font-medium text-base text-gray-800 flex items-center">
    {{ Auth::user()->name }}
    
    @role('root')
        <span class="ml-2 px-2 py-0.5 text-xs bg-red-100 text-red-800 rounded">
            ROOT
        </span>
    @endrole
    
    @role('therapist')
        <span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded">
            Terapeuta
        </span>
    @endrole
    
    @role('patient')
        <span class="ml-2 px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">
            Paciente
        </span>
    @endrole
</div>
```

---

### 2. Index de Terapeutas (`resources/views/therapists/index.blade.php`)

**Botón crear (condicional):**

```blade
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Terapeutas</h2>
    
    {{-- Solo usuarios con permiso therapist-create ven el botón --}}
    @can('therapist-create')
        <a href="{{ route('therapists.create') }}"
            class="btn btn-primary">
            Nuevo Terapeuta
        </a>
    @endcan
</div>
```

**Acciones por fila (condicionales):**

```blade
<td class="px-4 py-3">
    <div class="flex items-center space-x-3">
        {{-- Ver detalles: todos pueden --}}
        <a href="{{ route('therapists.show', $therapist->id) }}"
            title="Ver detalles">
            <svg>...</svg>
        </a>

        {{-- Editar: solo con permiso --}}
        @can('therapist-edit')
            <a href="{{ route('therapists.edit', $therapist->id) }}"
                title="Editar">
                <svg>...</svg>
            </a>
        @endcan

        {{-- Eliminar: solo con permiso --}}
        @can('therapist-delete')
            <form action="{{ route('therapists.destroy', $therapist->id) }}" 
                method="POST"
                onsubmit="return confirm('¿Estás seguro?');">
                @csrf
                @method('DELETE')
                <button type="submit" title="Eliminar">
                    <svg>...</svg>
                </button>
            </form>
        @endcan
    </div>
</td>
```

---

### 3. Directivas disponibles de Spatie

```blade
{{-- Verificar ROL --}}
@role('root')
    <p>Solo root puede ver esto</p>
@endrole

@role('therapist|patient')
    <p>Therapist o patient pueden ver esto</p>
@endrole

@unlessrole('root')
    <p>Todos MENOS root</p>
@endunlessrole

{{-- Verificar PERMISO --}}
@can('users-create')
    <a href="{{ route('users.create') }}">Crear Usuario</a>
@endcan

@cannot('users-delete')
    <span class="text-muted">No tienes permiso</span>
@endcannot

@canany(['users-edit', 'users-delete'])
    <div class="admin-actions">...</div>
@endcanany

{{-- Verificar múltiples roles --}}
@hasanyrole('root|users-admin')
    <p>Root o users-admin</p>
@endhasanyrole

@hasallroles('therapist|users-admin')
    <p>Debe tener AMBOS roles</p>
@endhasallroles
```

---

## 🔄 Flujo Completo: Ejemplo Therapist

### Caso: Root crea un terapeuta

```
1. Usuario ROOT inicia sesión
   ↓
2. Menú muestra "Terapeutas" (@can('therapist-list'))
   ↓
3. Click en "Terapeutas"
   ↓
4. Middleware verifica: permission:therapist-list ✅
   ↓
5. TherapistController::index()
   - Verifica permiso (abort_unless)
   - Carga terapeutas con paginación
   ↓
6. Vista index.blade.php
   - Muestra botón "Nuevo" (@can('therapist-create'))
   ↓
7. Click en "Nuevo Terapeuta"
   ↓
8. Middleware verifica: permission:therapist-create ✅
   ↓
9. TherapistController::create()
   - Muestra formulario
   ↓
10. Usuario llena formulario y envía
    ↓
11. TherapistController::store()
    - Valida datos
    - Inicia transacción DB
    - Crea User (con password hasheado)
    - Asigna rol 'therapist' (Spatie)
    - Crea Therapist (perfil)
    - Commit transacción
    ↓
12. Redirección a index con mensaje de éxito
```

---

### Caso: Therapist intenta acceder a /therapists

```
1. Usuario THERAPIST inicia sesión
   ↓
2. Menú NO muestra "Terapeutas" (@can('therapist-list') = false)
   ↓
3. Si intenta acceder manualmente a /therapists
   ↓
4. Middleware intercepta: permission:therapist-list ❌
   ↓
5. Laravel lanza error 403 Forbidden
   ↓
6. Usuario ve página de error "No autorizado"
```

**Nunca llega al controlador.**

---

## 🧪 Testing y Verificación

### 1. Verificar roles y permisos en Tinker

```powershell
php artisan tinker
```

```php
// Ver roles de un usuario
$user = User::find(1);
$user->getRoleNames(); // ["root"]

// Ver permisos de un usuario
$user->getAllPermissions()->pluck('name');

// Ver permisos de un rol
$role = Role::findByName('therapist');
$role->permissions->pluck('name');

// Verificar si tiene permiso
$user->can('therapist-create'); // true/false

// Verificar si tiene rol
$user->hasRole('root'); // true/false
```

---

### 2. Probar acceso manual (navegador)

**Como ROOT:**
```
✅ /dashboard
✅ /patients
✅ /therapists  ← Puede ver
✅ /therapists/create  ← Puede crear
✅ /users
✅ /roles
```

**Como THERAPIST:**
```
✅ /dashboard
✅ /patients
❌ /therapists  ← Error 403
❌ /users  ← Error 403
❌ /roles  ← Error 403
```

**Como PATIENT:**
```
✅ /dashboard
✅ /activities  (solo sus actividades)
❌ /patients  ← Error 403
❌ /therapists  ← Error 403
```

---

### 3. Pruebas unitarias (opcional)

```php
// tests/Feature/TherapistAccessTest.php

public function test_root_can_access_therapists_index()
{
    $root = User::factory()->create();
    $root->assignRole('root');

    $response = $this->actingAs($root)->get('/therapists');

    $response->assertStatus(200);
}

public function test_therapist_cannot_access_therapists_index()
{
    $therapist = User::factory()->create();
    $therapist->assignRole('therapist');

    $response = $this->actingAs($therapist)->get('/therapists');

    $response->assertStatus(403); // Forbidden
}
```

---

## 🛠️ Comandos Útiles

### Migraciones y Seeders

```powershell
# Limpiar BD y recrear todo (⚠️ BORRA DATOS)
php artisan migrate:fresh --seed

# Solo migrar (sin borrar)
php artisan migrate

# Solo seedear (sin migrar)
php artisan db:seed

# Seedear una clase específica
php artisan db:seed --class=TherapistSeeder

# Ver estado de migraciones
php artisan migrate:status
```

---

### Roles y Permisos

```powershell
# Limpiar caché de permisos (importante después de cambios)
php artisan permission:cache-reset

# Crear un permiso desde Artisan
php artisan permission:create-permission "therapist-create"

# Crear un rol desde Artisan
php artisan permission:create-role "therapist"
```

---

### Tinker (consola interactiva)

```powershell
php artisan tinker
```

```php
// Crear therapist de prueba
DB::transaction(function() {
    $user = User::create([
        'name' => 'Test Therapist',
        'email' => 'therapist@test.com',
        'password' => Hash::make('password'),
        'role' => 'therapist',
    ]);
    $user->assignRole('therapist');
    
    Therapist::create([
        'user_id' => $user->id,
        'nombres' => 'Test',
        'apellidos' => 'Therapist',
        'dni' => '87654321',
        'nacimiento' => '1990-01-01',
        'sexo' => 'M',
        'telefono' => '999999999',
        'email' => 'therapist@test.com',
        'direccion' => 'Calle Test 123',
    ]);
    
    echo "✅ Therapist creado: therapist@test.com / password";
});

// Ver usuarios therapist
User::where('role', 'therapist')->get(['id', 'name', 'email']);

// Cambiar rol de un usuario
$user = User::find(5);
$user->syncRoles(['therapist']); // Reemplaza roles existentes
$user->update(['role' => 'therapist']);

// Dar permiso directo a un usuario (sin rol)
$user->givePermissionTo('therapist-create');

// Ver todos los roles
Role::all()->pluck('name');

// Ver todos los permisos
Permission::all()->pluck('name');
```

---

### Rutas

```powershell
# Ver todas las rutas
php artisan route:list

# Filtrar por nombre
php artisan route:list --name=therapists

# Ver middlewares de una ruta
php artisan route:list --path=therapists
```

---

## 👤 Login de Pacientes por Código

Habilitamos un flujo de autenticación simple para pacientes usando su `codigo` (columna en la tabla `patients`).

### Rutas

- GET `/patient/login` → Formulario para ingresar el código
- POST `/patient/login` → Valida el código y autentica al usuario asociado

Archivo: `routes/web.php`

```php
Route::middleware(['guest'])->group(function () {
    Route::get('/patient/login', [PatientLoginController::class, 'show'])->name('patient.login.show');
    Route::post('/patient/login', [PatientLoginController::class, 'login'])->name('patient.login');
});
```

### Controlador

Archivo: `app/Http/Controllers/Auth/PatientLoginController.php`

- Busca `Patient` por `codigo`
- Obtiene `User` por `user_id`
- Verifica rol `patient`
- `Auth::login($user, true)` y redirige a `route('dashboard')` (o `intended`)

### Vista y Landing

- `resources/views/auth/patient-login.blade.php`: Campo `codigo` + enlaces debajo: “Admin o terapeuta” (login normal) y “Registrarse” (si está disponible)
- `resources/views/landing.blade.php`: Un único botón “Ingresar” que redirige a `/patient/login`

---

## 🧩 Restricciones para Pacientes (Asignaciones y Actividades)

Objetivo: El paciente NO debe ver “Asignaciones” ni actividades de otros pacientes, y en “Actividades” solo debe inspeccionar.

### Asignaciones (Patient-Activities)

- Menú: Ocultamos “Asignaciones” para pacientes
  - Archivo: `resources/views/navigation-menu.blade.php`
  - Lógica: `@can('activity-patient-list')` + `@unlessrole('patient')`
- Permisos: El rol `patient` ya no incluye `activity-patient-list`
  - Archivo: `database/seeders/PermissionsSeeder.php`
- Backend: Bloqueo total para `patient`
  - Archivo: `app/Http/Controllers/PatientActivityController.php`
  - Todos los métodos (index/create/store/show/edit/update/destroy) devuelven 403 si `user.role === 'patient'`
- Scoping para terapeutas: En `index()`, la lista de pacientes se limita a los del terapeuta autenticado

### Actividades (ActivityController y Vistas)

- Lista filtrada para pacientes: Solo actividades asignadas (via `PatientActivity`)
  - Archivo: `app/Http/Controllers/ActivityController.php` (método `index()`)
- Botones de UI:
  - “Nueva actividad” y acciones de “Editar/Eliminar” ocultas para pacientes
  - Archivo: `resources/views/activities/index.blade.php` usando `@unlessrole('patient')`
- Rutas sensibles protegidas en backend:
  - `create`, `store`, `edit`, `update`, `destroy` abortan con 403 si `role === 'patient'`

Resultado: El paciente solo ve sus actividades y únicamente puede “ver/inspeccionar”.

---

## 📚 Recursos Adicionales

### Documentación oficial:
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction)
- [Laravel Authorization](https://laravel.com/docs/11.x/authorization)
- [Laravel Jetstream](https://jetstream.laravel.com/)

### Conceptos clave:
- **Role (Rol):** Conjunto de permisos agrupados (ej: "therapist")
- **Permission (Permiso):** Acción específica (ej: "therapist-create")
- **Guard:** Sistema de autenticación (default: 'web')
- **Policy:** Clase para lógica de autorización compleja
- **Gate:** Cierre para autorización personalizada

### Arquitectura RBAC:
```
Usuario → Roles → Permisos → Recursos
  ↓
  └─ También puede tener permisos directos
```

---

## ✅ Checklist de Implementación

Cuando agregues un nuevo rol/entidad:

- [ ] **Migración:** Tabla con `user_id` foreign key
- [ ] **Modelo:** Relaciones `belongsTo(User)` y viceversa
- [ ] **Permisos:** Agregar en `PermissionsSeeder`
  - [ ] `entity-list`
  - [ ] `entity-create`
  - [ ] `entity-edit`
  - [ ] `entity-delete`
- [ ] **Rol:** Asignar permisos al rol en `PermissionsSeeder`
- [ ] **Seeder:** Crear `EntitySeeder` con User + Entity
- [ ] **Factory:** UserFactory con método específico si es necesario
- [ ] **Controlador:** CRUD con `abort_unless()` en cada método
- [ ] **Rutas:** Agregar grupo con middleware `permission:entity-list`
- [ ] **Vistas:**
  - [ ] index.blade.php con `@can('entity-create')`
  - [ ] show.blade.php
  - [ ] create.blade.php
  - [ ] edit.blade.php
- [ ] **Menú:** Agregar enlace con `@can('entity-list')` en `navigation-menu.blade.php`
- [ ] **Testing:** Probar acceso permitido y denegado

---

## 🎓 Conclusión

Este sistema proporciona:
- ✅ **Control de acceso granular** por permisos
- ✅ **Gestión flexible de roles** sin hardcodear
- ✅ **Seguridad en múltiples capas** (rutas + controlador + vistas)
- ✅ **Escalabilidad** fácil para agregar nuevos roles/permisos
- ✅ **Experiencia de usuario** adaptada al rol

**Patrón implementado:**
```
Middleware → Controller → Service → Model → Database
     ↓           ↓
  Autoriza   Valida datos
```

---

**Autor:** Sistema de Roles y Permisos TeAssist  
**Fecha:** Octubre 2025  
**Stack:** Laravel 11 + Spatie Permission 6.9 + Jetstream + Livewire 3

---

## 📞 Preguntas Frecuentes

**Q: ¿Puedo tener múltiples roles?**  
A: Sí, Spatie permite múltiples roles por usuario, pero en esta implementación usamos un rol primario en la columna `role`.

**Q: ¿Cómo agrego un permiso nuevo sin rehacer todo?**  
A: Ejecuta `php artisan db:seed --class=PermissionsSeeder` y limpia caché con `php artisan permission:cache-reset`.

**Q: ¿Por qué no aparece el menú "Terapeutas"?**  
A: Verifica que tu usuario tenga el permiso `therapist-list`. Ejecuta en Tinker: `auth()->user()->can('therapist-list')`.

**Q: ¿Cómo cambio el rol de un usuario?**  
A: En Tinker: `$user->syncRoles(['nuevo-rol']); $user->update(['role' => 'nuevo-rol']);`

**Q: ¿Puedo dar permisos directos sin rol?**  
A: Sí: `$user->givePermissionTo('permiso-especifico')`. Útil para excepciones.
