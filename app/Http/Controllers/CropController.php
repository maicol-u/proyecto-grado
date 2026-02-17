<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CropController extends Controller
{
    /**
     * Listar invernaderos
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $invernaderos = Crop::with('users')->get();
        } else {
            $invernaderos = $user->invernaderos()->get();
        }

        return Inertia::render('invernaderos/Index', [
            'invernaderos' => $invernaderos
        ]);
    }

    public function create(){
        return Inertia::render('invernaderos/Create');
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

        $invernadero = Crop::create([
            'name' => $request->nombre,
            'location' => $request->ubicacion,
            'description' => $request->descripcion,
        ]);

        return redirect()->route('invernadero.index')->with('success', 'Invernadero creado correctamente');
    }

    /**
     * Mostrar un invernadero específico
     */
    public function show(Crop $crop)
    {
        return Inertia::render('invernaderos/Update', ['invernadero' => $crop]);
    }

    /**
     * Mostrar un invernadero específico para editar
     */
    public function edit(Crop $invernadero)
    {
        return Inertia::render('invernaderos/Update', [
            'invernadero' => $invernadero->load('users'),
            'users' => User::select('id','name')->get()
        ]);
    }

    /**
     * Actualizar un invernadero
     */
    public function update(Request $request, Crop $invernadero)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'ubicacion' => 'nullable|string|max:150',
            'descripcion' => 'nullable|string',
        ]);

        $invernadero->update([
            'name' => $data['nombre'],
            'location' => $data['ubicacion'],
            'description' => $data['descripcion']
        ]);

        return redirect()->route('invernadero.edit', $invernadero->id)
            ->with('success', 'Invernadero actualizado correctamente', microtime());
    }

    /**
     * Eliminar un invernadero
     */
    public function destroy(Crop $invernadero)
    {
        // Desvincular usuarios
        $invernadero->users()->detach();

        $invernadero->delete();

        return redirect()->route('invernadero.index')->with('success', 'Invernadero eliminado correctamente');
    }

    /**
     * Verifica que el invernadero pertenezca al usuario
     */
    private function authorizeAccess(Crop $invernadero): void
    {
        if (!Auth::user()->invernaderos->contains($invernadero->id)) {
            abort(403, 'No tienes acceso a este invernadero');
        }
    }

    public function attachUser(Request $request, Crop $invernadero)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $invernadero->users()->syncWithoutDetaching($request->user_id);

        return back()->with('success', 'Usuario vinculado.');
    }

    public function detachUser(Crop $invernadero, User $user)
    {
        $invernadero->users()->detach($user->id);

        return back()->with('success', 'Usuario desvinculado.');
    }

}
