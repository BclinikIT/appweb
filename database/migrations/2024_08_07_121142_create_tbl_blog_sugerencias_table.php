<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblBlogSugerenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_blog_sugerencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255)->nullable(false);
            $table->string('telefono', 20)->nullable(false);
            $table->string('correo', 255)->nullable(false);
            $table->text('mensaje')->nullable(false);
            $table->string('archivo')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->nullable();
            $table->string('page_url', 255)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->string('remote_ip', 45)->nullable();
            $table->string('powered_by', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_blog_sugerencias');
    }
}
