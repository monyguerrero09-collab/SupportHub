<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('historial_tickets', function (Blueprint $table) {
            $table->foreignId('causa_solucion_id')->nullable()->constrained('causa_solucions')->nullOnDelete();
            $table->foreignId('motivo_cancelacion_id')->nullable()->constrained('motivo_cancelacions')->nullOnDelete();
            $table->text('detalles')->nullable();
            $table->string('adjunto_path')->nullable();
            $table->boolean('visible_para_usuario')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historial_tickets', function (Blueprint $table) {
            $table->dropForeign(['causa_solucion_id']);
            $table->dropForeign(['motivo_cancelacion_id']);
            $table->dropColumn(['causa_solucion_id', 'motivo_cancelacion_id', 'detalles', 'adjunto_path', 'visible_para_usuario']);
        });
    }
};
