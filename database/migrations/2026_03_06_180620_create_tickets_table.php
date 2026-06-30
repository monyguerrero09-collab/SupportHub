<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->foreignId('tipo_ticket_id')->constrained('tipo_tickets')->onDelete('cascade');
            $table->foreignId('estado_id')->constrained('estado_tickets')->onDelete('cascade');
            $table->foreignId('prioridad_id')->constrained('prioridads')->onDelete('cascade');
            $table->foreignId('usuario_creador_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('agente_asignado_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->foreignId('departamento_id')->nullable()->constrained('departamentos')->onDelete('set null');
            $table->foreignId('sector_id')->nullable()->constrained('sectors')->onDelete('set null');
            $table->foreignId('maquina_id')->nullable()->constrained('maquinas')->onDelete('set null');
            $table->timestamps(); // includes Fecha_Creacion
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
