<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLojasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lojas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->string('telefone')->nullable();
            $table->string('celular')->nullable();
            $table->string('descricao')->nullable();
            $table->string('rua')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('cep')->nullable();
            $table->string('complemento')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('inscEstadual')->nullable();
            $table->string('inscMunicipal')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('imgFundo')->nullable();
            $table->string('corTitulo')->nullable();
            $table->string('corFundo')->nullable();
            $table->string('corFonte')->nullable();
            $table->string('pixelFacebook')->nullable();
            $table->string('pixelGoogle')->nullable();
            $table->string('status')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('lojas');
    }
}
