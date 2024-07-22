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
        Schema::table('tbl_imc_formulario', function (Blueprint $table) {
            $table->string('categoria')->nullable()->after('telefono'); // Añade la columna 'categoria'
            $table->decimal('imc', 8, 2)->nullable()->after('categoria'); // Añade la columna 'imc'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_imc_formulario', function (Blueprint $table) {
            $table->dropColumn('categoria'); // Elimina la columna 'categoria'
            $table->dropColumn('imc'); // Elimina la columna 'imc'
        });
    }
};
