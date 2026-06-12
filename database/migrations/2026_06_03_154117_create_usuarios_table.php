<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('puesto')->nullable();
            $table->string('ubicacion')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('usuarios');
    }
};   