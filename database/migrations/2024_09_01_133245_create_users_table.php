<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
Schema::create('users', function (Blueprint $table) {
$table->id();
$table->string('nom');
$table->string('prenom');
$table->string('telephone')->unique();
$table->unsignedBigInteger('role_id');
$table->string('login')->unique();
$table->string('password');
$table->boolean('active')->default(true);
$table->string('photo')->nullable();
$table->timestamps();

$table->foreign('role_id')->references('id')->on('roles');
});
}

public function down()
{
Schema::dropIfExists('users');
}
};
