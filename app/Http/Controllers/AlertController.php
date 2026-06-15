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
        $alerts = Alert::with('sensor.crop')->latest('triggered_at')->paginate(15);
        return inertia('alerts/Index', [
            'alerts' => $alerts
        ]);
    }

    /**
     * Mostrar detalles de una alerta
     */
    public function show(Alert $alert)
    {
        $alert->load('sensor.crop');
        return inertia('alerts/Show', [
            'alert' => $alert
        ]); 
    }

    /**
     * Eliminar una alerta
     */
    public function destroy(Alert $alert)
    {        
        $alert->delete();
        return redirect()->route('alerts.index')->with('success', 'Alerta eliminada exitosamente.');
    }   
}
