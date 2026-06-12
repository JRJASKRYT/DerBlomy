<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tramite_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['usuario_id', 'tramite_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('tramite_usuario');
    }
};   