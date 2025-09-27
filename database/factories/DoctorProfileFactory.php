<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\DoctorProfile;
use App\Models\User;
use App\Models\Center;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoctorProfile>
 */
class DoctorProfileFactory extends Factory
{
    protected $model = DoctorProfile::class;

    public function definition(): array
    {
        return [
            // Por defecto vinculamos a un usuario doctor nuevo para respetar unique(user_id)
            'user_id'             => User::factory()->doctor(),
            'center_id_proposed'  => null,
            'carnet_minsa'        => $this->faker->optional(0.6)->bothify('MINSA-#####'),
            'documents'           => [
                'carnet' => [
                    'path' => null,
                    'uploaded_at' => null,
                ],
            ],
            'ruc'                 => $this->faker->optional(0.5)->bothify('##########'),
            'specialties'         => $this->faker->optional(0.9)->randomElements(
                                        ['pediatria','medicina_general','odontologia','ginecologia','psicologia'], 
                                        $this->faker->numberBetween(1, 3)
                                    ),
            'status_validation'   => 'pendiente', // pendiente | en_revision | aprobado | rechazado
            'reviewed_by'         => null,
            'validated_at'        => null,
            'comments'            => null,
        ];
    }

    /**
     * Vincular a un usuario existente (User instance o id).
     * Uso: DoctorProfile::factory()->forUser($user)->create();
     */
    public function forUser($user)
    {
        return $this->state(fn () => [
            'user_id' => $user instanceof User ? $user->id : $user,
        ]);
    }

    /**
     * Proponer un centro concreto (Center instance o id).
     */
    public function proposeCenter($center)
    {
        return $this->state(fn () => [
            'center_id_proposed' => $center instanceof Center ? $center->id : $center,
        ]);
    }

    /**
     * Adjuntar documentos con metadatos (array).
     * Ejemplo: ->withDocuments(['carnet' => ['path'=>'/x.pdf','uploaded_at'=>now()]])
     */
    public function withDocuments(array $docs)
    {
        return $this->state(fn () => [
            'documents' => $docs,
        ]);
    }

    /**
     * Forzar lista de especialidades.
     * Ejemplo: ->withSpecialties(['pediatria','odontologia'])
     */
    public function withSpecialties(array $specialties)
    {
        return $this->state(fn () => [
            'specialties' => $specialties,
        ]);
    }

    /**
     * Estados del flujo de validación
     */
    public function pending()
    {
        return $this->state(fn () => ['status_validation' => 'pendiente']);
    }

    public function inReview()
    {
        return $this->state(fn () => ['status_validation' => 'en_revision']);
    }

    public function approved()
    {
        return $this->state(function () {
            return [
                'status_validation' => 'aprobado',
                'validated_at'      => Carbon::now(),
            ];
        });
    }

    public function rejected(?string $reason = null)
    {
        return $this->state(function () use ($reason) {
            return [
                'status_validation' => 'rechazado',
                'validated_at'      => Carbon::now(),
                'comments'          => $reason ?? 'Rechazado en fixtures',
            ];
        });
    }

    /**
     * Asignar reviewer (User instance o id).
     */
    public function reviewedBy($user)
    {
        return $this->state(fn () => [
            'reviewed_by' => $user instanceof User ? $user->id : $user,
        ]);
    }

    /**
     * Seguridad adicional: afterCreating asegura que validated_at se setea cuando el estado lo requiere.
     */
    public function configure()
    {
        return $this->afterCreating(function (DoctorProfile $profile) {
            if (in_array($profile->status_validation, ['aprobado', 'rechazado']) && ! $profile->validated_at) {
                $profile->validated_at = Carbon::now();
                $profile->save();
            }
        });
    }
}