<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number', 20)->nullable();
            $table->string('photo', 191)->nullable();
            $table->string('address', 180)->nullable();
            $table->string('number', 100)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->string('birthday', 15)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColmn('phone_number');
            $table->dropColmn('photo');
            $table->dropColmn('address');
            $table->dropColmn('number');
            $table->dropColmn('neighborhood');
            $table->dropColmn('city');
            $table->dropColmn('state');
            $table->dropColmn('zip_code');
            $table->dropColmn('birthday');
        });
    }
}
