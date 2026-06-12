<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card p-4 shadow text-center" style="max-width: 400px; width: 100%;">
        <h3>Acceso al Sistema ⭐DERBLOMY⭐</h3>
        
        <form action="/Der/login-web" method="POST" class="mt-3 text-start">
    @csrf
    <div class="mb-3">
        <label class="form-label fw-bold"><i class="bi bi-card-text me-1"></i> Cédula de Identidad (CI):</label>
        <input type="text" name="usuario_ci" class="form-control form-control-lg" placeholder="Ej. 1234567 LP" required>
    </div>
    <button type="submit" class="btn btn-primary w-100 btn-lg">Ingresar al Panel</button>
</form> @if(session('nuevo_id'))
            <div class="alert alert-success mt-3">
                <i class="bi bi-check-circle-fill"></i> ¡Registro exitoso!<br>
                Tu ID de acceso es: <strong>{{ session('nuevo_id') }}</strong>
            </div>
        @endif

        <hr class="mt-4">
        <div class="text-center">
            <p class="text-muted small mb-2 fw-bold"><i class="bi bi-lock-fill"></i> Área de Registro Protegida</p>
            
            <form action="/Der/verificar-clave-registro" method="POST" class="text-start">
                @csrf
                <div class="input-group input-group-sm mb-2">
                    <input type="password" name="clave_secreta" class="form-control" placeholder="Introduce la Clave Maestra" required>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-arrow-right-short"></i> Entrar
                    </button>
                </div>
            </form>
        </div>

        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif
    </div>
</body>
</html>