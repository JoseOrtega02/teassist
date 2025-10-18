<?php

namespace App\Http\Controllers;

use App\Models\Therapist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TherapistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificar permiso
        abort_unless(auth()->user()->can('therapist-list'), 403);

        $data = Therapist::with('user')->latest()->paginate(15);
        
        return view('therapists.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar permiso
        abort_unless(auth()->user()->can('therapist-create'), 403);

        return view('therapists.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar permiso
        abort_unless(auth()->user()->can('therapist-create'), 403);

        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:therapists,dni',
            'nacimiento' => 'required|date|before:today',
            'sexo' => 'required|in:M,F',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:therapists,email',
            'direccion' => 'required|string|max:500',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nombres.required' => 'El campo nombres es obligatorio',
            'apellidos.required' => 'El campo apellidos es obligatorio',
            'dni.required' => 'El DNI es obligatorio',
            'dni.size' => 'El DNI debe tener 8 dígitos',
            'dni.unique' => 'Este DNI ya está registrado',
            'nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'sexo.required' => 'El campo sexo es obligatorio',
            'sexo.in' => 'El sexo debe ser M o F',
            'telefono.required' => 'El teléfono es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'Debe ser un email válido',
            'email.unique' => 'Este email ya está registrado',
            'direccion.required' => 'La dirección es obligatoria',
            'user_name.required' => 'El nombre de usuario es obligatorio',
            'user_email.required' => 'El email de acceso es obligatorio',
            'user_email.email' => 'Debe ser un email válido',
            'user_email.unique' => 'Este email ya está en uso',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        DB::beginTransaction();

        try {
            // Obtener el rol therapist
            $therapistRole = Role::where('name', 'therapist')->first();

            // Crear usuario
            $user = User::create([
                'name' => $validated['user_name'],
                'email' => $validated['user_email'],
                'password' => Hash::make($validated['password']),
                'role' => 'therapist',
            ]);

            // Asignar rol therapist
            $user->assignRole('therapist');

            // Crear terapeuta
            $therapist = Therapist::create([
                'user_id' => $user->id,
                'nombres' => $validated['nombres'],
                'apellidos' => $validated['apellidos'],
                'dni' => $validated['dni'],
                'nacimiento' => $validated['nacimiento'],
                'sexo' => $validated['sexo'],
                'telefono' => $validated['telefono'],
                'email' => $validated['email'],
                'direccion' => $validated['direccion'],
            ]);

            DB::commit();

            return redirect()
                ->route('therapists.index')
                ->with('success', 'Terapeuta creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear el terapeuta: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Therapist $therapist)
    {
        // Verificar permiso
        abort_unless(auth()->user()->can('therapist-list'), 403);

        $therapist->load(['user', 'patients']);

        return view('therapists.show', compact('therapist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Therapist $therapist)
    {
        // Verificar permiso
        abort_unless(auth()->user()->can('therapist-edit'), 403);

        $therapist->load('user');

        return view('therapists.edit', compact('therapist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Therapist $therapist)
    {
        // Verificar permiso
        abort_unless(auth()->user()->can('therapist-edit'), 403);

        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:therapists,dni,' . $therapist->id,
            'nacimiento' => 'required|date|before:today',
            'sexo' => 'required|in:M,F',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:therapists,email,' . $therapist->id,
            'direccion' => 'required|string|max:500',
            'user_name' => 'nullable|string|max:255',
            'user_email' => 'nullable|email|unique:users,email,' . ($therapist->user_id ?? 'NULL'),
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'nombres.required' => 'El campo nombres es obligatorio',
            'apellidos.required' => 'El campo apellidos es obligatorio',
            'dni.required' => 'El DNI es obligatorio',
            'dni.size' => 'El DNI debe tener 8 dígitos',
            'dni.unique' => 'Este DNI ya está registrado',
            'nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'sexo.required' => 'El campo sexo es obligatorio',
            'sexo.in' => 'El sexo debe ser M o F',
            'telefono.required' => 'El teléfono es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'Debe ser un email válido',
            'email.unique' => 'Este email ya está registrado',
            'direccion.required' => 'La dirección es obligatoria',
            'user_email.email' => 'Debe ser un email válido',
            'user_email.unique' => 'Este email ya está en uso',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        DB::beginTransaction();

        try {
            // Actualizar datos del terapeuta
            $therapist->update([
                'nombres' => $validated['nombres'],
                'apellidos' => $validated['apellidos'],
                'dni' => $validated['dni'],
                'nacimiento' => $validated['nacimiento'],
                'sexo' => $validated['sexo'],
                'telefono' => $validated['telefono'],
                'email' => $validated['email'],
                'direccion' => $validated['direccion'],
            ]);

            // Actualizar usuario asociado si existe
            if ($therapist->user) {
                $userData = [];

                if (!empty($validated['user_name'])) {
                    $userData['name'] = $validated['user_name'];
                }

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
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar el terapeuta: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Therapist $therapist)
    {
        // Verificar permiso
        abort_unless(auth()->user()->can('therapist-delete'), 403);

        DB::beginTransaction();

        try {
            // Eliminar el usuario asociado si existe
            if ($therapist->user) {
                $therapist->user->delete();
            }

            // Eliminar el terapeuta
            $therapist->delete();

            DB::commit();

            return redirect()
                ->route('therapists.index')
                ->with('success', 'Terapeuta eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withErrors(['error' => 'Error al eliminar el terapeuta: ' . $e->getMessage()]);
        }
    }
}

