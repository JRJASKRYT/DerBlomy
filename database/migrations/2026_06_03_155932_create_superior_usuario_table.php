<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('superior_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('superior_id')->constrained('superiores')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['superior_id', 'usuario_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('superior_usuario');
    }
};   
