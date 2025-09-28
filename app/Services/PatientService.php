<?php

namespace App\Services;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use App\Services\Storage\StorageAdapterInterface;

class PatientService
{
    protected User $userModel;
    protected Profile $profileModel;
    protected ?StorageAdapterInterface $storage;

    public function __construct(User $userModel, Profile $profileModel, ?StorageAdapterInterface $storage = null)
    {
        $this->userModel = $userModel;
        $this->profileModel = $profileModel;
        $this->storage = $storage;
    }

    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = $this->userModel->newQuery()->whereNull('deleted_at');

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

        return $query->orderBy('last_name')->paginate($perPage);
    }

    public function find(int $id): User
    {
        return $this->userModel->with('profile')->findOrFail($id);
    }

    /**
     * Crear paciente (opcional). Crea user + profile en transacción.
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
            ]);

            $profileData = array_intersect_key($data, array_flip([
                'birthdate','address','idcard','phone','gender','documents_metadata','professional_details'
            ]));
            $profileData['user_id'] = $user->id;
            $this->profileModel->create($profileData);

            if ($avatar instanceof UploadedFile && $this->storage) {
                $path = $this->storage->putFile("users/{$user->id}/avatar", $avatar);
                $user->avatar = $path;
                $user->save();
            }

            return $user->load('profile');
        });
    }

    /**
     * Actualizar user + profile atómicamente.
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
            ]));
            $user->save();

            $profile = $user->profile ?? $this->profileModel->newInstance(['user_id' => $user->id]);
            $profile->fill(array_filter([
                'birthdate' => $data['birthdate'] ?? null,
                'address'   => $data['address'] ?? null,
                'idcard'    => $data['idcard'] ?? null,
                'phone'     => $data['phone'] ?? null,
                'gender'    => $data['gender'] ?? null,
                'documents_metadata' => $data['documents_metadata'] ?? null,
                'professional_details' => $data['professional_details'] ?? null,
            ]));
            $profile->save();

            if ($avatar instanceof UploadedFile && $this->storage) {
                $path = $this->storage->putFile("users/{$user->id}/avatar", $avatar);
                $user->avatar = $path;
                $user->save();
            }

            return $user->load('profile');
        });
    }

    public function deactivate(int $id, ?int $byUserId = null): User
    {
        /** @var User $user */
        $user = $this->userModel->findOrFail($id);
        $user->status = 'disabled';
        $user->save();
        return $user;
    }

    public function delete(int $id): bool
    {
        /** @var User $user */
        $user = $this->userModel->findOrFail($id);
        return (bool) $user->delete();
    }
}