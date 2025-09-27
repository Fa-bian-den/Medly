<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AppointmentDocument;
use App\Models\Appointment;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppointmentDocument>
 */
class AppointmentDocumentFactory extends Factory
{
    protected $model = AppointmentDocument::class;

    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::inRandomOrder()->value('id') ?? Appointment::factory(),
            'uploaded_by'    => User::inRandomOrder()->value('id') ?? User::factory(),
            'type'           => $this->faker->randomElement(['report','image','prescription','other']),
            'path'           => $this->faker->filePath(), // ruta relativa realista
            'metadata'       => [
                'filename' => $this->faker->word() . '.' . $this->faker->fileExtension(),
                'mimetype' => $this->faker->mimeType(),
                'size'     => $this->faker->numberBetween(1024, 5_000_000),
            ],
        ];
    }

    public function forAppointment($appointment)
    {
        return $this->state(fn () => [
            'appointment_id' => $appointment instanceof Appointment ? $appointment->id : $appointment,
        ]);
    }

    public function forUploader($user)
    {
        return $this->state(fn () => [
            'uploaded_by' => $user instanceof User ? $user->id : $user,
        ]);
    }

    public function withType(string $type)
    {
        return $this->state(fn () => ['type' => $type]);
    }

    public function withPath(string $path)
    {
        return $this->state(fn () => ['path' => $path]);
    }

    public function withMetadata(array $meta)
    {
        return $this->state(fn () => ['metadata' => $meta]);
    }
}