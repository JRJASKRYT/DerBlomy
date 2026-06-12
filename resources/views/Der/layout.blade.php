<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet - DerBlomy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { min-height: 100vh; background-color: #212529; }
        .sidebar .nav-link { color: #adb5bd; }
        .sidebar .nav-link.active { color: #fff; background-color: #0d6efd; border-radius: 5px; }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar p-3">
                <div class="text-center my-3 text-white">
                    <i class="bi bi-person-circle fs-1 text-primary"></i>
                    <h6 class="mt-2 mb-0">{{ session('usuario_nombre', 'Usuario') }}</h6>
                    <small class="text-muted">{{ session('usuario_puesto', 'Empleado') }}</small>
                </div>
                <hr class="text-secondary">
                <ul class="nav flex-column gap-2">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('Der/inicio') ? 'active' : '' }}" href="/Der/inicio">
                            <i class="bi bi-house-door-fill me-2"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('Der/tramites') ? 'active' : '' }}" href="/Der/tramites">
                            <i class="bi bi-file-earmark-text-fill me-2"></i> Mis Trámites
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('Der/conexiones') ? 'active' : '' }}" href="/Der/conexiones">
                            <i class="bi bi-diagram-3-fill me-2"></i> Mis Conexiones
                        </a>
                    </li>
                </ul>
                <hr class="text-secondary mt-5">
                <a href="/Der/logout" class="btn btn-outline-danger w-100 btn-sm">
                    <i class="bi bi-box-arrow-left me-2"></i> Salir
                </a>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @yield('contenido')
            </main>
        </div>
    </div>
</body>
</html>