<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Crear tabla pivot patient_therapist
        if (! Schema::hasTable('patient_therapist')) {
            Schema::create('patient_therapist', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
                $table->foreignId('therapist_id')->constrained('therapists')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['patient_id', 'therapist_id']);
            });
        }

        // 2) Copiar relaciones existentes desde patients.therapist_id -> patient_therapist
        if (Schema::hasColumn('patients', 'therapist_id')) {
            $patients = DB::table('patients')->whereNotNull('therapist_id')->get();
            $now = now();
            foreach ($patients as $p) {
                // Evitar duplicados con insertOrIgnore
                DB::table('patient_therapist')->insertOrIgnore([
                    'patient_id' => $p->id,
                    'therapist_id' => $p->therapist_id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // 3) Eliminar la columna therapist_id de patients (primero la FK si existe)
            Schema::table('patients', function (Blueprint $table) {
                // dropForeign acepta nombre de columna
                try {
                    $table->dropForeign(['therapist_id']);
                } catch (\Exception $e) {
                    // Si no existe FK, continuar
                }
                $table->dropColumn('therapist_id');
            });
        }
    }

    public function down(): void
    {
        // 1) Añadir therapist_id nuevamente a patients
        if (! Schema::hasColumn('patients', 'therapist_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->foreignId('therapist_id')
                      ->nullable()
                      ->constrained('therapists')
                      ->onDelete('set null');
            });

            // 2) Restaurar una relación (si hay varias, tomamos la primera) desde patient_therapist
            $rows = DB::table('patient_therapist')
                ->select('patient_id', 'therapist_id')
                ->groupBy('patient_id', 'therapist_id')
                ->get();

            foreach ($rows as $r) {
                DB::table('patients')
                    ->where('id', $r->patient_id)
                    ->update(['therapist_id' => $r->therapist_id]);
            }
        }

        // 3) Eliminar tabla pivot
        if (Schema::hasTable('patient_therapist')) {
            Schema::dropIfExists('patient_therapist');
        }
    }
};
