<?php

namespace App\Services;

use App\Models\AppointmentSlot;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppointmentSlotService
{
    protected AppointmentSlot $model;

    public function __construct(AppointmentSlot $model)
    {
        $this->model = $model;
    }

    /**
     * Listar slots con filtros sencillos.
     */
    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['center_id'])) {
            $query->where('center_id', (int)$filters['center_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', (int)$filters['user_id']);
        }

        if (!empty($filters['service_id'])) {
            $query->where('service_id', (int)$filters['service_id']);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('date', $filters['date']);
        }

        return $query->orderBy('date')->orderBy('start_time')->paginate($perPage);
    }

    public function find(int $id): AppointmentSlot
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Crear slot; valida unicidad por keys únicas definidas en migration.
     */
    public function create(array $data): AppointmentSlot
    {
        return DB::transaction(function () use ($data) {
            return $this->model->create($data);
        });
    }

    /**
     * Actualizar slot.
     */
    public function update(int $id, array $data): AppointmentSlot
    {
        return DB::transaction(function () use ($id, $data) {
            $slot = $this->find($id);
            $slot->fill($data);
            $slot->save();
            return $slot;
        });
    }

    /**
     * Eliminar slot.
     */
    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $slot = $this->find($id);
            return (bool) $slot->delete();
        });
    }

    /**
     * Contar plazas ocupadas para un slot (usado para comprobar capacidad).
     */
    public function bookedCount(int $slotId): int
    {
        $slot = $this->find($slotId);
        return $slot->appointments()->count();
    }

    /**
     * Indica si hay espacio disponible en el slot.
     */
    public function hasCapacity(int $slotId): bool
    {
        $slot = $this->find($slotId);
        return $this->bookedCount($slotId) < $slot->capacity;
    }
}