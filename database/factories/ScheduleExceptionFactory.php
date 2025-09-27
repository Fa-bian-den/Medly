<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\ScheduleException;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Center;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScheduleException>
 */
class ScheduleExceptionFactory extends Factory
{
    protected $model = ScheduleException::class;

    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-1 month', '+3 months');
        $start = Carbon::instance($date)->setTime(
            $this->faker->numberBetween(6, 18),
            $this->faker->randomElement([0, 15, 30, 45]),
            0
        );
        $end = (clone $start)->addMinutes($this->faker->numberBetween(30, 480));

        return [
            'schedule_id' => Schedule::inRandomOrder()->value('id') ?? Schedule::factory(),
            'user_id'     => User::inRandomOrder()->value('id') ?? User::factory(),
            'center_id'   => Center::inRandomOrder()->value('id') ?? Center::factory(),
            'date'        => $start->toDateString(),
            'start_time'  => $start->format('H:i:s'),
            'end_time'    => $end->format('H:i:s'),
            'type'        => $this->faker->randomElement(['holiday','unavailable','override','maintenance']),
            'reason'      => $this->faker->optional()->sentence(),
        ];
    }

    // Helpers 
    public function forSchedule($schedule) { return $this->state(fn()=>['schedule_id' => $schedule instanceof Schedule ? $schedule->id : $schedule]); }
    public function forUser($user)         { return $this->state(fn()=>['user_id'     => $user instanceof User ? $user->id : $user]); }
    public function forCenter($center)     { return $this->state(fn()=>['center_id'   => $center instanceof Center ? $center->id : $center]); }

    public function holiday()              { return $this->state(fn()=>['type'=>'holiday','start_time'=>'00:00:00','end_time'=>'23:59:59']); }
    public function unavailable()          { return $this->state(fn()=>['type'=>'unavailable']); }

    public function onDate(string $date)   { return $this->state(fn()=>['date'=>$date]); }
    public function withTimes(string $start, string $end) { return $this->state(fn()=>['start_time'=>$start,'end_time'=>$end]); }
}