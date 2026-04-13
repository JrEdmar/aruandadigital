<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'             => fake()->name(),
            'email'            => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'         => static::$password ??= Hash::make('password'),
            'role'             => 'visitante',
            'phone'            => '(11) 99999-9999',
            'cpf'              => '000.000.000-00',
            'birth_date'       => '1990-01-01',
            'lgpd_accepted_at' => now(),
            'points'           => 0,
            'level'            => 1,
            'remember_token'   => Str::random(10),
        ];
    }

    public function visitante(): static
    {
        return $this->state(['role' => 'visitante']);
    }

    public function membro(): static
    {
        return $this->state(['role' => 'membro']);
    }

    public function dirigente(): static
    {
        return $this->state(['role' => 'dirigente']);
    }

    public function assistente(): static
    {
        return $this->state(['role' => 'assistente']);
    }

    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }

    public function loja(): static
    {
        return $this->state(['role' => 'loja']);
    }

    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }
}
