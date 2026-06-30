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
        Schema::table('maquinas', function (Blueprint $table) {
            $table->string('external_id')->nullable()->after('id');
            $table->text('descripcion')->nullable()->after('nombre');
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->string('barcode')->nullable();
            $table->string('model')->nullable();
            $table->string('status')->nullable(); // deployed, in-stock
            $table->unsignedBigInteger('maquina_id')->nullable();
            $table->date('installed_at')->nullable();
            $table->dropColumn('stock');
        });
    }

    public function down(): void
    {
        Schema::table('maquinas', function (Blueprint $table) {
            $table->dropColumn(['external_id', 'descripcion']);
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->integer('stock')->default(0);
            $table->dropColumn(['type', 'barcode', 'model', 'status', 'maquina_id', 'installed_at']);
        });
    }
};
