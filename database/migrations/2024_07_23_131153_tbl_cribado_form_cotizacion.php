<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_cribado_form_cotizacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_de_la_empresa');
            $table->string('direccion');
            $table->integer('cantidad_de_colaboradores_en_total');
            $table->string('nombre_de_quien_solicita');
            $table->string('puesto_en_la_empresa');
            $table->string('telefono_directo_movil');
            $table->string('email');
            $table->date('date');
            $table->time('time');
            $table->string('page_url');
            $table->string('user_agent');
            $table->ipAddress('remote_ip');
            $table->string('powered_by');
            $table->string('form_id');
            $table->string('form_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_cribado_form_cotizacion');
    }
};
