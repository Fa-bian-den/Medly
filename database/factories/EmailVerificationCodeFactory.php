<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\EmailVerificationCode;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmailVerificationCode>
 */
class EmailVerificationCodeFactory extends Factory
{
    protected $model = EmailVerificationCode::class;

    public function definition(): array
    {
        return [
            'user_id'   => User::inRandomOrder()->value('id') ?? User::factory(),
            'code'      => (string) $this->faker->numerify('######'),
            'expires_at'=> Carbon::now()->addMinutes($this->faker->numberBetween(10, 60)),
            'used'      => false,
            'attempts'  => 0,
        ];
    }

    // Vincular a un usuario existente
    public function forUser($user)
    {
        return $this->state(fn () => [
            'user_id' => $user instanceof User ? $user->id : $user,
        ]);
    }

    // Código específico
    public function withCode(string $code)
    {
        return $this->state(fn () => ['code' => $code]);
    }

    // Generar como usado
    public function used()
    {
        return $this->state(fn () => ['used' => true, 'expires_at' => Carbon::now()->subMinute()]);
    }

    // Generar expirado
    public function expired()
    {
        return $this->state(fn () => ['expires_at' => Carbon::now()->subMinutes($this->faker->numberBetween(1, 60))]);
    }

    // Ajustar intentos
    public function withAttempts(int $n)
    {
        return $this->state(fn () => ['attempts' => max(0, $n)]);
    }
}