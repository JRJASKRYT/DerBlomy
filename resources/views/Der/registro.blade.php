<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Nuevo Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card p-4 shadow-lg" style="max-width: 450px; width: 100%;">
        <div class="text-center mb-3">
            <i class="bi bi-person-badge text-success" style="font-size: 3rem;"></i>
            <h3 class="mt-2">Crear Cuenta Nueva</h3>
            <p class="text-muted small">Regístrate para obtener tu ID de acceso al sistema</p>
        </div>

        <form action="/Der/registro-web" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Cédula de Identidad (CI)</label>
                <input type="text" name="ci" class="form-control" placeholder="Ej. 1234567 LP" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre Completo</label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej. Carlos Mendoza" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Puesto / Cargo</label>
                <input type="text" name="puesto" class="form-control" placeholder="Ej. Supervisor de Planta" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Ubicación Física</label>
                <input type="text" name="ubicacion" class="form-control" placeholder="Ej. Pabellón B - Oficina 4">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Área</label>
                <input type="text" name="area" class="form-control" placeholder="Ej. Logística">
            </div>

            <button type="submit" class="btn btn-success w-100 btn-lg mb-3">Registrarme</button>
        </form>

        <hr>
        <div class="text-center">
            <a href="/Der" class="text-decoration-none small"><i class="bi bi-arrow-left"></i> ¿Ya tienes cuenta? Inicia Sesión</a>
        </div>
    </div>

</body>
</html>