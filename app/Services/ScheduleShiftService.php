<?php

namespace App\Services;

use App\Models\ScheduleShift;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ScheduleShiftService
{
    protected ScheduleShift $model;

    public function __construct(ScheduleShift $model)
    {
        $this->model = $model;
    }

    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['schedule_id'])) {
            $query->where('schedule_id', (int)$filters['schedule_id']);
        }

        if (isset($filters['weekday'])) {
            $query->where('weekday', (int)$filters['weekday']);
        }

        return $query->orderBy('weekday')->orderBy('start_time')->paginate($perPage);
    }

    public function find(int $id): ScheduleShift
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): ScheduleShift
    {
        return DB::transaction(function () use ($data) {
            return $this->model->create($data);
        });
    }

    public function update(int $id, array $data): ScheduleShift
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