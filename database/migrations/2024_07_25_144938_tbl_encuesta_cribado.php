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
        Schema::create('tbl_encuesta_cribado', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->integer('edad');
            $table->string('telefono_personal');
            $table->string('correo');
            $table->string('empresa');
            $table->string('sede');
            $table->string('genero');
            $table->integer('peso_en_libras');
            $table->integer('altura_en_cms');
            $table->integer('cintura_en_cms');
            $table->string('agua_diaria');
            $table->string('horarios_refacciones_comidas');
            $table->string('porciones_pequenas');
            $table->string('separar_combinar_alimentos');
            $table->string('rutina_ejercicio');
            $table->string('vivir_saludable');
            $table->string('insulina_alta');
            $table->string('resistencia_insulina');
            $table->string('elevaciones_azucar_embarazo');
            $table->string('sindrome_ovarios_poliquistico');
            $table->string('sobrepeso');
            $table->string('diabetes_embarazo_hijo');
            $table->string('ejercicio_regular');
            $table->string('ovario_poliquistico');
            $table->string('padre');
            $table->string('madre');
            $table->string('hermanos');
            $table->string('tios_paternos');
            $table->string('tios_maternos');
            $table->string('abuelos_maternos');
            $table->string('abuelos_paternos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_encuesta_cribado');
    }
};
