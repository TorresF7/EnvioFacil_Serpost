<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('envio_id')->constrained()->cascadeOnDelete();
            $table->string('tipo')->default('certificado');
            $table->string('entidad')->nullable();
            $table->string('nombre_original');
            $table->string('ruta');
            $table->string('mime')->nullable();
            $table->unsignedInteger('tamano_bytes')->default(0);
            $table->foreignId('subido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
