@extends('Der.layout')

@section('contenido')
<div class="p-4 mb-4 bg-white rounded-3 shadow-sm border">
    <h3 class="text-dark"><i class="bi bi-person-plus-fill text-success me-2"></i> Registrar Nuevo Usuario</h3>
    <p class="text-muted mb-0">Introduce los datos del nuevo colaborador para ingresarlo en la base de datos de la empresa.</p>
</div>

@if(session('exito'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('exito') }}
    </div>
@endif

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 bg-white">
            <div class="card-header bg-dark text-white py-3">
                <h6 class="m-0"><i class="bi bi-card-list me-2"></i> Formulario de Alta de Personal</h6>
            </div>
            <div class="card-body p-4">
                
                <form action="/Der/usuarios-store" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej. María López" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Puesto / Cargo</label>
                        <input type="text" name="puesto" class="form-control" placeholder="Ej. Analista de Crédito" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ubicación Física</label>
                        <input type="text" name="ubicacion" class="form-control" placeholder="Ej. Oficina Norte - Piso 2">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Área Asignada</label>
                        <input type="text" name="area" class="form-control" placeholder="Ej. Finanzas">
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-person-plus me-1"></i> Guardar Empleado
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection