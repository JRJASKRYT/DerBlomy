<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validamos que nos envíen el ID del usuario en la petición
        $request->validate([
            'usuario_id' => 'required|integer',
        ]);

        // 2. Buscamos si el usuario existe en la tabla 'usuarios'
        $usuario = Usuario::find($request->usuario_id);

        if (!$usuario) {
            return response()->json([
                'status' => 'error',
                'message' => 'El usuario con ese ID no existe en el sistema.'
            ], 404);
        }

        // 3. Generamos el token de Sanctum (se guardará en personal_access_tokens)
        $token = $usuario->createToken('auth_token')->plainTextToken;

        // 4. Respondemos con el token que usará el cliente de ahora en adelante
        return response()->json([
            'status' => 'success',
            'message' => 'Login correcto',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'usuario' => $usuario
        ], 200);
    }

    public function logout(Request $request)
    {
        // Revoca (elimina) el token que el usuario usó para esta sesión
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sesión cerrada y token destruido.'
        ], 200);
    }
}