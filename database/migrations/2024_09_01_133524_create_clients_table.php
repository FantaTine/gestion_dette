<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
public function up()
{
Schema::create('clients', function (Blueprint $table) {
$table->id();
$table->string('surnom')->unique();
$table->string('telephone')->unique();
$table->string('adresse')->nullable();
$table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('clients');
}
}
