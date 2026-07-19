<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ConfigParamsController extends Controller
{
	public function index(Request $request): Response
	{
		$user = $request->user();

		return Inertia::render('customer/ConfigParams', [
			'user' => [
				'name' => $user->name,
				'email' => $user->email,
				'phone_number' => $user->phone_number,
			],
		]);
	}

	public function update(Request $request)
	{
		$user = $request->user();

		$validated = $request->validate([
			'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
			'celular' => ['required', 'digits:10', Rule::unique('users', 'phone_number')->ignore($user->id)],
		]);

		$user->update([
			'email' => $validated['email'],
			'phone_number' => $validated['celular'],
		]);

		return redirect()
			->route('client.settings')
			->with('success', 'Configuracion actualizada correctamente');
	}

}
