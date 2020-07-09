<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExchangeIdToMedicalInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_informations', function (Blueprint $table) {
            $table->integer('exchange_id')->unsigned()->nullable();
            $table->foreign('exchange_id')->references('id')->on('exchanges');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_informations', function (Blueprint $table) {
            //
        });
    }
}
