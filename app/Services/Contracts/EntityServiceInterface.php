<?php

namespace App\Services\Contracts;

interface EntityServiceInterface
{
    public function list(array $filters = [], int $perPage = 25);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id): bool;
}