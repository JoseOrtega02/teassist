<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Therapist;
use Illuminate\Http\Request;
use App\Http\Requests\PatientRequest;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::latest()->paginate(5);
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $therapists = Therapist::all();
        return view('patients.create', compact('therapists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientRequest $request)
    {
        $data = $request->validated();
        $patient = Patient::create($data);

        // sync therapists if provided (array of therapist ids)
        if ($request->has('therapists') && is_array($request->get('therapists'))) {
            $patient->therapists()->sync($request->get('therapists'));
        }

        return redirect()->route('patients.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $therapists = Therapist::all();
        return view('patients.edit', compact('patient', 'therapists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'codigo' => 'required|unique:patients,codigo,' . $patient->id,
            'apellidos' => 'required',
            'nombres' => 'required',
            'dni' => 'required|unique:patients,dni,' . $patient->id,
            'nacimiento' => 'required|date',
            'sexo' => 'required',
            'telefono' => 'required',
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'direccion' => 'required',
            'therapists' => 'sometimes|array',
            'therapists.*' => 'exists:therapists,id',
        ]);

        $patient->update($request->only(['codigo','apellidos','nombres','dni','nacimiento','sexo','telefono','email','direccion','observaciones']));

        if ($request->has('therapists')) {
            $patient->therapists()->sync($request->get('therapists'));
        }

        return redirect()->route('patients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index');
    }
}
