<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Añadimos el campo CI. Lo hacemos 'unique' para que no haya dos iguales
            // y 'nullable' por si ya tienes usuarios creados viejos sin CI.
            $table->string('ci')->unique()->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('ci');
        });
    }
};