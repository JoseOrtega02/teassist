<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Activity;
use App\Models\PatientActivity;
use App\Http\Requests\StorePatientActivityRequest;
use App\Http\Requests\UpdatePatientActivityRequest;
use Illuminate\Support\Facades\Auth;

class PatientActivityController extends Controller
{
    public function index() {
        $user = Auth::user();
        // Pacientes no deben ver asignaciones (evitar ver otros pacientes)
        if ($user && ($user->role === 'patient')) {
            abort(403);
        }

        $patient_id = request()->get('patient_id');

        // Limitar lista de pacientes según rol
        if ($user && ($user->role === 'therapist') && method_exists($user, 'therapist') && $user->therapist) {
            $patients = Patient::where('therapist_id', $user->therapist->id)->get();
        } else {
            // root u otros administradores
            $patients = Patient::all();
        }

        $query = PatientActivity::query();
        if ($patient_id) {
            $query->where('patient_id', $patient_id);
        } else {
            // Si no hay paciente seleccionado, no mostrar nada para evitar listar todo
            $query->whereRaw('1=0');
        }

        $patientActivities = $query->paginate(5);
        return view('patient-activities.index', compact('patientActivities', 'patients', 'patient_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if ($user && ($user->role === 'patient')) {
            abort(403);
        }
        $patient_id = request()->get('patient_id');
        $patient = Patient::find($patient_id);
        $patient_full_name = $patient->apellidos . ', ' . $patient->nombres;
        $activities = Activity::all();
        return view('patient-activities.create', compact('activities', 'patient_id', 'patient_full_name'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientActivityRequest $request)
    {
        $user = Auth::user();
        if ($user && ($user->role === 'patient')) {
            abort(403);
        }
        $user_id = $user ? $user->id : null;
        $patient_id = request()->get('patient_id');
        $validated = $request->validated();
        $validated['user_id'] = $user_id;
        $validated['patient_id'] = $patient_id;
        PatientActivity::create($validated);
        return redirect()->route('patient-activities.index', ['patient_id' => $patient_id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientActivity $patientActivity)
    {
        $user = Auth::user();
        if ($user && ($user->role === 'patient')) {
            abort(403);
        }
        return view('patient-activities.show', compact('patientActivity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PatientActivity $patientActivity)
    {
        $user = Auth::user();
        if ($user && ($user->role === 'patient')) {
            abort(403);
        }
        $patient = $patientActivity->patient;
        $patient_full_name = $patient->apellidos . ', ' . $patient->nombres;
        $activities = Activity::all();
        return view('patient-activities.edit', compact('patientActivity', 'activities', 'patient_full_name'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientActivityRequest $request, PatientActivity $patientActivity)
    {
        $user = Auth::user();
        if ($user && ($user->role === 'patient')) {
            abort(403);
        }
        $patient_id = $patientActivity->patient_id;
        $validated = $request->validated();
        $patientActivity->update($validated);
        return redirect()->route('patient-activities.index', ['patient_id' => $patient_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientActivity $patientActivity)
    {
        $user = Auth::user();
        if ($user && ($user->role === 'patient')) {
            abort(403);
        }
        $patientActivity->delete();
        return redirect()->route('patient-activities.index', ['patient_id' => $patientActivity->patient_id]);
    }
}
