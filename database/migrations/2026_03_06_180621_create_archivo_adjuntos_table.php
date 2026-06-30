<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archivo_adjuntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->string('nombre_archivo');
            $table->string('ruta_archivo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archivo_adjuntos');
    }
};
