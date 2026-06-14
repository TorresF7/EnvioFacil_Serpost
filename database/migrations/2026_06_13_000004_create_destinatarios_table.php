<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinatarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('envio_id')->constrained()->cascadeOnDelete();
            $table->string('nombre_completo');
            $table->string('empresa')->nullable();
            $table->string('referencia_importador')->nullable();
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('estado_region')->nullable();
            $table->string('codigo_postal');
            $table->string('pais');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinatarios');
    }
};
