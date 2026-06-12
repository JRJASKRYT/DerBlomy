<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'estado', 'archivo_ci', 'archivo_solicitud'];

    // ESTA FUNCIÓN ES OBLIGATORIA para que se muestren en la tabla web
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'tramite_usuario', 'tramite_id', 'usuario_id');
    }
}