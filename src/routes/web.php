<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TherapistController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ContadorController;
use App\Http\Controllers\PatientActivityController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/contador', [ContadorController::class, 'index'])->name('contador');
Route::get('/contador/incrementar/{número}', [ContadorController::class, 'incrementar'])->name('incrementar');
Route::get('/contador/decrementar/{número}', [ContadorController::class, 'decrementar'])->name('decrementar');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    
    // Ruta para eventos en tiempo real (event listener)
    Route::get('/pull-events', [EventController::class, 'pullEvents'])->name('pull-events');

    // Dashboard - requiere permiso 'see-panel'
    Route::middleware('permission:see-panel')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });

    // ============================================
    // GESTIÓN DE ROLES (solo roles-admin y root)
    // ============================================
    Route::middleware('permission:roles-list')->prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->middleware('permission:roles-create')->name('create');
        Route::post('/', [RoleController::class, 'store'])->middleware('permission:roles-create')->name('store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:roles-edit')->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->middleware('permission:roles-edit')->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->middleware('permission:roles-delete')->name('destroy');
    });

    // ============================================
    // GESTIÓN DE USUARIOS (solo users-admin y root)
    // ============================================
    Route::middleware('permission:users-list')->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->middleware('permission:users-create')->name('create');
        Route::post('/', [UserController::class, 'store'])->middleware('permission:users-create')->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->middleware('permission:users-edit')->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->middleware('permission:users-edit')->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('permission:users-delete')->name('destroy');
        
        // Habilitar/deshabilitar usuarios
        Route::post('/{user}/disable', [UserController::class, 'disable'])->middleware('permission:users-disable')->name('disable');
        Route::post('/{user}/enable', [UserController::class, 'enable'])->middleware('permission:users-enable')->name('enable');
    });

    // ============================================
    // GESTIÓN DE PACIENTES (therapist y root)
    // ============================================
    Route::middleware('permission:patient-list')->prefix('patients')->name('patients.')->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('/create', [PatientController::class, 'create'])->middleware('permission:patient-create')->name('create');
        Route::post('/', [PatientController::class, 'store'])->middleware('permission:patient-create')->name('store');
        Route::get('/{patient}', [PatientController::class, 'show'])->name('show');
        Route::get('/{patient}/edit', [PatientController::class, 'edit'])->middleware('permission:patient-edit')->name('edit');
        Route::put('/{patient}', [PatientController::class, 'update'])->middleware('permission:patient-edit')->name('update');
        Route::delete('/{patient}', [PatientController::class, 'destroy'])->middleware('permission:patient-delete')->name('destroy');
    });

    // ============================================
    // GESTIÓN DE TERAPEUTAS (solo root)
    // ============================================
    Route::middleware('permission:therapist-list')->prefix('therapists')->name('therapists.')->group(function () {
        Route::get('/', [TherapistController::class, 'index'])->name('index');
        Route::get('/create', [TherapistController::class, 'create'])->middleware('permission:therapist-create')->name('create');
        Route::post('/', [TherapistController::class, 'store'])->middleware('permission:therapist-create')->name('store');
        Route::get('/{therapist}', [TherapistController::class, 'show'])->name('show');
        Route::get('/{therapist}/edit', [TherapistController::class, 'edit'])->middleware('permission:therapist-edit')->name('edit');
        Route::put('/{therapist}', [TherapistController::class, 'update'])->middleware('permission:therapist-edit')->name('update');
        Route::delete('/{therapist}', [TherapistController::class, 'destroy'])->middleware('permission:therapist-delete')->name('destroy');
    });

    // ============================================
    // GESTIÓN DE ACTIVIDADES (therapist, patient y root)
    // ============================================
    Route::middleware('permission:activity-list')->prefix('activities')->name('activities.')->group(function () {
        Route::get('/', [ActivityController::class, 'index'])->name('index');
        Route::get('/create', [ActivityController::class, 'create'])->middleware('permission:activity-create')->name('create');
        Route::post('/', [ActivityController::class, 'store'])->middleware('permission:activity-create')->name('store');
        Route::get('/{activity}', [ActivityController::class, 'show'])->name('show');
        Route::get('/{activity}/edit', [ActivityController::class, 'edit'])->middleware('permission:activity-edit')->name('edit');
        Route::put('/{activity}', [ActivityController::class, 'update'])->middleware('permission:activity-edit')->name('update');
        Route::delete('/{activity}', [ActivityController::class, 'destroy'])->middleware('permission:activity-delete')->name('destroy');
    });

    // ============================================
    // ASIGNACIÓN DE ACTIVIDADES A PACIENTES
    // ============================================
    Route::middleware('permission:activity-patient-list')->prefix('patient-activities')->name('patient-activities.')->group(function () {
        Route::get('/', [PatientActivityController::class, 'index'])->name('index');
        Route::post('/', [PatientActivityController::class, 'store'])->middleware('permission:activity-patient-create')->name('store');
        Route::delete('/{patientActivity}', [PatientActivityController::class, 'destroy'])->middleware('permission:activity-patient-delete')->name('destroy');
    });
});