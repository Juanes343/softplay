<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Importa el modelo User

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // Retorna la vista del formulario de login
        return view('auth.login');
    }

    /**
     * Maneja la autenticación del usuario.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validación de las credenciales recibidas
        $credentials = $request->validate([
            'email' => ['required', 'email'],     // El correo es requerido y debe ser un correo válido
            'password' => ['required'],           // La contraseña es requerida
        ]);

        // Intento de autenticación con las credenciales
        if (Auth::attempt($credentials)) {
            // Si es exitoso, se regenera la sesión para mayor seguridad
            $request->session()->regenerate();

            // Redirige al dashboard
            return redirect()->intended('dashboard');
        }

        // Si la autenticación falla, regresa al formulario con un mensaje de error
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    /**
     * Muestra el dashboard con la lista de usuarios.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Obtiene todos los usuarios de la base de datos
        $users = User::all();

        // Retorna la vista del dashboard, pasando los usuarios a la misma
        return view('dashboard', compact('users'));
    }

    /**
     * Cierra la sesión del usuario autenticado.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Cierra la sesión del usuario actual
        Auth::logout();

        // Invalida la sesión actual
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirige a la página de inicio
        return redirect('/');
    }
}