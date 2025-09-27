<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\CenterUser;
use App\Models\User;
use App\Models\Center;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CenterUser>
 */
class CenterUserFactory extends Factory
{
    protected $model = CenterUser::class;

    public function definition(): array
    {
        $from = $this->faker->dateTimeBetween('-30 days', '+10 days');
        $to = $this->faker->boolean(60)
            ? (clone $from)->modify('+'. $this->faker->numberBetween(30, 365) .' days')
            : null;

        return [
            'user_id'        => User::inRandomOrder()->value('id') ?? User::factory(),
            'center_id'      => Center::inRandomOrder()->value('id') ?? Center::factory(),
            'role_at_center' => $this->faker->randomElement(['doctor','admin','staff','nurse', null]),
            'active_from'    => $from->format('Y-m-d'),
            'active_to'      => $to ? $to->format('Y-m-d') : null,
            'is_primary'     => $this->faker->boolean(15),
            'notes'          => $this->faker->optional()->sentence(),
        ];
    }

    public function forUser($user)
    {
        return $this->state(fn () => [
            'user_id' => $user instanceof User ? $user->id : $user,
        ]);
    }

    public function forCenter($center)
    {
        return $this->state(fn () => [
            'center_id' => $center instanceof Center ? $center->id : $center,
        ]);
    }

    public function primary()
    {
        return $this->state(fn () => ['is_primary' => true]);
    }

    public function withDates(string $from, ?string $to = null)
    {
        return $this->state(fn () => ['active_from' => $from, 'active_to' => $to]);
    }

    public function active()
    {
        return $this->state(fn () => ['active_from' => now()->toDateString(), 'active_to' => null]);
    }
}