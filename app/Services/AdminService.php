<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\Storage\StorageAdapterInterface;

class AdminService
{
    protected User $model;
    protected ?StorageAdapterInterface $storage;

    public function __construct(User $model, ?StorageAdapterInterface $storage = null)
    {
        $this->model = $model;
        $this->storage = $storage;
    }

    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        });

        if (!empty($filters['q'])) {
            $term = '%'.$filters['q'].'%';
            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'like', $term)->orWhere('last_name', 'like', $term)->orWhere('email', 'like', $term);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('last_name')->paginate($perPage);
    }

    public function find(int $id): User
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Crear admin: crea usuario, asigna rol admin y sube avatar si se provee.
     */
    public function create(array $data, ?UploadedFile $avatar = null): User
    {
        return DB::transaction(function () use ($data, $avatar) {
            $user = $this->model->create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => isset($data['password']) ? Hash::make($data['password']) : Hash::make(str()->random(12)),
                'status'     => $data['status'] ?? 'active',
            ]);

            // Asignar rol admin (usar Spatie)
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('admin');
            }

            if ($avatar instanceof UploadedFile && $this->storage) {
                $path = $this->storage->putFile("users/{$user->id}/avatar", $avatar);
                $user->avatar = $path;
                $user->save();
            }

            return $user;
        });
    }

    /**
     * Actualizar admin con manejo opcional de avatar y password.
     */
    public function update(int $id, array $data, ?UploadedFile $avatar = null): User
    {
        return DB::transaction(function () use ($id, $data, $avatar) {
            $user = $this->model->findOrFail($id);

            $fill = array_filter([
                'first_name' => $data['first_name'] ?? null,
                'last_name'  => $data['last_name'] ?? null,
                'email'      => $data['email'] ?? null,
                'status'     => $data['status'] ?? null,
            ]);

            $user->fill($fill);

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            if ($avatar instanceof UploadedFile && $this->storage) {
                $path = $this->storage->putFile("users/{$user->id}/avatar", $avatar);
                $user->avatar = $path;
                $user->save();
            }

            return $user->refresh();
        });
    }

    public function delete(int $id): bool
    {
        $user = $this->model->findOrFail($id);
        return (bool) $user->delete();
    }
}