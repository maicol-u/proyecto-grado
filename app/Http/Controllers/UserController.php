<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the user.
     */
    public function index()
    {
        $users = User::paginate(10)->through(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'role_label' => $user->role->label(),
            ];
        });

        return Inertia::render('users/Index', [
            'users' =>$users,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = collect(UserRole::cases())->map(fn ($role) => [
            'value' => $role->value,
            'label' => ucfirst($role->label()),
        ]);
        return Inertia::render('users/Create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'celular' => 'required|string|digits:10',
            'password' => 'required|min:6|confirmed',
            'role' => ['required', new Enum(UserRole::class)],
        ]);

        User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'phone_number' => $request->celular,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

         return redirect()->route('users.index')->with('success', 'Usuario creado correctamente');
    }

    /**
     * Display the specified user.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = collect(UserRole::cases())->map(fn ($role) => [
            'value' => $role->value,
            'label' => ucfirst($role->label()),
        ]);
        return Inertia::render('users/Update', ['user' => $user, 'roles'=>$roles]);
    }

    /**
     * Update the user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'celular' => 'required|digits:10',
            'role' => ['required', new Enum(UserRole::class)],
            'password' => 'nullable|min:8|confirmed',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update([
            'name' => $validated['nombre'],
            'email' => $validated['email'],
            'phone_number' => $validated['celular'],
            'role' => $validated['role'],
            ...($validated['password'] ?? [] ? ['password' => $validated['password']] : [])
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // No puede eliminarse a sí mismo
        if ($user->id === auth()->id()) {
            return back()->withErrors([
                'error' => 'No puedes eliminar tu propio usuario.'
            ]);
        }

        $user->crops()->detach();
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function search(Request $request)
    {
        $search = $request->search;

        $result = User::query()
            ->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone_number']);

        return $result;
    }
}
