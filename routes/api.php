<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Usuario;
use App\Models\Tramite;

/*
|--------------------------------------------------------------------------
| API ENDPOINTS - INTRARENET DERBLOMY
|--------------------------------------------------------------------------
| Base URL general para consumo: http://127.0.0.1:8000/api
*/

/*
|--------------------------------------------------------------------------
| MÓDULO 1: AUTENTICACIÓN / LOGIN
|--------------------------------------------------------------------------
*/

// Validar el Login del usuario mediante su CI (Método: POST)
// URL: http://127.0.0.1:8000/api/login
Route::post('/login', function (Request $request) {
    
    // 1. Validamos que el JSON envíe el campo 'usuario_ci'
    $request->validate([
        'usuario_ci' => 'required|string',
    ]);

    // 2. Buscamos al usuario junto con sus trámites asociados
    $usuario = Usuario::with(['tramites'])
                ->where('ci', $request->json('usuario_ci'))
                ->first();

    // 3. Si el usuario no existe, devolvemos un código de error 401
    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas. El CI introducido no está registrado.'
        ], 401);
    }

    // 4. Si existe, devolvemos toda su información y su historial de trámites
    return response()->json([
        'success' => true,
        'message' => 'Autenticación exitosa',
        'user'    => [
            'id'        => $usuario->id,
            'ci'        => $usuario->ci,
            'nombre'    => $usuario->nombre,
            'puesto'    => $usuario->puesto,
            'ubicacion' => $usuario->ubicacion ?? 'Oficina Central',
            'superior_inmediato' => 'Ing. Carlos Mendoza (Gerente de Operaciones)'
        ],
        'tramites_activos' => $usuario->tramites
    ], 200);
});


/*
|--------------------------------------------------------------------------
| MÓDULO 2: GESTIÓN DE USUARIOS
|--------------------------------------------------------------------------
*/

// 1. Obtener todos los usuarios (Método: GET)
// URL: http://127.0.0.1:8000/api/usuarios
Route::get('/usuarios', function () {
    $usuarios = Usuario::all();
    return response()->json([
        'success' => true,
        'data'    => $usuarios
    ], 200);
});

// 2. Obtener un solo usuario por su CI (Método: GET)
// URL: http://127.0.0.1:8000/api/usuarios/{ci}
Route::get('/usuarios/{ci}', function ($ci) {
    $usuario = Usuario::where('ci', $ci)->first();

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado con ese CI'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data'    => $usuario
    ], 200);
});

// 3. Crear un nuevo usuario cuidando la estructura de la BD (Método: POST)
// URL: http://127.0.0.1:8000/api/usuarios
Route::post('/usuarios', function (Request $request) {
    $request->validate([
        'ci'     => 'required|string|max:20|unique:usuarios,ci',
        'nombre' => 'required|string|max:255',
        'puesto' => 'required|string|max:255',
    ]);

    // Mapeamos 'area' a 'ubicacion' para adaptarnos a las columnas reales de MySQL
    $ubicacionFinal = $request->json('ubicacion');
    if ($request->json('area')) {
        $ubicacionFinal = $request->json('area') . ($request->json('ubicacion') ? " - " . $request->json('ubicacion') : "");
    }

    $usuario = Usuario::create([
        'ci'        => $request->json('ci'),
        'nombre'    => $request->json('nombre'),
        'puesto'    => $request->json('puesto'),
        'ubicacion' => $ubicacionFinal,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Usuario creado exitosamente',
        'data'    => $usuario
    ], 201);
});

// 4. Actualizar un usuario por su CI (Método: PUT)
// URL: http://127.0.0.1:8000/api/usuarios/{ci}
Route::put('/usuarios/{ci}', function (Request $request, $ci) {
    $usuario = Usuario::where('ci', $ci)->first();

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado para actualizar'
        ], 404);
    }

    $request->validate([
        'ci'     => 'string|max:20|unique:usuarios,ci,' . $usuario->id,
        'nombre' => 'string|max:255',
        'puesto' => 'string|max:255',
    ]);

    $usuario->update([
        'ci'        => $request->json('ci', $usuario->ci),
        'nombre'    => $request->json('nombre', $usuario->nombre),
        'puesto'    => $request->json('puesto', $usuario->puesto),
        'ubicacion' => $request->json('ubicacion', $usuario->ubicacion),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Usuario actualizado de forma correcta',
        'data'    => $usuario
    ], 200);
});

// 5. Eliminar un usuario por su CI (Método: DELETE)
// URL: http://127.0.0.1:8000/api/usuarios/{ci}
Route::delete('/usuarios/{ci}', function ($ci) {
    $usuario = Usuario::where('ci', $ci)->first();

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado para eliminar'
        ], 404);
    }

    // Desvinculamos de la tabla intermedia (pivot) antes de borrar definitivamente
    $usuario->tramites()->detach();
    $usuario->delete();

    return response()->json([
        'success' => true,
        'message' => 'Usuario eliminado por completo de la base de datos'
    ], 200);
});


/*
|--------------------------------------------------------------------------
| MÓDULO 3: GESTIÓN DE TRÁMITES Y DOCUMENTACIÓN
|--------------------------------------------------------------------------
*/

// 1. Crear un trámite asignándolo a un usuario (Método: POST)
// URL: http://127.0.0.1:8000/api/tramites
Route::post('/tramites', function (Request $request) {
    $request->validate([
        'usuario_ci' => 'required|string',
        'nombre'     => 'required|string|max:255',
        'estado'     => 'nullable|string|in:iniciado,en curso,terminado',
    ]);

    $usuario = Usuario::where('ci', $request->json('usuario_ci'))->first();

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'No se encontró el usuario con el CI proporcionado. No se puede crear el trámite.'
        ], 404);
    }

    $tramite = Tramite::create([
        'nombre'            => $request->json('nombre'),
        'estado'            => $request->json('estado', 'iniciado'),
        'archivo_ci'        => $request->json('archivo_ci'),
        'archivo_solicitud' => $request->json('archivo_solicitud'),
    ]);

    $tramite->usuarios()->attach($usuario->id);

    return response()->json([
        'success' => true,
        'message' => 'Trámite creado y asignado exitosamente',
        'data'    => $tramite
    ], 201);
});

// 2. Actualizar el estado o las rutas de documentos de un trámite (Método: PUT)
// URL: http://127.0.0.1:8000/api/tramites/{id}
Route::put('/tramites/{id}', function (Request $request, $id) {
    $tramite = Tramite::find($id);

    if (!$tramite) {
        return response()->json([
            'success' => false,
            'message' => 'El trámite no existe'
        ], 404);
    }

    $tramite->update([
        'estado'            => $request->json('estado', $tramite->estado),
        'archivo_ci'        => $request->json('archivo_ci', $tramite->archivo_ci),
        'archivo_solicitud' => $request->json('archivo_solicitud', $tramite->archivo_solicitud),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Trámite/Documentación actualizada correctamente',
        'data'    => $tramite
    ], 200);
});
// 3. Obtener los trámites de un usuario mediante su CI (Método: GET)
// URL: http://127.0.0.1:8000/api/tramites/{ci}
Route::get('/tramites/{ci}', function ($ci) {
    $usuario = Usuario::where('ci', $ci)->first();

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ], 404);
    }

    // Retornamos únicamente la colección de trámites asociados de este usuario
    return response()->json($usuario->tramites, 200);
});
// 4. Obtener las conexiones y activos de un usuario por su CI (Método: GET)
// URL: http://127.0.0.1:8000/api/conexiones/{ci}
Route::get('/conexiones/{ci}', function ($ci) {
    $usuario = Usuario::where('ci', $ci)->first();

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ], 404);
    }

    // Estructuramos la respuesta dinámicamente según lo que necesita la interfaz
    return response()->json([
        'success' => true,
        'jefe' => [
            'nombre' => 'Ing. Carlos Mendoza',
            'puesto' => 'Gerente de Operaciones',
            'contacto' => 'Ext. 402'
        ],
        // Aquí simulamos los activos asignados. Si en un futuro creas la tabla 'activos', 
        // simplemente los cargas con: $usuario->activos
        'activos' => [
            [
                'codigo' => '#104',
                'descripcion' => 'Laptop Corporativa ThinkPad L14',
                'estado' => 'Buen Estado'
            ],
            [
                'codigo' => '#209',
                'descripcion' => 'Monitor Dell 24" UltraSharp',
                'estado' => 'Buen Estado'
            ]
        ]
    ], 200);
});
// URL: http://127.0.0.1:8000/api/tramites/{id}
Route::put('/tramites/{id}', function (Request $request, $id) {
    $tramite = Tramite::find($id);

    if (!$tramite) {
        return response()->json([
            'success' => false,
            'message' => 'El trámite no existe'
        ], 404);
    }

    // Leemos los campos del JSON. Si explícitamente mandamos "NULL" o vacío, se borrará el registro
    $tramite->update([
        'estado'            => $request->json('estado', $tramite->estado),
        'archivo_ci'        => $request->has('archivo_ci') ? $request->json('archivo_ci') : $tramite->archivo_ci,
        'archivo_solicitud' => $request->has('archivo_solicitud') ? $request->json('archivo_solicitud') : $tramite->archivo_solicitud,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Trámite/Documentación actualizada correctamente',
        'data'    => $tramite
    ], 200);
});
// 5. Obtener las habilitaciones asignadas a un usuario por su CI (Método: GET)
// URL: http://127.0.0.1:8000/api/habilitaciones/{ci}
Route::get('/habilitaciones/{ci}', function ($ci) {
    $usuario = Usuario::where('ci', $ci)->first();

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ], 404);
    }

    // Estructuramos una respuesta dinámica. En el futuro, si creas una tabla 'habilitaciones',
    // podrás cargarla directamente mediante relaciones: $usuario->habilitaciones
    return response()->json([
        'success' => true,
        'usuario_nombre' => $usuario->nombre,
        'habilitaciones' => [
            [
                'id' => 1,
                'modulo' => 'Servidores Críticos',
                'tipo_acceso' => 'Administrador (SSH / Root)',
                'fecha_emision' => '2026-01-15',
                'estado' => 'activo'
            ],
            [
                'id' => 2,
                'modulo' => 'Base de Datos de Producción',
                'tipo_acceso' => 'Solo Lectura (Read-Only)',
                'fecha_emision' => '2026-02-10',
                'estado' => 'activo'
            ],
            [
                'id' => 3,
                'modulo' => 'Repositorios DerBlomy (GitHub)',
                'tipo_acceso' => 'Desarrollador / Write',
                'fecha_emision' => '2026-03-01',
                'estado' => 'activo'
            ],
            [
                'id' => 4,
                'modulo' => 'Módulo Financiero ERP',
                'tipo_acceso' => 'Sin Acceso / Denegado',
                'fecha_emision' => 'N/A',
                'estado' => 'inactivo'
            ]
        ]
    ], 200);
});
// 6. Obtener los recursos propios de un usuario por su CI (Método: GET)
// URL: http://127.0.0.1:8000/api/recursos-propios/{ci}
Route::get('/recursos-propios/{ci}', function ($ci) {
    $usuario = Usuario::where('ci', $ci)->first();

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ], 404);
    }

    // Retornamos la estructura de recursos propios. 
    // Si más adelante creas la tabla 'recursos', aquí harías un: $usuario->recursos
    return response()->json([
        'success' => true,
        'recursos' => [
            [
                'id' => 1,
                'recurso' => 'Token de Seguridad RSA (MFA)',
                'categoria' => 'Credenciales / Accesos',
                'identificador' => 'TOK-99382-X',
                'estado' => 'Asignado'
            ],
            [
                'id' => 2,
                'recurso' => 'Licencia JetBrains All Products Pack',
                'categoria' => 'Software / Desarrollo',
                'identificador' => 'JB-PERM-2026',
                'estado' => 'Asignado'
            ],
            [
                'id' => 3,
                'recurso' => 'Cuenta Corporativa AWS Sandbox',
                'categoria' => 'Infraestructura / Cloud',
                'identificador' => 'aws-dev-user-04',
                'estado' => 'En Revisión'
            ]
        ]
    ], 200);
});
// Eliminar un recurso
Route::delete('/recursos/{id}', function ($id) {
    // Aquí iría tu lógica: Recurso::find($id)->delete();
    return response()->json(['success' => true, 'message' => 'Recurso eliminado']);
});

// Editar un recurso
Route::put('/recursos/{id}', function (Request $request, $id) {
    // Aquí iría tu lógica: Recurso::find($id)->update($request->all());
    return response()->json(['success' => true, 'message' => 'Recurso actualizado']);
});
// Actualizar una habilitación
Route::put('/habilitaciones/{id}', function (Request $request, $id) {
    // Aquí actualizas en tu BD
    return response()->json(['success' => true, 'message' => 'Habilitación actualizada']);
});

// Eliminar una habilitación
Route::delete('/habilitaciones/{id}', function ($id) {
    // Aquí borras de tu BD
    return response()->json(['success' => true, 'message' => 'Habilitación eliminada']);
});