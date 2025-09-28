<?php

namespace App\Services;

use App\Models\AppointmentDocument;
use App\Services\Storage\StorageAdapterInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppointmentDocumentService
{
    protected AppointmentDocument $model;
    protected StorageAdapterInterface $storage;
    protected string $uploadPathPrefix = 'appointments';

    public function __construct(AppointmentDocument $model, StorageAdapterInterface $storage)
    {
        $this->model = $model;
        $this->storage = $storage;
    }

    /**
     * Listar documentos por filtros simples.
     */
    public function list(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['appointment_id'])) {
            $query->where('appointment_id', (int)$filters['appointment_id']);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function find(int $id): AppointmentDocument
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Crear documento: guarda archivo usando StorageAdapterInterface y crea registro.
     * $file expected to be an instance of UploadedFile.
     */
    public function create(array $data, $file): AppointmentDocument
    {
        return DB::transaction(function () use ($data, $file) {
            $path = $this->storage->putFile(
                $this->uploadPathPrefix . '/' . ($data['appointment_id'] ?? 'unknown'),
                $file
            );

            $record = $this->model->create([
                'appointment_id' => $data['appointment_id'],
                'uploaded_by'    => $data['uploaded_by'] ?? null,
                'type'           => $data['type'] ?? null,
                'path'           => $path,
                'metadata'       => isset($data['metadata']) ? json_decode($data['metadata'], true) : null,
            ]);

            return $record;
        });
    }

    /**
     * Eliminar documento: borra fichero y registro.
     */
    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $doc = $this->find($id);
            // Intentar borrar el asset; ignorar si falla la eliminación física
            try {
                $this->storage->delete($this->extractStoragePath($doc->path));
            } catch (\Throwable $e) {
                // no hacemos throw para evitar romper transacción por issue con storage
            }
            return (bool) $doc->delete();
        });
    }

    /**
     * Extrae el path que entiende el adaptador a partir de la URL devuelta por putFile.
     * Ajusta según tu adaptador (si tu adapter devuelve URL pública, aquí conviene
     * almacenar además el path "raw" en la BD; si no, intentar derivar).
     */
    protected function extractStoragePath(string $urlOrPath): string
    {
        // Si tu adaptador devuelve URLs completas (http...), intenta devolver la parte relativa.
        // Por defecto retornamos la misma cadena (LocalStorageAdapter puede entender url=path).
        return $urlOrPath;
    }
}