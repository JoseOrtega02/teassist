<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientMood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PatientMoodController extends Controller
{
    /**
     * Show form for patient to record mood.
     */
    public function create()
    {
        return view('patient_moods.create');
    }

    /**
     * Store mood submitted by patient.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // find patient record linked to current user
        $patient = Patient::where('user_id', $user->id)->first();
        if (! $patient) {
            return redirect()->back()->with('error', 'Paciente no encontrado.');
        }

        $data = $request->validate([
            'mood' => 'required|string|max:255',
            'recorded_at' => 'nullable|date',
        ]);

        $recordedAt = $data['recorded_at'] ?? now()->toDateString();

        // create or update today's mood (one per day)
        PatientMood::updateOrCreate(
            ['patient_id' => $patient->id, 'recorded_at' => $recordedAt],
            ['mood' => $data['mood']]
        );

        return redirect()->back()->with('success', 'Estado de ánimo guardado.');
    }

    /**
     * Show moods for a given patient (therapist view).
     */
    public function index(Patient $patient)
    {
        // Basic permission check: require permission to list patient activities/moods
        if (! Gate::forUser(Auth::user())->allows('activity-patient-list') && ! Gate::forUser(Auth::user())->allows('patient-list')) {
            abort(403);
        }

        $moods = PatientMood::where('patient_id', $patient->id)
            ->orderBy('recorded_at', 'desc')
            ->get();

        return view('patient_moods.index', compact('patient', 'moods'));
    }
}
