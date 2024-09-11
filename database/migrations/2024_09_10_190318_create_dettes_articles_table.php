<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDettesArticlesTable extends Migration
{
    public function up()
    {
        Schema::create('dette_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detteId');
            $table->unsignedBigInteger('articleId');
            $table->integer('qteVente');
            $table->integer('prixVente');
            $table->timestamps();

            $table->foreign('detteId')->references('id')->on('dettes');
            $table->foreign('articleId')->references('id')->on('articles');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dette_articles');
    }
}
