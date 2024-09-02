<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run()
    {
        // Créer 2 clients simples sans UserId
        Client::factory()->count(2)->create();

        // Créer 1 client avec un compte User
        Client::factory()->withUser()->create();
    }
}
