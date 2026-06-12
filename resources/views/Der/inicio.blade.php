@extends('Der.layout')

@section('contenido')
<div class="p-4 mb-4 bg-white rounded-3 shadow-sm border">
    <h1 class="display-6 fw-bold text-dark">Panel de Control General</h1>
    <p class="fs-5 text-muted">Bienvenido al sistema. Aquí puedes revisar tu expediente digital guardado en la base de datos.</p>
</div>

<div class="card shadow-sm border-0 bg-white">
    <div class="card-header bg-primary text-white p-3">
        <h5 class="card-title m-0"><i class="bi bi-person-badge-fill me-2"></i> Expediente Completo del Colaborador</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            
            <div class="col-md-6 border-end">
                <h6 class="text-secondary fw-bold text-uppercase mb-3">Información Personal</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 130px;">ID Empleado:</td>
                        <td><strong>#{{ session('usuario_id') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nombre Completo:</td>
                        <td><strong>{{ session('usuario_nombre') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Ubicación Física:</td>
                        <td><span class="badge bg-light text-dark border">{{ session('usuario_ubicacion', 'No registrada') }}</span></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <h6 class="text-secondary fw-bold text-uppercase mb-3">Estructura Organizacional</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 130px;">Puesto Actual:</td>
                        <td><strong class="text-primary">{{ session('usuario_puesto', 'No asignado') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Áreas Vinculadas:</td>
                        <td><strong>{{ session('usuario_areas') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Estado de Cuenta:</td>
                        <td><span class="badge bg-success">Activo</span></td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection