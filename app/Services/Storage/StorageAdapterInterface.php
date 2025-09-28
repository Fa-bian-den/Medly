<?php

namespace App\Services\Storage;

/**
 * Contrato mínimo para adaptadores de almacenamiento.
 * Implementaciones deben devolver una URL o ruta relativa según su política.
 */
interface StorageAdapterInterface
{
    /**
     * Guardar contenido raw en una ruta.
     *
     * @param string $path  Ruta o prefijo donde guardar.
     * @param mixed  $content Contenido (string, stream).
     * @param array  $options Opciones adicionales (visibilidad, metadata).
     * @return string URL o path retornado por el adaptador.
     */
    public function put(string $path, $content, array $options = []): string;

    /**
     * Guardar un archivo subido (UploadedFile) en una ruta.
     *
     * @param string $path Carpeta destino (ej: 'avatars').
     * @param mixed  $file UploadedFile o similar.
     * @param array  $options Opciones (disk, visibility).
     * @return string URL o path retornado por el adaptador.
     */
    public function putFile(string $path, $file, array $options = []): string;

    /**
     * Eliminar un archivo por su path.
     *
     * @param string $path Path guardado (según adaptador).
     * @return bool true si eliminado.
     */
    public function delete(string $path): bool;

    /**
     * Obtener URL pública para un path conocido por el adaptador.
     *
     * @param string $path
     * @return string
     */
    public function url(string $path): string;
}