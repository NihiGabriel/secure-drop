<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SecretService;

class SecretController extends Controller
{
    protected $service;

    public function __construct(SecretService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
            'ttl' => 'nullable|integer|min:1',
        ]);

        $uuid = $this->service->storeSecret($request->secret, $request->ttl);

        return response()->json([
            'uuid' => $uuid,
            'url' => url("/api/v1/secrets/{$uuid}")
        ], 201);
    }

    public function show($uuid)
    {
        $secret = $this->service->retrieveSecret($uuid);

        if (!$secret) {
            return response()->json(['message' => 'Secret not found or expired'], 404);
        }

        return response()->json(['secret' => $secret]);
    }
}