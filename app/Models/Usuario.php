<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // <-- Debe ser este
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'usuarios'; // <-- Obligatorio para que use tu tabla SQL

    protected $fillable = ['nombre', 'puesto', 'ubicacion', 'area', 'ci'];
    public function tramites()
    {
        return $this->belongsToMany(Tramite::class, 'tramite_usuario', 'usuario_id', 'tramite_id')
            ->withTimestamps();
    }
 
}