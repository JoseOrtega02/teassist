<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientLoginController extends Controller
{
    /**
     * Show the patient login form (by codigo).
     */
    public function show()
    {
        return view('auth.patient-login');
    }

    /**
     * Attempt a login using patient codigo.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'codigo' => ['required', 'string'],
        ], [
            'codigo.required' => 'Ingresa tu código de paciente.',
        ]);

        // Find patient by codigo
        $patient = Patient::where('codigo', $validated['codigo'])->first();

        if (!$patient) {
            return back()->withErrors(['codigo' => 'Código no válido.'])->withInput();
        }

        // Get associated user
        $user = User::find($patient->user_id);
        if (!$user) {
            return back()->withErrors(['codigo' => 'No se encontró la cuenta de usuario asociada.'])->withInput();
        }

        // Ensure user has patient role (defensive)
        if (! $user->hasRole('patient')) {
            return back()->withErrors(['codigo' => 'Tu cuenta no tiene rol de paciente. Contacta a soporte.']);
        }

        // Login the user
        Auth::login($user, true);

        // Redirect to dashboard or a patient home if exists
        return redirect()->intended(route('dashboard'));
    }
}
