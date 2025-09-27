<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Profile;
use App\Models\User;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'user_id'          => User::inRandomOrder()->value('id') ?? User::factory(),
            'birthdate'        => $this->faker->date(),
            'address'          => $this->faker->address(),
            'idcard'           => $this->faker->bothify('##########'),
            'phone'            => preg_replace('/\D+/', '', $this->faker->phoneNumber()),
            'gender'           => $this->faker->randomElement(['male','female','other']),
            'documents_metadata'=> [],
            'professional_details'=> [],
            'clinical_history' => [
                ['date' => $this->faker->date(), 'note' => $this->faker->sentence()],
            ],
            'allergies'        => $this->faker->optional()->randomElements(['penicillin','latex','nuts']),
            'medications'      => $this->faker->optional()->randomElements(['aspirin','insulin']),
            'emergency_contact'=> [
                'name' => $this->faker->name(),
                'phone'=> preg_replace('/\D+/', '', $this->faker->phoneNumber()),
                'relation' => $this->faker->randomElement(['parent','partner','friend']),
            ],
            'clinical_notes'   => $this->faker->optional()->sentence(),
        ];
    }

    public function forUser($user)
    {
        return $this->state(fn () => ['user_id' => $user instanceof User ? $user->id : $user]);
    }

    public function patient()
    {
        return $this->state(fn () => [
            'clinical_history' => [
                ['date' => now()->subMonths(3)->toDateString(), 'note' => 'Historial clínico inicial de prueba']
            ]
        ]);
    }
}