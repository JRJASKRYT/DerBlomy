@extends('Der.layout')

@section('contenido')
<div class="mb-3">
    <a href="/Der/tramites" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver a Mis Trámites
    </a>
</div>

<div class="p-4 mb-4 bg-white rounded-3 shadow-sm border">
    <h3 class="text-dark"><i class="bi bi-folder-fill text-warning me-2"></i> Documentación Adjunta</h3>
    <p class="text-muted mb-0">Suba o descargue los archivos oficiales para el trámite: <strong class="text-primary">{{ $tramite->nombre }}</strong></p>
</div>

<div class="card shadow-sm border-0 bg-white">
    <div class="card-header bg-dark text-white py-3">
        <h6 class="m-0"><i class="bi bi-cloud-arrow-up-fill me-2"></i> Gestión de Archivos (PDF / Word)</h6>
    </div>
    <div class="card-body p-4">
        
        <form action="/Der/tramites/{{ $tramite->id }}/subir-archivos" method="POST" enctype="multipart/form-data" id="formSubir">
            @csrf
            
            <div class="p-3 mb-3 bg-light rounded border">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-1 fw-bold">1. Fotocopia de documento de identidad (CI)</h6>
                        <small class="text-muted">Formatos admitidos: PDF, DOCX, DOC.</small>
                    </div>
                    <div class="col-md-6 text-md-end mt-2 mt-md-0">
                        <input type="file" name="archivo_ci" class="form-control form-control-sm mb-2" accept=".pdf,.doc,.docx">
                        @if($tramite->archivo_ci)
                            <div class="d-inline-flex gap-2">
                                <a href="{{ Storage::url($tramite->archivo_ci) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="bi bi-download"></i> Ver / Descargar
                                </a>
                                <button type="submit" form="formBorrarCi" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash3-fill"></i> Eliminar Archivo
                                </button>
                            </div>
                        @else
                            <span class="badge bg-danger">Sin archivo adjunto</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-3 mb-4 bg-light rounded border">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-1 fw-bold">2. Formulario de solicitud firmado</h6>
                        <small class="text-muted">Formatos admitidos: PDF, DOCX, DOC.</small>
                    </div>
                    <div class="col-md-6 text-md-end mt-2 mt-md-0">
                        <input type="file" name="archivo_solicitud" class="form-control form-control-sm mb-2" accept=".pdf,.doc,.docx">
                        @if($tramite->archivo_solicitud)
                            <div class="d-inline-flex gap-2">
                                <a href="{{ Storage::url($tramite->archivo_solicitud) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="bi bi-download"></i> Ver / Descargar
                                </a>
                                <button type="submit" form="formBorrarSolicitud" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash3-fill"></i> Eliminar Archivo
                                </button>
                            </div>
                        @else
                            <span class="badge bg-danger">Sin archivo adjunto</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-cloud-upload-fill me-1"></i> Guardar y Subir Documentos
                </button>
            </div>
        </form>

        @if($tramite->archivo_ci)
            <form action="/Der/tramites/{{ $tramite->id }}/borrar-archivo/archivo_ci" method="POST" id="formBorrarCi" onsubmit="return confirm('¿Seguro que deseas eliminar este documento de identidad?');">
                @csrf
                @method('DELETE')
            </form>
        @endif

        @if($tramite->archivo_solicitud)
            <form action="/Der/tramites/{{ $tramite->id }}/borrar-archivo/archivo_solicitud" method="POST" id="formBorrarSolicitud" onsubmit="return confirm('¿Seguro que deseas eliminar este formulario de solicitud?');">
                @csrf
                @method('DELETE')
            </form>
        @endif

    </div>
</div>
@endsection