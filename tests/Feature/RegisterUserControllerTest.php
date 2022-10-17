<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_a_user_can_be_registered()
    {
        $payload = [
            'name' => 'Some User',
            'email' => 'user@email.com',
            'password' => 'very-strong-password'
        ];

        $response = $this->post('/api/v1/register', $payload, $this->headers);

        $response->assertCreated();
        $response->assertJsonStructure(['message']);
        $response->assertJson(['message' => 'User created successfully']);
    }

    public function test_if_all_fields_are_required_when_registering_a_user()
    {
        $response = $this->post('/api/v1/register', [], $this->headers);

        $response->assertUnprocessable();
        $response->assertInvalid([
            'name' => 'The name field is required',
            'email' => 'The email field is required',
            'password' => 'The password field is required',
        ]);
    }
}
