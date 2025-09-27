<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Notification;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        $sent = $this->faker->boolean(80) ? Carbon::now()->subMinutes($this->faker->numberBetween(0, 1440)) : null;
        $read = $sent ? ($this->faker->boolean(60) ? Carbon::instance($sent)->addMinutes($this->faker->numberBetween(1, 300)) : null) : null;

        return [
            'user_id'  => User::inRandomOrder()->value('id') ?? User::factory(),
            'type'     => $this->faker->randomElement([
                'appointment.reminder',
                'appointment.updated',
                'appointment.cancelled',
                'system.alert',
            ]),
            'title'    => $this->faker->sentence(6, true),
            'body'     => $this->faker->paragraph(2, true),
            'data'     => [
                'reference_id' => $this->faker->optional()->numberBetween(1, 2000),
                'meta' => ['source' => $this->faker->word()],
            ],
            'read_at'  => $read,
            'sent_at'  => $sent,
        ];
    }

    // Helpers cortos
    public function forUser($user)
    {
        return $this->state(fn () => ['user_id' => $user instanceof User ? $user->id : $user]);
    }

    public function unread()
    {
        return $this->state(fn () => ['read_at' => null]);
    }

    public function read()
    {
        return $this->state(fn () => ['read_at' => Carbon::now()]);
    }

    public function withType(string $type)
    {
        return $this->state(fn () => ['type' => $type]);
    }

    public function withData(array $data)
    {
        return $this->state(fn () => ['data' => $data]);
    }

    public function sentAt(\DateTimeInterface $dt)
    {
        return $this->state(fn () => ['sent_at' => Carbon::instance($dt)]);
    }
}