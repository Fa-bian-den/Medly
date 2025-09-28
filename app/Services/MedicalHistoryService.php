<?php

namespace App\Services;

use App\Services\Contracts\EntityServiceInterface;
use App\Models\MedicalHistory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedicalHistoryService implements EntityServiceInterface
{
    protected MedicalHistory $model;

    public function __construct(MedicalHistory $model)
    {
        $this->model = $model;
    }

    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $q = $filters['q'] ?? null;
        $query = $this->model->newQuery();

        if ($q) {
            $query->where('notes', 'like', "%{$q}%");
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function find(int $id): MedicalHistory
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): MedicalHistory
    {
        // lógica de negocio mínima; idealmente validar antes con FormRequest
        return $this->model->create($data);
    }

    public function update(int $id, array $data): MedicalHistory
    {
        $item = $this->find($id);
        $item->fill($data);
        $item->save();
        return $item;
    }

    public function delete(int $id): bool
    {
        $item = $this->find($id);
        return (bool) $item->delete();
    }
}