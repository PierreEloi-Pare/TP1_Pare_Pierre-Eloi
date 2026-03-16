<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user(){
        $data = [
            'firstName' => 'aName',
            'lastName' => 'aName',
            'email' => 'email@test.com',
            'phone' => 'phone'
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'email@test.com'
        ]);
    }

    public function test_create_user_invalid(){
        $response = $this->postJson('/api/users', []);

        $response->assertStatus(422);
    }

    public function test_update_user(){
        $user = User::factory()->create();

        $data = [
            'firstName' => 'updatedName',
            'lastName' => 'updatedName',
            'email' => 'updated@test.com',
            'phone' => 'updatedPhone'
        ];

        $response = $this->putJson("/api/users/{$user->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'firstName' => 'updatedName',
            'lastName' => 'updatedName',
            'email' => 'updated@test.com',
            'phone' => 'updatedPhone'
        ]);
    }

    public function test_update_user_not_found(){
        $data = [
            'firstName' => 'updatedName',
            'lastName' => 'updatedName',
            'email' => 'updated@test.com',
            'phone' => 'updatedPhone'
        ];

        $response = $this->putJson('/api/users/9999', $data);

        $response->assertStatus(404);
    }
}
