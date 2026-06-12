<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TramiteController extends Controller
{
    // GET /api/tramites -> Lista todos los trámites con sus usuarios asignados
    // GET /api/tramites -> Muestra SOLO los trámites del usuario autenticado
    // GET /api/tramites
    public function index(Request $request)
    {
        // En lugar de buscar por Token, leemos el id que mandamos en la URL (?usuario_id=X)
        $usuarioId = $request->query('usuario_id');

        if (!$usuarioId) {
            return response()->json(['message' => 'Falta el parámetro usuario_id.'], 400);
        }

        // Buscamos el usuario en la base de datos
        $usuario = \App\Models\Usuario::find($usuarioId);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        // Carga los trámites vinculados únicamente a este usuario a través de la tabla pivote
        $misTramites = $usuario->tramites()->get();

        return response()->json($misTramites, 200);
    }

    // POST /api/tramites
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'estado' => 'required|string|max:50',
            'usuario_id' => 'required|exists:usuarios,id' // Valida que el usuario exista
        ]);

        try {
            DB::beginTransaction();

            // 1. Insertar en la tabla 'tramites' incluyendo el estado
            $tramite = Tramite::create([
                'nombre' => $request->nombre,
                'estado' => $request->estado // Guardamos el estado que viene del select HTML
            ]);

            // 2. Insertar automáticamente en la tabla pivote 'tramite_usuario'
            $tramite->usuarios()->attach($request->usuario_id);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Trámite creado y asignado con éxito.',
                'data' => $tramite
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    // GET /api/tramites/{id}/documentacion -> Ver los documentos de un trámite específico
    public function showDocumentacion($id)
    {
        $tramite = Tramite::find($id);

        if (!$tramite) {
            return response()->json(['message' => 'Trámite no encontrado.'], 404);
        }

        // Retorna la colección de la tabla 'documentacion' vinculada a este trámite
        return response()->json($tramite->documentaciones, 200);
    }
}