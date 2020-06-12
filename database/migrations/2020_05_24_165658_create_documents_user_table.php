<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->comment('Id do usuário');
            $table->string('url', 191)->nullable()->comment('URL para a foto do documento');
            $table->string('type', 191)->comment('Tipo do documento - ex: rg, cpf, certificado, diploma, certidão, outro');
            $table->tinyInteger('status')->nullable()->comment('Status de Autorização. 0 = Pendente, 1 = Autorizado, 2 = Rejeitado');
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
        Schema::dropIfExists('documents_user');
    }
}
