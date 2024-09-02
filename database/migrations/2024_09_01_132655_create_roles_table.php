<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->enum('name', ['admin', 'boutiquier', 'client'])->unique();
            $table->timestamps();
        });

        // Insérer les rôles
        $roles = ['admin', 'boutiquier', 'client'];
        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
