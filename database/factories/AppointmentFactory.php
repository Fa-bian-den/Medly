<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\User;
use App\Models\Center;
use App\Models\Service;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    /**
     * Estado por defecto.
     */
    public function definition(): array
    {
        // Paciente y médico generados por factories;
        $patient = User::factory();
        $doctor  = User::factory()->doctor();

        // Intentar reutilizar un slot existente; si no existe, dejar null (estado helper puede forzarlo)
        $slotId = AppointmentSlot::inRandomOrder()->value('id') ?? null;

        // Si no hay slot, generamos scheduled_at aleatorio en próximos 14 días
        $scheduled = Carbon::now()->addDays($this->faker->numberBetween(1, 14))
                        ->setTime($this->faker->numberBetween(8, 17), $this->faker->randomElement([0,15,30,45]), 0);

        return [
            'patient_id'         => $patient,
            'user_id'            => $doctor, // médico responsable
            'center_id'          => Center::inRandomOrder()->value('id') ?? Center::factory(),
            'service_id'         => Service::inRandomOrder()->value('id') ?? Service::factory(),
            'slot_id'            => $slotId, // nullable
            'scheduled_at'       => $scheduled,
            'status'             => 'pending',
            'reason'             => null,
            'created_by'         => null,
            'cancelled_by'       => null,
            'cancellation_reason'=> null,
        ];
    }

    /**
     * Forzar patient concreto (User instance o id)
     */
    public function forPatient($patient)
    {
        return $this->state(fn () => [
            'patient_id' => $patient instanceof User ? $patient->id : $patient,
        ]);
    }

    /**
     * Forzar doctor concreto (User instance o id)
     */
    public function forDoctor($doctor)
    {
        return $this->state(fn () => [
            'user_id' => $doctor instanceof User ? $doctor->id : $doctor,
        ]);
    }

    /**
     * Forzar center concreto (Center instance o id)
     */
    public function forCenter($center)
    {
        return $this->state(fn () => [
            'center_id' => $center instanceof Center ? $center->id : $center,
        ]);
    }

    /**
     * Forzar service concreto (Service instance o id)
     */
    public function forService($service)
    {
        return $this->state(fn () => [
            'service_id' => $service instanceof Service ? $service->id : $service,
        ]);
    }

    public function forSlot($slot)
    {
        return $this->state(function () use ($slot) {
            $resolved = null;

            if ($slot instanceof AppointmentSlot) {
                $resolved = $slot;
            } elseif (is_numeric($slot)) {
                $resolved = AppointmentSlot::find($slot);
            }

            if ($resolved) {
                // Usar los valores del slot para garantizar consistencia referencial
                return [
                    'slot_id'      => $resolved->id,
                    'user_id'      => $resolved->user_id,
                    'center_id'    => $resolved->center_id,
                    'service_id'   => $resolved->service_id,
                    'scheduled_at' => Carbon::createFromFormat('Y-m-d H:i:s', $resolved->date . ' ' . $resolved->start_time),
                ];
            }

            // Si no existe el slot, crear uno nuevo y asociarlo (factory nested)
            $newSlot = AppointmentSlot::factory()->create();

            return [
                'slot_id'      => $newSlot->id,
                'user_id'      => $newSlot->user_id,
                'center_id'    => $newSlot->center_id,
                'service_id'   => $newSlot->service_id,
                'scheduled_at' => Carbon::createFromFormat('Y-m-d H:i:s', $newSlot->date . ' ' . $newSlot->start_time),
            ];
        });
    }

    /**
     * States rápidos para status comunes
     */
    public function confirmed()
    {
        return $this->state(fn () => ['status' => 'confirmed']);
    }

    public function attended()
    {
        return $this->state(fn () => ['status' => 'attended']);
    }

    public function noShow()
    {
        return $this->state(fn () => ['status' => 'no_show']);
    }

    /**
     * Cancelar cita: establece status, cancelled_by y cancellation_reason.
     * $by puede ser User instance o id; $reason texto opcional.
     */
    public function cancelled($by = null, ?string $reason = null)
    {
        return $this->state(function () use ($by, $reason) {
            $cancelledBy = null;

            if ($by instanceof User) {
                $cancelledBy = $by->id;
            } elseif (is_numeric($by)) {
                $cancelledBy = $by;
            } else {
                // si no se especifica, por defecto null (podría ser sistema)
                $cancelledBy = null;
            }

            return [
                'status'              => 'cancelled',
                'cancelled_by'        => $cancelledBy,
                'cancellation_reason' => $reason ?? 'Cancelado en fixtures',
            ];
        });
    }

    /**
     * Reschedule helper: cambia scheduled_at y marca status rescheduled.
     */
    public function rescheduled(\DateTimeInterface $newDateTime)
    {
        return $this->state(fn () => [
            'status'       => 'rescheduled',
            'scheduled_at' => Carbon::instance($newDateTime),
        ]);
    }

    /**
     * Configure: validaciones leves después de crear para evitar inconsistencia básica.
     * No debe reemplazar la validación de dominio en AppointmentService.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Appointment $appointment) {
            // Si hay slot asociado, asegurar que scheduled_at venga del slot si aún no coincide
            if ($appointment->slot_id) {
                $slot = AppointmentSlot::find($appointment->slot_id);
                if ($slot) {
                    $appointment->user_id = $slot->user_id;
                    $appointment->center_id = $slot->center_id;
                    $appointment->service_id = $slot->service_id;
                    $appointment->scheduled_at = Carbon::createFromFormat('Y-m-d H:i:s', $slot->date . ' ' . $slot->start_time);
                }
            }
        })->afterCreating(function (Appointment $appointment) {
            // Si cancelled y no hay cancelled_by, establecer created_by como cancelled_by por fixtures
            if ($appointment->status === 'cancelled' && !$appointment->cancelled_by) {
                $appointment->cancelled_by = $appointment->created_by ?? $appointment->patient_id;
                $appointment->save();
            }
        });
    }
}