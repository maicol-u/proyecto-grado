<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SensorAlertLevel;
use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Crop;
use App\Models\Sensor;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{

    public function index()
    {
        $sensors = Sensor::query()
            ->withMax('readings', 'recorded_at')
            ->get();

        $onlineSensors = $sensors->filter(function (Sensor $sensor) {
            if (! $sensor->readings_max_recorded_at) {
                return false;
            }

            return Carbon::parse($sensor->readings_max_recorded_at)
                ->greaterThanOrEqualTo(now()->subSeconds($sensor->reading_interval));
        })->count();

        $stats = [
            [
                'label' => 'Usuarios',
                'value' => User::count(),
                'description' => 'Usuarios registrados en la plataforma',
            ],
            [
                'label' => 'Invernaderos',
                'value' => Crop::count(),
                'description' => 'Espacios monitoreados en el sistema',
            ],
            [
                'label' => 'Sensores',
                'value' => $sensors->count(),
                'description' => 'Dispositivos asociados a cultivos',
            ],
            [
                'label' => 'Alertas activas',
                'value' => Alert::whereNull('resolved_at')->count(),
                'description' => 'Alertas pendientes por revisar',
            ],
            [
                'label' => 'Sensores en alerta',
                'value' => Sensor::whereIn('alert_level', [
                    SensorAlertLevel::HIGH->value,
                    SensorAlertLevel::LOW->value,
                ])->count(),
                'description' => 'Sensores con humedad fuera de rango',
            ],
        ];

        $recentAlerts = Alert::query()
            ->with('sensor.crop')
            ->latest('triggered_at')
            ->take(5)
            ->get()
            ->map(function (Alert $alert) {
                return [
                    'id' => $alert->id,
                    'message' => $alert->message,
                    'value' => $alert->value,
                    'triggered_at' => optional($alert->triggered_at)?->toISOString(),
                    'sensor_name' => $alert->sensor?->name ?? 'Sin sensor',
                    'crop_name' => $alert->sensor?->crop?->name ?? 'Sin cultivo',
                ];
            })
            ->values();

        return Inertia::render('admin/Dashboard', [
            'stats' => $stats,
            'recentAlerts' => $recentAlerts,
        ]);
    }
}
