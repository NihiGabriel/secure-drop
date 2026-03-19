<?php

namespace App\Services;

use App\Interfaces\SecretRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

class SecretService
{
    protected $repository;

    public function __construct(SecretRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    // Store secret with optional TTL
    public function storeSecret(string $secret, ?int $ttl = null)
    {
        $uuid = Str::uuid()->toString();

        $data = [
            'uuid' => $uuid,
            'secret' => Crypt::encryptString($secret),
            'expires_at' => $ttl ? Carbon::now()->addMinutes($ttl) : null,
        ];

        $this->repository->create($data);

        return $uuid;
    }

    // Retrieve secret and burn
    public function retrieveSecret(string $uuid)
    {
        $record = $this->repository->findByUuid($uuid);

        if (!$record) {
            return null;
        }

        // Check if expired
        if ($record->expires_at && Carbon::now()->gt($record->expires_at)) {
            $this->repository->deleteByUuid($uuid);
            return null;
        }

        $secret = Crypt::decryptString($record->secret);

        // Burn after read
        $this->repository->deleteByUuid($uuid);

        return $secret;
    }
}