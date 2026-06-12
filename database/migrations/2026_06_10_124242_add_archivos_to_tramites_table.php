<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::table('tramites', function (Blueprint $table) {
        // Columnas de texto para guardar las rutas de los archivos (pueden ser null si aún no se suben)
        $table->string('archivo_ci')->nullable()->after('estado');
        $table->string('archivo_solicitud')->nullable()->after('archivo_ci');
    });
}

public function down(): void
{
    Schema::table('tramites', function (Blueprint $table) {
        $table->dropColumn(['archivo_ci', 'archivo_solicitud']);
    });
}
};
