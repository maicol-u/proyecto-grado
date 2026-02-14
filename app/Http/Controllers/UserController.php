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
     * Show the form for editing the specified usser.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
