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
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historial_tickets', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropColumn('usuario_id');
        });
    }
};
