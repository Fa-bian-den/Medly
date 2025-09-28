<?php

namespace App\Services;

use App\Models\ScheduleException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ScheduleExceptionService
{
    protected ScheduleException $model;

    public function __construct(ScheduleException $model)
    {
        $this->model = $model;
    }

    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['schedule_id'])) {
            $query->where('schedule_id', (int)$filters['schedule_id']);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('date', $filters['date']);
        }

        return $query->orderBy('date')->orderBy('start_time')->paginate($perPage);
    }

    public function find(int $id): ScheduleException
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): ScheduleException
    {
        return DB::transaction(function () use ($data) {
            return $this->model->create($data);
        });
    }

    public function update(int $id, array $data): ScheduleException
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