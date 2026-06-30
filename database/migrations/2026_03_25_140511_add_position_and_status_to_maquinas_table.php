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
            $table->integer('pos_x')->default(0);
            $table->integer('pos_y')->default(0);
            $table->string('status')->default('active'); // active, inactive, warning
        });
    }

    public function down(): void
    {
        Schema::table('maquinas', function (Blueprint $table) {
            $table->dropColumn(['pos_x', 'pos_y', 'status']);
        });
    }
};
