<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDettesTable extends Migration
{
    public function up()
    {
        Schema::create('dettes', function (Blueprint $table) {
            $table->id();
            $table->integer('montantDu');
            $table->unsignedBigInteger('clientId');
            $table->enum('statut', ['soldée', 'nonsoldée'])->default('nonsoldée');
            $table->timestamps();

            $table->foreign('clientId')->references('id')->on('clients');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dettes');
    }
}
