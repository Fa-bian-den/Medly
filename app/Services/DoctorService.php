<?php

namespace App\Services;

use App\Models\User;
use App\Models\DoctorProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use App\Services\Storage\StorageAdapterInterface;

class DoctorService
{
    protected User $userModel;
    protected DoctorProfile $profileModel;
    protected ?StorageAdapterInterface $storage;

    public function __construct(User $userModel, DoctorProfile $profileModel, ?StorageAdapterInterface $storage = null)
    {
        $this->userModel = $userModel;
        $this->profileModel = $profileModel;
        $this->storage = $storage;
    }

    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = $this->userModel->newQuery()->where('role', 'doctor');

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function($qry) use ($q) {
                $qry->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->with('doctorProfile')->orderBy('last_name')->paginate($perPage);
    }

    public function find(int $id): User
    {
        return $this->userModel->with('doctorProfile')->findOrFail($id);
    }

    /**
     * Crear usuario doctor + doctor_profile en transacción
     */
    public function create(array $data, ?UploadedFile $avatar = null): User
    {
        return DB::transaction(function () use ($data, $avatar) {
            $user = $this->userModel->create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => $data['password'] ?? bcrypt(str()->random(12)),
                'status'     => $data['status'] ?? 'pending',
                'carnet_minsa' => $data['carnet_minsa'] ?? null,
                'role'       => $data['role'] ?? 'doctor',
            ]);

            $profileData = [
                'user_id' => $user->id,
                'center_id_proposed' => $data['center_id_proposed'] ?? null,
                'carnet_minsa' => $data['carnet_minsa'] ?? null,
                'ruc' => $data['ruc'] ?? null,
                'specialties' => isset($data['specialties']) ? json_encode($data['specialties']) : null,
                'status_validation' => $data['status_validation'] ?? 'pendiente',
                'reviewed_by' => $data['reviewed_by'] ?? null,
                'validated_at' => $data['validated_at'] ?? null,
                'comments' => $data['comments'] ?? null,
            ];
            $this->profileModel->create($profileData);

            if ($avatar instanceof UploadedFile && $this->storage) {
                $path = $this->storage->putFile("users/{$user->id}/avatar", $avatar);
                $user->avatar = $path;
                $user->save();
            }

            return $user->load('doctorProfile');
        });
    }

    /**
     * Actualizar user + doctor_profile
     */
    public function update(int $id, array $data, ?UploadedFile $avatar = null): User
    {
        return DB::transaction(function () use ($id, $data, $avatar) {
            /** @var User $user */
            $user = $this->userModel->findOrFail($id);

            $user->fill(array_filter([
                'first_name' => $data['first_name'] ?? null,
                'last_name'  => $data['last_name'] ?? null,
                'email'      => $data['email'] ?? null,
                'carnet_minsa'=> $data['carnet_minsa'] ?? null,
                'status'     => $data['status'] ?? null,
            ]));
            $user->save();

            $profile = $user->doctorProfile ?? $this->profileModel->newInstance(['user_id' => $user->id]);
            $profile->fill(array_filter([
                'center_id_proposed' => $data['center_id_proposed'] ?? null,
                'carnet_minsa' => $data['carnet_minsa'] ?? null,
                'ruc' => $data['ruc'] ?? null,
                'specialties' => isset($data['specialties']) ? json_encode($data['specialties']) : null,
                'status_validation' => $data['status_validation'] ?? null,
                'reviewed_by' => $data['reviewed_by'] ?? null,
                'validated_at' => $data['validated_at'] ?? null,
                'comments' => $data['comments'] ?? null,
            ]));
            $profile->save();

            if ($avatar instanceof UploadedFile && $this->storage) {
                $path = $this->storage->putFile("users/{$user->id}/avatar", $avatar);
                $user->avatar = $path;
                $user->save();
            }

            return $user->load('doctorProfile');
        });
    }

    public function delete(int $id): bool
    {
        $user = $this->userModel->findOrFail($id);
        return (bool) $user->delete();
    }
}