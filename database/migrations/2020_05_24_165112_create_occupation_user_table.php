<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccupationUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('occupation_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->comment('Id do usuário');
            $table->string('funcao', 191)->nullable()->comment('Cargo');
            $table->string('address', 191)->nullable()->comment('Rua');
			$table->string('district', 191)->nullable()->comment('Bairro');
			$table->string('state', 191)->nullable()->comment('Estado');
			$table->string('city', 191)->nullable()->comment('Cidade');
			$table->string('number', 191)->nullable()->comment('Número da residencia');
			$table->string('number_doc_license', 191)->nullable()->comment('Número documento (COREM ou CRM):');
			$table->string('zip_code', 191)->nullable()->comment('CEP');
			$table->string('complement', 191)->nullable()->comment('Complemento do endereço');
			$table->string('country', 191)->nullable()->default('brazil')->comment('País');
			$table->string('phone', 191)->comment('Telefone');
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
        Schema::dropIfExists('occupation_user');
    }
}
