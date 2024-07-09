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
        Schema::create('tbl_imc_invitacion', function (Blueprint $table) {
            $table->id(); // Esto incluye el incremento automático
            $table->string('nombre');
            $table->string('apellido');
            $table->string('nombre_invitado');
            $table->string('apellido_invitado');
            $table->string('correo');
            $table->string('telefono');
            $table->date('fecha');
            $table->time('hora');
            $table->string('form_id');
            $table->timestamps(); // Esto añade los campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_imc_invitacion');
    }
};
