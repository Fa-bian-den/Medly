<?php

namespace App\Services\Storage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

/**
 * Implementación local que usa el filesystem disk configurado.
 * Por defecto usa el disk 'public', configurable vía constructor o argumento.
 */
class LocalStorageAdapter implements StorageAdapterInterface
{
    protected string $disk;

    /**
     * @param string $disk Nombre del disk en config/filesystems.php (default: public)
     */
    public function __construct(string $disk = 'public')
    {
        $this->disk = $disk;
    }

    /**
     * @param string $path
     * @param mixed $content
     * @param array $options
     * @return string URL pública del archivo
     */
    public function put(string $path, $content, array $options = []): string
    {
        $disk = $this->diskInstance();
        $disk->put($path, $content, $options);
        return $disk->url($path);
    }

    /**
     * @param string $path
     * @param mixed $file
     * @param array $options
     * @return string URL pública del archivo almacenado
     */
    public function putFile(string $path, $file, array $options = []): string
    {
        $disk = $this->diskInstance();
        $stored = $disk->putFile($path, $file, $options);
        return $disk->url($stored);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        return $this->diskInstance()->delete($path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function url(string $path): string
    {
        return $this->diskInstance()->url($path);
    }

    /**
     * Obtener instancia tipada del disk para evitar avisos del IDE.
     *
     * @return FilesystemAdapter
     */
    protected function diskInstance(): FilesystemAdapter
    {
        // Anotación clara para el analizador: Storage::disk() devuelve FilesystemAdapter
        /** @var FilesystemAdapter $adapter */
        $adapter = Storage::disk($this->disk);
        return $adapter;
    }
}