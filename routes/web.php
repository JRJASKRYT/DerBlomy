<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Tramite;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| 1. MÓDULO DE AUTENTICACIÓN & LOGIN
|--------------------------------------------------------------------------
*/

// Mostrar pantalla de Login
Route::get('/Der', function () {
    return view('Der.login');
});

// Procesar el formulario de Login mediante CI
Route::post('/Der/login-web', function (Request $request) {
    // Buscamos al usuario comparando el campo 'ci' de la base de datos
    $usuario = Usuario::with(['tramites'])
                ->where('ci', $request->usuario_ci)
                ->first();

    // Si no se encuentra ningún usuario con ese CI, lo rechazamos
    if (!$usuario) {
        return redirect('/Der')->with('error', 'El CI introducido no está registrado en el sistema.');
    }

    // Guardamos todos sus datos en la sesión
    session([
        'usuario_id'              => $usuario->id,
        'usuario_ci'              => $usuario->ci,
        'usuario_nombre'          => $usuario->nombre,
        'usuario_puesto'          => $usuario->puesto,
        'usuario_ubicacion'       => $usuario->ubicacion ?? 'Oficina Central',
        'usuario_areas'           => $usuario->area ?? 'General',
        'usuario_superior_nombre' => 'Ing. Carlos Mendoza', 
        'usuario_superior_puesto' => 'Gerente de Operaciones',
    ]);

    return redirect('/Der/inicio');
});

// Cerrar Sesión
Route::get('/Der/logout', function () {
    session()->flush();
    return redirect('/Der');
});


/*
|--------------------------------------------------------------------------
| 2. MÓDULO DE REGISTRO PÚBLICO PROTEGIDO
|--------------------------------------------------------------------------
*/

// Mostrar la pantalla de registro (Protegida con clave maestra por URL)
Route::get('/Der/registro', function (Request $request) {
    $claveMaestra = "DerBlomy2026"; 

    if ($request->query('auth') !== $claveMaestra) {
        return redirect('/Der')->with('error', 'Código de acceso de registro incorrecto o expirado.');
    }

    return view('Der.registro');
});

// Validar la contraseña secreta antes de redirigir al registro
Route::post('/Der/verificar-clave-registro', function (Request $request) {
    $claveCorrecta = "DerBlomy2026";

    if ($request->clave_secreta !== $claveCorrecta) {
        return redirect('/Der')->with('error', 'La clave secreta para crear usuarios es incorrecta.');
    }

    return redirect('/Der/registro?auth=' . $claveCorrecta);
});

// Almacenar el nuevo usuario registrado en la base de datos
Route::post('/Der/registro-web', function (Request $request) {
    $request->validate([
        'ci'     => 'required|string|max:20',
        'nombre' => 'required|string|max:255',
        'puesto' => 'required|string|max:255',
    ]);

    $nuevoUsuario = Usuario::create([
        'ci'        => $request->ci,
        'nombre'    => $request->nombre,
        'puesto'    => $request->puesto,
        'ubicacion' => $request->ubicacion,
        'area'      => $request->area,
    ]);

    return redirect('/Der')->with('nuevo_id', $nuevoUsuario->id);
});


/*
|--------------------------------------------------------------------------
| 3. MÓDULO DE PANEL INTERNO (REQUUIERE INICIO DE SESIÓN)
|--------------------------------------------------------------------------
*/

// Ventana de Inicio / Expediente del Empleado
Route::get('/Der/inicio', function () {
    if (!session()->has('usuario_id')) return redirect('/Der');
    return view('Der.inicio');
});

// Ventana de Conexiones e Instrumentos
Route::get('/Der/conexiones', function () {
    if (!session()->has('usuario_id')) return redirect('/Der');
    
    $activosSimulados = [
        (object)['id' => 104, 'nombre' => 'Laptop Corporativa ThinkPad L14'],
        (object)['id' => 209, 'nombre' => 'Monitor Dell 24" UltraSharp']
    ];

    return view('Der.conexiones', ['activos' => $activosSimulados]);
});


/*
|--------------------------------------------------------------------------
| 4. MÓDULO DE GESTIÓN DE TRÁMITES Y DOCUMENTACIÓN
|--------------------------------------------------------------------------
*/

// Listar los trámites pertenecientes al usuario logueado
Route::get('/Der/tramites', function () {
    if (!session()->has('usuario_id')) return redirect('/Der');
    
    $usuario = Usuario::find(session('usuario_id'));
    $misTramites = $usuario->tramites;

    return view('Der.tramites', ['tramites' => $misTramites]);
});

// Procesar la creación de un trámite nuevo con su estado
Route::post('/Der/tramites-web', function (Request $request) {
    if (!session()->has('usuario_id')) return redirect('/Der');

    $tramite = Tramite::create([
        'nombre' => $request->nombre,
        'estado' => $request->estado,
    ]);
    
    $tramite->usuarios()->attach(session('usuario_id')); 

    return redirect('/Der/tramites');
});

// [AÑADIDA] Vista para visualizar los requisitos y archivos de un trámite específico
Route::get('/Der/tramites/{id}/documentacion', function ($id) {
    if (!session()->has('usuario_id')) return redirect('/Der');

    $tramite = Tramite::find($id);

    if (!$tramite) {
        return redirect('/Der/tramites')->with('error', 'El trámite solicitado no existe.');
    }

    return view('Der.documentacion', ['tramite' => $tramite]);
});

// Procesar la subida física de archivos (PDF/Word) de un trámite
Route::post('/Der/tramites/{id}/subir-archivos', function (Request $request, $id) {
    if (!session()->has('usuario_id')) return redirect('/Der');

    $request->validate([
        'archivo_ci'        => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        'archivo_solicitud' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
    ]);

    $tramite = Tramite::find($id);

    if (!$tramite) {
        return redirect('/Der/tramites');
    }

    // Procesar archivo de Cédula de Identidad
    if ($request->hasFile('archivo_ci')) {
        if ($tramite->archivo_ci) {
            Storage::disk('public')->delete($tramite->archivo_ci);
        }
        $rutaCi = $request->file('archivo_ci')->store('documentos', 'public');
        $tramite->archivo_ci = $rutaCi;
    }

    // Procesar archivo de Formulario de Solicitud
    if ($request->hasFile('archivo_solicitud')) {
        if ($tramite->archivo_solicitud) {
            Storage::disk('public')->delete($tramite->archivo_solicitud);
        }
        $rutaSolicitud = $request->file('archivo_solicitud')->store('documentos', 'public');
        $tramite->archivo_solicitud = $rutaSolicitud;
    }

    $tramite->save();

    return redirect('/Der/tramites/'.$id.'/documentacion');
});

// Eliminar un trámite por completo de la base de datos
Route::delete('/Der/tramites/{id}/delete', function ($id) {
    if (!session()->has('usuario_id')) return redirect('/Der');

    $tramite = Tramite::find($id);

    if ($tramite) {
        // Desvinculamos de la tabla intermedia y eliminamos los archivos si existían
        $tramite->usuarios()->detach();
        
        if ($tramite->archivo_ci) Storage::disk('public')->delete($tramite->archivo_ci);
        if ($tramite->archivo_solicitud) Storage::disk('public')->delete($tramite->archivo_solicitud);

        $tramite->delete();
    }

    return redirect('/Der/tramites');
});
// Ruta para eliminar un documento específico de un trámite sin borrar el trámite entero
Route::delete('/Der/tramites/{id}/borrar-archivo/{tipo_archivo}', function ($id, $tipoArchivo) {
    if (!session()->has('usuario_id')) return redirect('/Der');

    $tramite = Tramite::find($id);

    if (!$tramite) {
        return redirect('/Der/tramites');
    }

    // Comprobamos cuál es el campo que se desea limpiar ('archivo_ci' o 'archivo_solicitud')
    if ($tipoArchivo === 'archivo_ci' && $tramite->archivo_ci) {
        // 1. Lo borramos físicamente del servidor
        Storage::disk('public')->delete($tramite->archivo_ci);
        // 2. Lo dejamos en NULL en la base de datos
        $tramite->archivo_ci = null;
    } 
    
    if ($tipoArchivo === 'archivo_solicitud' && $tramite->archivo_solicitud) {
        Storage::disk('public')->delete($tramite->archivo_solicitud);
        $tramite->archivo_solicitud = null;
    }

    // Guardamos los cambios en la Base de Datos
    $tramite->save();

    return redirect('/Der/tramites/'.$id.'/documentacion');
});