<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeQrcodeToTextInClientsTable extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->text('qrcode')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('qrcode')->nullable()->change();
        });
    }
}
