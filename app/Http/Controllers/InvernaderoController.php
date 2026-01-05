<?php

namespace App\Http\Controllers;

use App\Models\Invernadero;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class InvernaderoController extends Controller
{
    /**
     * Listar invernaderos
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $invernaderos = Invernadero::with('users')->get();
        } else {
            $invernaderos = $user->invernaderos()->get();
        }

        return response()->json($invernaderos);
    }

    /**
     * Crear un nuevo invernadero
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'ubicacion' => 'nullable|string|max:150',
            'descripcion' => 'nullable|string',
        ]);

        $invernadero = Invernadero::create([
            'nombre' => $request->nombre,
            'ubicacion' => $request->ubicacion,
            'descripcion' => $request->descripcion,
        ]);

        // Vincular el invernadero al usuario
        Auth::user()->invernaderos()->attach($invernadero->id);

        return response()->json([
            'message' => 'Invernadero creado correctamente',
            'data' => $invernadero
        ], 201);
    }

    /**
     * Mostrar un invernadero específico (solo si pertenece al usuario)
     */
    public function show(Invernadero $invernadero)
    {
        $this->authorizeAccess($invernadero);

        return response()->json($invernadero);
    }

    /**
     * Actualizar un invernadero
     */
    public function update(Request $request, Invernadero $invernadero)
    {
        $this->authorizeAccess($invernadero);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'ubicacion' => 'nullable|string|max:150',
            'descripcion' => 'nullable|string',
        ]);

        $invernadero->update($request->all());

        return response()->json([
            'message' => 'Invernadero actualizado',
            'data' => $invernadero
        ]);
    }

    /**
     * Eliminar un invernadero
     */
    public function destroy(Invernadero $invernadero)
    {
        $this->authorizeAccess($invernadero);

        // Desvincular usuarios
        $invernadero->users()->detach();

        $invernadero->delete();

        return response()->json([
            'message' => 'Invernadero eliminado'
        ]);
    }

    /**
     * Verifica que el invernadero pertenezca al usuario
     */
    private function authorizeAccess(Invernadero $invernadero): void
    {
        if (!Auth::user()->invernaderos->contains($invernadero->id)) {
            abort(403, 'No tienes acceso a este invernadero');
        }
    }
}
