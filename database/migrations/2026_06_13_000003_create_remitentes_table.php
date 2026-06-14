<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remitentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('envio_id')->constrained()->cascadeOnDelete();
            $table->string('tipo_documento');
            $table->string('numero_documento');
            $table->string('nombre_completo');
            $table->string('empresa')->nullable();
            $table->string('ruc')->nullable();
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('departamento')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('telefono');
            $table->string('email')->nullable();

            $table->boolean('depositante_es_remitente')->default(true);
            $table->string('depositante_nombre')->nullable();
            $table->string('depositante_tipo_doc')->nullable();
            $table->string('depositante_numero_doc')->nullable();
            $table->string('depositante_direccion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remitentes');
    }
};
