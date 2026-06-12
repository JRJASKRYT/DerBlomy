<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('seguros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('seguros');
    }
};   