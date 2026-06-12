<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tramites', function (Blueprint $table) {
            // Creamos un campo enum. Por defecto, todo trámite nuevo iniciará como 'iniciado'
            $table->enum('estado', ['iniciado', 'en curso', 'terminado'])->default('iniciado')->after('nombre');
        });
    }

    public function down(): void
    {
        Schema::table('tramites', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};