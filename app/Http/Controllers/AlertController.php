<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Listar Alertas
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $alerts = Alert::with('sensor.crop')
            ->when(! $user->isAdmin(), function ($query) use ($user) {
                $query->whereHas('sensor.crop.users', function ($cropUsersQuery) use ($user) {
                    $cropUsersQuery->whereKey($user->id);
                });
            })
            ->latest('triggered_at')
            ->paginate(15);

        return inertia('alerts/Index', [
            'alerts' => $alerts
        ]);
    }

    /**
     * Mostrar detalles de una alerta
     */
    public function show(Request $request, Alert $alert)
    {
        $this->authorizeAlertAccess($request, $alert);

        $alert->load('sensor.crop');

        return inertia('alerts/Show', [
            'alert' => $alert
        ]); 
    }

    /**
     * Eliminar una alerta
     */
    public function destroy(Request $request, Alert $alert)
    {        
        $this->authorizeAlertAccess($request, $alert);

        $alert->delete();
        return redirect()->route('alerts.index')->with('success', 'Alerta eliminada exitosamente.');
    }

    private function authorizeAlertAccess(Request $request, Alert $alert): void
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return;
        }

        abort_unless(
            $user->crops()->whereKey($alert->sensor->crop_id)->exists(),
            403,
            'No tienes acceso a esta alerta.'
        );
    }
    
}
