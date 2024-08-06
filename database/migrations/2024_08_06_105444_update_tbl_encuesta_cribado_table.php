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
        // Renombrar la tabla
        Schema::rename('tbl_encuesta_cribado', 'tbl_cribado_form_encuesta');

        // Añadir los nuevos campos
        Schema::table('tbl_cribado_form_encuesta', function (Blueprint $table) {
            // Campos añadidos después de cintura_en_cms
            $table->float('imc')->after('cintura_en_cms');
            $table->string('categoria')->after('imc');
            $table->string('habitos')->after('categoria');
            // Campos añadidos después de abuelos_paternos
            $table->float('antecedentes_porcentaje')->after('abuelos_paternos');
            $table->float('factores_porcentaje')->after('antecedentes_porcentaje');
            $table->float('hereditarios_porcentaje')->after('factores_porcentaje');
            $table->float('porcentaje_promedio_final')->after('hereditarios_porcentaje');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Quitar los campos añadidos
        Schema::table('tbl_cribado_form_encuesta', function (Blueprint $table) {
            $table->dropColumn(['imc', 'categoria', 'habitos', 'antecedentes_porcentaje', 'factores_porcentaje', 'hereditarios_porcentaje', 'porcentaje_promedio_final']);
        });

        // Renombrar la tabla de vuelta a su nombre original
        Schema::rename('tbl_cribado_form_encuesta', 'tbl_encuesta_cribado');
    }
};
