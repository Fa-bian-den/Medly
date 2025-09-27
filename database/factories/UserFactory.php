<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Modelo asociado a esta fábrica.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Contraseña hasheada compartida para acelerar la generación.
     *
     * @var string|null
     */
    protected static ?string $password = null;

    /**
     * Estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Reutiliza la misma contraseña hasheada para todas las instancias
        static::$password ??= Hash::make('secret');

        return [
            'first_name'        => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password,
            'status'            => 'active', // active | pending | disabled
            'carnet_minsa'      => null,    // rellenado por state doctor()
            'provider'          => null,
            'provider_id'       => null,
            'avatar'            => $this->faker->imageUrl(200, 200, 'people', true),
            'remember_token'    => Str::random(10),
        ];
    }

    /**
     * Marca el email como no verificado.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Genera un usuario con datos de doctor.
     *
     * - Rellena carnet_minsa si no se pasa.
     * - Deja status en pending por defecto.
     * - Crea un profile mínimo asociado.
     * - Intenta asignar el role 'doctor' si Spatie está instalado y el role existe.
     */
    public function doctor(?string $carnet = null): static
    {
        return $this->state(fn (array $attributes) => [
            'carnet_minsa' => $carnet ?? 'MINSA-' . $this->faker->bothify('#####'),
            'status'       => 'pending',
        ])->afterCreating(function (User $user) {
            // Crear profile mínimo si no existe
            if (! $user->profile) {
                $user->profile()->create([
                    'birthdate' => $this->faker->date(),
                    'phone'     => $this->faker->phoneNumber(),
                ]);
            }

            // Intentar asignar rol 'doctor' si Spatie está disponible
            try {
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('doctor');
                }
            } catch (\Throwable $e) {
                // Silenciar: entorno de testing/dev puede no tener roles cargados
            }
        });
    }

    /**
     * Genera un usuario creado vía OAuth (p. ej. Google).
     */
    public function oauth(string $provider = 'google'): static
    {
        return $this->state(fn (array $attributes) => [
            'provider'    => $provider,
            'provider_id' => (string) $this->faker->unique()->numberBetween(1000000, 9999999),
            'password'    => null,
        ])->afterCreating(function (User $user) {
            // Crear profile mínimo si no existe (útil para tests)
            if (! $user->profile) {
                $user->profile()->create([
                    'birthdate' => $this->faker->date(),
                    'phone'     => $this->faker->phoneNumber(),
                ]);
            }
        });
    }

    /**
     * Configuración general: crear profile por defecto y asignar role 'patient' si es posible.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            // Crear profile básico si no existe
            try {
                if (! $user->profile) {
                    $user->profile()->create([
                        'birthdate' => $this->faker->date(),
                        'phone'     => $this->faker->phoneNumber(),
                    ]);
                }
            } catch (\Throwable $e) {
                // Silenciar fallos por dependencia de modelos/migraciones en entornos parciales
            }

            // Intentar asignar rol 'patient' para facilitar tests que dependen de roles
            try {
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('patient');
                }
            } catch (\Throwable $e) {
                // Silenciar si roles no están inicializados
            }
        });
    }
}
