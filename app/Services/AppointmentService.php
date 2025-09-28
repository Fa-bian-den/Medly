<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    protected Appointment $model;

    public function __construct(Appointment $model)
    {
        $this->model = $model;
    }

    /**
     * Listar citas con filtros básicos.
     */
    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['doctor_id'])) {
            $query->where('user_id', (int)$filters['doctor_id']);
        }

        if (!empty($filters['patient_id'])) {
            $query->where('patient_id', (int)$filters['patient_id']);
        }

        if (!empty($filters['center_id'])) {
            $query->where('center_id', (int)$filters['center_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['from'])) {
            $query->where('scheduled_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->where('scheduled_at', '<=', $filters['to']);
        }

        return $query->orderBy('scheduled_at')->paginate($perPage);
    }

    /**
     * Buscar por id.
     */
    public function find(int $id): Appointment
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Crear cita. Aquí insertar lógica de negocio (conflictos) si la necesitas.
     */
    public function create(array $data): Appointment
    {
        return DB::transaction(function () use ($data) {
            // puedes validar solapamientos aquí antes de crear
            return $this->model->create($data);
        });
    }

    /**
     * Actualizar cita.
     */
    public function update(int $id, array $data): Appointment
    {
        return DB::transaction(function () use ($id, $data) {
            $item = $this->find($id);
            $item->fill($data);
            $item->save();
            return $item;
        });
    }

    /**
     * Eliminar cita.
     */
    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $item = $this->find($id);
            return (bool) $item->delete();
        });
    }
}