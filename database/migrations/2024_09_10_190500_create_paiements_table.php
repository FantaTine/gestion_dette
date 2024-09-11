<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaiementsTable extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detteId');
            $table->integer('montant');
            $table->timestamps();

            $table->foreign('detteId')->references('id')->on('dettes');
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
}
