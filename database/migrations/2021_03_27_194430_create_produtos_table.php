<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('categoria_produto_id')->unsigned();
            $table->bigInteger('loja_id')->unsigned()->nullable();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('peso')->nullable();
            $table->string('preco_venda')->nullable();
            $table->string('preco_promocional')->nullable();
            $table->string('comprimento')->nullable();
            $table->string('largura')->nullable();
            $table->string('altura')->nullable();
            $table->string('estoque_minimo')->nullable();
            $table->string('estoque_maximo')->nullable();
            $table->string('tipo_produto')->nullable();
            $table->string('status_estoque')->nullable();
            $table->string('sku')->nullable();
            $table->string('gerenciar_estoque')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('loja_id')->references('id')->on('lojas');
            $table->foreign('categoria_produto_id')->references('id')->on('categoria_produtos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
