<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SecretController extends Controller
{
    /**
     * Store a secret
     *
     * @bodyParam secret string required The secret text. Example: my-password
     * @bodyParam ttl integer Optional TTL in minutes. Example: 10
     */
    public function store(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
        ]);

        $uuid = (string) Str::uuid();

        // 1. Save file to storage/app/secrets/
        Storage::disk('local')->put("secrets/{$uuid}.txt", $request->input('secret'));

        return response()->json(['uuid' => $uuid], 201);
    }

    /**
     * Retrieve and burn a secret
     *
     * @urlParam uuid string required The UUID of the secret. Example: 123e4567-e89b-12d3-a456-426614174000
     */
    public function show($uuid)
    {
        $path = "secrets/{$uuid}.txt";

        // 2. Check if the file exists using the Storage facade
        if (! Storage::disk('local')->exists($path)) {
            return response()->json(['message' => 'Secret not found'], 404);
        }

        // 3. Get content and then DELETE (Burn)
        $secret = Storage::disk('local')->get($path);
        Storage::disk('local')->delete($path);

        return response()->json(['secret' => $secret]);
    }
}
