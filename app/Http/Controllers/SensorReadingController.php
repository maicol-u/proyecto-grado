<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorReadingController extends Controller
{

    public function chart($id)
    {
        $sensor = Sensor::findOrFail($id);

        $readings = $sensor->readings()
            ->latest('recorded_at')
            ->take(20)
            ->get()
            ->reverse()
            ->values();

        return response()->json($readings);
    }
}
