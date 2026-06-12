@extends('Der.layout')

@section('contenido')
<div class="p-4 mb-4 bg-white rounded-3 shadow-sm border">
    <h3 class="text-dark"><i class="bi bi-diagram-3-fill text-primary me-2"></i> Mis Conexiones y Activos</h3>
    <p class="text-muted mb-0">Consulta tu red de trabajo, superiores asignados y herramientas corporativas a tu cargo.</p>
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 bg-white h-100">
            <div class="card-header bg-dark text-white py-3">
                <h6 class="m-0"><i class="bi bi-person-fill-up me-2"></i> Estructura de Reporte (Superior)</h6>
            </div>
            <div class="card-body text-center py-4">
                <i class="bi bi-person-bounding-box text-secondary display-4 mb-2"></i>
                <h5 class="fw-bold text-primary">{{ session('usuario_superior_nombre', 'No asignado') }}</h5>
                <p class="text-muted small mb-0">Jefe Directo / Supervisor</p>
                <hr>
                <div class="text-start px-3">
                    <p class="small mb-1"><strong>Puesto del Jefe:</strong> {{ session('usuario_superior_puesto', 'N/A') }}</p>
                    <p class="small mb-0"><strong>Contacto Interno:</strong> Ext. 402</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm border-0 bg-white h-100">
            <div class="card-header bg-dark text-white py-3">
                <h6 class="m-0"><i class="bi bi-laptop me-2"></i> Herramientas y Activos Asignados</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Código</th>
                                <th>Descripción del Activo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activos as $activo)
                                <tr>
                                    <td><span class="badge bg-light text-dark border">#{{ $activo->id }}</span></td>
                                    <td><strong>{{ $activo->nombre }}</strong></td>
                                    <td><span class="badge bg-success-subtle text-success">Buen Estado</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="bi bi-box-seismic d-block fs-3 mb-2"></i>
                                        No tienes herramientas registradas a tu cargo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection