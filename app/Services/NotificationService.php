<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class NotificationService
{
    protected Notification $model;

    public function __construct(Notification $model)
    {
        $this->model = $model;
    }

    /**
     * Listado paginado por usuario (si se pasa user_id) o global (admin).
     */
    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['q'])) {
            $term = '%'.$filters['q'].'%';
            $query->where(function($q) use ($term) {
                $q->where('title', 'like', $term)->orWhere('body', 'like', $term);
            });
        }

        if (isset($filters['unread']) && $filters['unread']) {
            $query->whereNull('read_at');
        }

        return $query->orderBy('sent_at', 'desc')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function find(int $id): Notification
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Crear notificación.
     */
    public function create(array $data): Notification
    {
        $data['data'] = isset($data['data']) ? $data['data'] : null;
        return $this->model->create($data);
    }

    /**
     * Actualizar (incluye marcar como leída).
     */
    public function update(int $id, array $data): Notification
    {
        return DB::transaction(function () use ($id, $data) {
            $notification = $this->model->findOrFail($id);

            if (array_key_exists('read_at', $data)) {
                $notification->read_at = $data['read_at'] ? Carbon::parse($data['read_at']) : null;
            }

            if (array_key_exists('title', $data)) {
                $notification->title = $data['title'];
            }

            if (array_key_exists('body', $data)) {
                $notification->body = $data['body'];
            }

            if (array_key_exists('data', $data)) {
                $notification->data = $data['data'];
            }

            if (array_key_exists('sent_at', $data)) {
                $notification->sent_at = $data['sent_at'] ? Carbon::parse($data['sent_at']) : null;
            }

            $notification->save();

            return $notification->refresh();
        });
    }

    public function delete(int $id): bool
    {
        $notification = $this->model->findOrFail($id);
        return (bool) $notification->delete();
    }

    /**
     * Marcar todas las notificaciones como leídas para un usuario.
     */
    public function markAllReadForUser(int $userId): int
    {
        return $this->model->where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}