<?php

namespace App\Http\Controllers;

use App\Models\Invernadero;
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
}
