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
        Schema::table('archivo_adjuntos', function (Blueprint $table) {
            $table->boolean('visible_operadores')->default(false)->after('ticket_id');
        });

        // Also add to documentos table for general files
        if (Schema::hasTable('documentos')) {
            Schema::table('documentos', function (Blueprint $table) {
                if (!Schema::hasColumn('documentos', 'visible_operadores')) {
                    $table->boolean('visible_operadores')->default(false)->after('licencia');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archivo_adjuntos', function (Blueprint $table) {
            $table->dropColumn('visible_operadores');
        });

        if (Schema::hasTable('documentos')) {
            Schema::table('documentos', function (Blueprint $table) {
                if (Schema::hasColumn('documentos', 'visible_operadores')) {
                    $table->dropColumn('visible_operadores');
                }
            });
        }
    }
};

