<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\PatientActivity;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Traits\DebugHelper;
use App\Utils\ImageUtils;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    use DebugHelper;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $searchString = request()->get('search');
        $user = Auth::user();

        // Si es paciente, solo mostrar actividades asignadas a ese paciente
        if ($user && ($user->role === 'patient') && method_exists($user, 'patient') && $user->patient) {
            $activityIds = PatientActivity::where('patient_id', $user->patient->id)->pluck('activity_id');
            $query = Activity::whereIn('id', $activityIds);
            if ($searchString) {
                $query->where('name', 'like', "%$searchString%");
            }
            $activities = $query->latest()->paginate(5);
        } else {
            // Catálogo general para terapeutas/admins
            if ($searchString) {
                $activities = Activity::where('name', 'like', "%$searchString%")->latest()->paginate(5);
            } else {
                $activities = Activity::latest()->paginate(5);
            }
        }
        return view('activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user() && Auth::user()->role === 'patient') {
            abort(403);
        }
        return view('activities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRequest $request)
    {
        if (Auth::user() && Auth::user()->role === 'patient') {
            abort(403);
        }
        $file = $request->file('image');
        $contents = file_get_contents($file);
        $base64Images = ImageUtils  ::base64Images($contents);
        $validated = $request->validated();
        $validated['image'] = $base64Images['original'];
        $validated['thumbnail'] = $base64Images['thumbnail'];
        Activity::create($validated);
        return redirect()->route('activities.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        if (Auth::user() && Auth::user()->role === 'patient') {
            abort(403);
        }
        return view('activities.edit', compact('activity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        if (Auth::user() && Auth::user()->role === 'patient') {
            abort(403);
        }
        $file = $request->file('image');
        $validated = $request->validated();
        if ($file) {
            $contents = file_get_contents($file);
            $base64Images = ImageUtils  ::base64Images($contents);
            $validated = $request->validated();
            $validated['image'] = $base64Images['original'];
            $validated['thumbnail'] = $base64Images['thumbnail'];
        }
        $activity->update($validated);
        return redirect()->route('activities.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        if (Auth::user() && Auth::user()->role === 'patient') {
            abort(403);
        }
        $activity->delete();
        return redirect()->route('activities.index');
    }
}
