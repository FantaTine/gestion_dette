<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $prefixes = ['70', '76', '77', '78'];
        return [
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'telephone' => $prefixes[array_rand($prefixes)] . $this->faker->numerify('#######'),
            'role' => $this->faker->randomElement(['admin', 'boutiquier']),
            'login' => $this->faker->unique()->userName,
            'password' => bcrypt('Password1!'), // password
            'active' => $this->faker->boolean,
        ];
    }
}
