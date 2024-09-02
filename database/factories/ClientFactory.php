<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'surnom' => $this->faker->unique()->userName,
            'telephone' => $this->faker->numerify('7########'), // Génère un numéro commençant par 7 suivi de 8 chiffres
            'adresse' => $this->faker->address,
            'user_id' => null, // Par défaut, pas de User associé
        ];
    }

    /**
     * Indique que le client a un compte utilisateur.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withUser()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => User::factory(),
            ];
        });
    }
}
