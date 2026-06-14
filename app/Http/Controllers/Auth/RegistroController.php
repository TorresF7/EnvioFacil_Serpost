<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RolUsuario;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificacionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class RegistroController extends Controller
{
    public function mostrarRegistro(): Response
    {
        return Inertia::render('Auth/Registro');
    }

    public function registrar(Request $request, NotificacionService $notificaciones): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'numero_documento' => ['required', 'string', 'max:20'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'email.unique' => 'Ese correo ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $usuario = User::create([
            ...$datos,
            'rol' => RolUsuario::CLIENTE->value,
        ]);

        $notificaciones->bienvenida($usuario);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect('/app');
    }
}
