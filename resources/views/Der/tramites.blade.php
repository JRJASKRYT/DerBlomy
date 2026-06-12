@extends('Der.layout')

@section('contenido')
<h3><i class="bi bi-file-earmark-text-fill text-primary me-2"></i> Ventana de Mis Trámites</h3>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm bg-white">
            <h5>Nueva Solicitud</h5>
            <form action="/Der/tramites-web" method="POST" class="mt-2">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre del Trámite</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej. Vacaciones" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Estado Inicial</label>
                    <select name="estado" class="form-select">
                        <option value="iniciado">Iniciado</option>
                        <option value="en curso">En Curso</option>
                        <option value="terminado">Terminado</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-success w-100">Guardar Trámite</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card p-3 shadow-sm bg-white">
            <h5>Historial Registrado</h5>
            <table class="table table-striped mt-3 align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Trámite</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tramites as $t)
                        <tr>
                            <td>{{ $t->id }}</td>
                            <td><strong>{{ $t->nombre }}</strong></td>
                            <td>
                                @if($t->estado == 'iniciado')
                                    <span class="badge bg-secondary text-uppercase">Iniciado</span>
                                @elseif($t->estado == 'en curso')
                                    <span class="badge bg-warning text-dark text-uppercase">En Curso</span>
                                @elseif($t->estado == 'terminado')
                                    <span class="badge bg-success text-uppercase">Terminado</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="/Der/tramites/{{ $t->id }}/documentacion" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye-fill"></i> Requisitos
                                    </a>

                                    <form action="/Der/tramites/{{ $t->id }}/delete" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este trámite?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection