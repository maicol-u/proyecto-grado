<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SensorReadingController extends Controller
{


    public function chart(Request $request, $id)
    {
        $sensor = Sensor::findOrFail($id);

        $range = $request->get('range', 'live');

        switch ($range) {

            case 'live':
                $from = now()->setTimezone('America/Bogota')->subMinutes(10);

                $readings = DB::table('readings')
                    ->select('recorded_at as time', 'value')
                    ->where('sensor_id', $id)
                    ->where('value', '<=', 100)
                    ->where('recorded_at', '>=', $from)
                    ->orderByDesc('recorded_at')
                    ->limit(70)
                    ->get()
                    ->reverse()
                    ->values();
                break;

            // Última hora (cada minuto)
            case '1h':
                $format = '%Y-%m-%d %H:%i:00';
                $from = now()->setTimezone('America/Bogota')->subHour();
                break;

            // 10 horas (cada 10 min)
            case '10h':
                $format = '%Y-%m-%d %H:%i:00';
                $from = now()->setTimezone('America/Bogota')->subHours(10);
                break;

            // 1 día (cada hora)
            case '1d':
                $format = '%Y-%m-%d %H:00:00';
                $from = now()->setTimezone('America/Bogota')->subDay();
                break;

            //  mes (por día)
            case '1m':
                $format = '%Y-%m-%d';
                $from = now()->setTimezone('America/Bogota')->subMonth();
                break;

            default:
                $format = '%Y-%m-%d %H:%i:00';
                $from = now()->setTimezone('America/Bogota')->subHour();
        }

        // Agregación (excepto live)
        if ($range !== 'live') {
            $readings = DB::table('readings')
                ->selectRaw("
                DATE_FORMAT(recorded_at, '{$format}') as time,
                AVG(value) as value
            ")
                ->where('sensor_id', $id)
                ->where('recorded_at', '>=', $from)
                ->groupBy('time')
                ->orderBy('time')
                ->get();
        }

        return response()->json($readings);
    }
}
