<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    protected Schedule $model;

    public function __construct(Schedule $model)
    {
        $this->model = $model;
    }

    /**
     * Listar schedules con filtros sencillos.
     */
    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['user_id'])) {
            $query->where('user_id', (int)$filters['user_id']);
        }

        if (!empty($filters['center_id'])) {
            $query->where('center_id', (int)$filters['center_id']);
        }

        if (!empty($filters['active'])) {
            $query->where('active', (bool)$filters['active']);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function find(int $id): Schedule
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Schedule
    {
        return DB::transaction(function () use ($data) {
            return $this->model->create($data);
        });
    }

    public function update(int $id, array $data): Schedule
    {
        return DB::transaction(function () use ($id, $data) {
            $item = $this->find($id);
            $item->fill($data);
            $item->save();
            return $item;
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $item = $this->find($id);
            return (bool) $item->delete();
        });
    }
}