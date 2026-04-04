<?php

namespace App\Http\Controllers;

use App\Enums\SensorAlertLevel;
use App\Models\Crop;
use App\Models\Sensor;
use App\Models\SensorType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SensorController extends Controller
{
    /**
     * Display a listing of the sensors.
     */
    public function index()
    {
        $sensors = Sensor::with(['crop', 'type'])->paginate(15)
            ->through(function ($sensor) {
                return [
                    'id' => $sensor->id,
                    'name' => $sensor->name,
                    'model' => $sensor->model,
                    'crop_name' => $sensor->crop?->name,
                    'alert_level_label' => $sensor->alert_level == SensorAlertLevel::NORMAL ? 'NORMAL' : 'ALERTA',
                    'type_label' => $sensor->type?->name,
                ];
            });

        return Inertia::render('sensors/Index', [
            'sensors' => $sensors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('sensors/Create', [
            'crops' => Crop::select('id', 'name')->get(),
            'types' => SensorType::select('id', 'name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'crop_id' => ['required', 'exists:crops,id'],
            'type_id' => ['required', 'exists:sensor_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:50'],
            'reading_interval' => ['required', 'integer', 'min:1'],
            'min_value' => ['required', 'numeric'],
            'max_value' => ['required', 'numeric', 'gt:min_value'],
        ]);

        $validated['status'] = 'active';
        $validated['alert_level'] = SensorAlertLevel::NORMAL;

        Sensor::create($validated);

        return redirect()->route('sensors.index')->with('success', 'Sensor creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sensor = Sensor::findOrFail($id);
        $crops = Crop::select('id', 'name')->get();
        $types = SensorType::select('id', 'name')->get();

        return Inertia::render('sensors/Update', ['sensor' => $sensor, 'crops' => $crops, 'types' => $types]);
    }

    /**
     * Update sensor in storage.
     */
    public function update(Request $request, string $id)
    {
        $sensor = Sensor::findOrFail($id);

        $validated = $request->validate([
            'crop_id' => ['required', 'exists:crops,id'],
            'type_id' => ['required', 'exists:sensor_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:50'],
            'reading_interval' => ['required', 'integer', 'min:1'],
            'min_value' => ['required', 'numeric'],
            'max_value' => ['required', 'numeric', 'gt:min_value'],
        ]);

        $sensor->update($validated);

        return redirect()
            ->route('sensors.index')
            ->with('success', 'Sensor actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sensor = Sensor::findOrFail($id);
        $sensor->delete();

        return redirect()->route('sensors.index')->with('success', 'Sensor eliminado correctamente');
    }
}
