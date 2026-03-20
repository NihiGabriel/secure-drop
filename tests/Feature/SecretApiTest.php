<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecretApiTest extends TestCase
{
    use RefreshDatabase; // This resets the DB for every test

    public function test_can_store_and_retrieve_secret_once()
    {
        // 1. Test POST (Create)
        $response = $this->postJson('/api/v1/secrets', [
            'secret' => 'top-secret-message',
            'ttl' => 60
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['uuid']);

        $uuid = $response->json('uuid');

        // 2. Test GET (First time - Success)
        $getResponse = $this->getJson("/api/v1/secrets/{$uuid}");
        $getResponse->assertStatus(200)
                    ->assertJson(['secret' => 'top-secret-message']);

        // 3. Test GET (Second time - Burned/404)
        $getSecondResponse = $this->getJson("/api/v1/secrets/{$uuid}");
        $getSecondResponse->assertStatus(404);
    }
}
