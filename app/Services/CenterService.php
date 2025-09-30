<?php

namespace App\Services;

use App\Models\Center;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class CenterService
{
    protected string $disk = 'public';

    /**
     * Listado con filtros y paginación.
     */
    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = Center::query()->with('municipality');

        if (!empty($filters['q'])) {
            $query->where('name', 'like', '%'.$filters['q'].'%');
        }

        if (!empty($filters['municipality_id'])) {
            $query->where('municipality_id', $filters['municipality_id']);
        }

        if (isset($filters['is_public'])) {
            $query->where('is_public', (bool)$filters['is_public']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function find(int $id): ?Center
    {
        return Center::with('municipality')->find($id);
    }

    public function create(array $data, ?UploadedFile $logo = null): Center
    {
        if ($logo) {
            $data['logo'] = $logo->store('centers/logos', $this->disk);
        }

        return Center::create($data);
    }

    public function update(int $id, array $data, ?UploadedFile $logo = null): Center
    {
        $center = Center::findOrFail($id);

        if ($logo) {
            // borrar previo
            if ($center->logo && Storage::disk($this->disk)->exists($center->logo)) {
                Storage::disk($this->disk)->delete($center->logo);
            }
            $data['logo'] = $logo->store('centers/logos', $this->disk);
        }

        $center->update($data);

        return $center->refresh();
    }

    public function delete(int $id): void
    {
        $center = Center::findOrFail($id);

        if ($center->logo && Storage::disk($this->disk)->exists($center->logo)) {
            Storage::disk($this->disk)->delete($center->logo);
        }

        $center->delete();
    }
}