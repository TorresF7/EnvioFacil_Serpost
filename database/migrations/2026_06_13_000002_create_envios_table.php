<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('oficina_postal_id')->nullable()->constrained('oficinas_postales')->nullOnDelete();
            $table->string('codigo')->unique();
            $table->string('tipo_servicio');
            $table->string('pais_destino');
            $table->string('oficina_deposito');
            $table->string('tipo_envio');
            $table->string('naturaleza');
            $table->string('divisa')->default('USD');
            $table->decimal('peso_bruto', 8, 3)->nullable();
            $table->integer('largo_cm')->nullable();
            $table->integer('ancho_cm')->nullable();
            $table->integer('alto_cm')->nullable();
            $table->string('instruccion_no_entrega')->nullable();
            $table->integer('devolver_dias')->nullable();
            $table->string('direccion_redireccion')->nullable();
            $table->string('modalidad')->nullable();
            $table->string('via')->nullable();
            $table->integer('cantidad_bultos')->nullable();
            $table->decimal('gastos_porte', 10, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->decimal('valor_aduanas', 10, 2)->nullable();
            $table->decimal('valor_seguro', 10, 2)->nullable();
            $table->string('estado')->default('preadmision');
            $table->foreignId('atendido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('procesado_at')->nullable();

            $table->string('doc_comercial_tipo')->nullable();
            $table->string('doc_comercial_serie')->nullable();
            $table->string('doc_comercial_numero')->nullable();
            $table->string('doc_comercial_razon')->nullable();

            $table->string('certificado_numero')->nullable();
            $table->string('licencia_numero')->nullable();
            $table->string('certificado_entidad')->nullable();

            $table->string('notif_email')->nullable();
            $table->string('notif_whatsapp')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('envios');
    }
};
