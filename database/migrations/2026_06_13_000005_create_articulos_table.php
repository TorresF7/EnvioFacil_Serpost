<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('envio_id')->constrained()->cascadeOnDelete();
            $table->string('descripcion');
            $table->integer('cantidad');
            $table->integer('peso_neto_gramos');
            $table->decimal('valor', 10, 2);
            $table->string('pais_origen')->default('Perú');
            $table->string('codigo_hs')->nullable();
            $table->string('categoria')->default('permitido');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
