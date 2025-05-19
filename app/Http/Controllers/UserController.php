<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Obtiene todos los usuarios
        $users = User::all();

        // Retorna la vista de usuarios con los datos
        return view('users', compact('users'));
    }

    public function edit($id)
    {
        // Encuentra al usuario por ID
        $user = User::findOrFail($id);

        // Retorna la vista del formulario de edición
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Encuentra al usuario por ID
        $user = User::findOrFail($id);

        // Valida y actualiza los datos del usuario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Verifica que el correo sea único
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        // Redirecciona con un mensaje de éxito
        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito.');
    }

    public function showChangePasswordForm($id)
    {
        // Encuentra al usuario por ID
        $user = User::findOrFail($id);

        // Retorna la vista del formulario de cambio de contraseña
        return view('users.change_password', compact('user'));
    }

    public function changePassword(Request $request, $id)
    {
        // Valida los datos de la solicitud
        $request->validate([
            'password' => 'required|string|min:8|confirmed', // Debe ser confirmada
        ]);

        // Encuentra al usuario y actualiza la contraseña
        $user = User::findOrFail($id);
        $user->password = Hash::make($request->input('password')); // Hashea la nueva contraseña
        $user->save();

        // Redirecciona con un mensaje de éxito
        return redirect()->route('users.index')->with('success', 'Contraseña actualizada con éxito.');
    }

    public function destroy($id)
    {
        // Encuentra y elimina al usuario
        $user = User::findOrFail($id);
        $user->delete();

        // Redirecciona con un mensaje de éxito
        return redirect()->route('users.index')->with('success', 'Usuario eliminado con éxito.');
    }
}