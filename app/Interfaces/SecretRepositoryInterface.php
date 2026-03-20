<?php

namespace App\Interfaces;

interface SecretRepositoryInterface
{
    public function create(array $data);

    public function findByUuid(string $uuid);

    public function deleteByUuid(string $uuid);
}
