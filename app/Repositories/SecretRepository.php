<?php

namespace App\Repositories;

use App\Models\Secret;
use App\Interfaces\SecretRepositoryInterface;

class SecretRepository implements SecretRepositoryInterface
{
    public function create(array $data)
    {
        return Secret::create($data);
    }

    public function findByUuid(string $uuid)
    {
        return Secret::where('uuid', $uuid)->first();
    }

    public function deleteByUuid(string $uuid)
    {
        return Secret::where('uuid', $uuid)->delete();
    }
}