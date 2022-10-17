<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TokenControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_a_registered_user_can_issue_an_api_token()
    {
        $password = 'secret';
        $user = User::factory()->create(['email' => 'user@email.com', 'password' => Hash::make($password)]);
        $payload = [
            'email' => $user->email,
            'password' => $password,
            'device_name' => 'my device'
        ];

        $response = $this->post('/api/v1/tokens', $payload, $this->headers);

        $response->assertOk();
        $response->assertJsonStructure(['access_token']);
        $response->assertHeader('content-type', 'application/json');
    }

    public function test_if_a_invalid_user_receives_a_forbidden_message()
    {
        $user = User::factory()->create(['email' => 'user@email.com', 'password' => Hash::make('secret')]);
        $payload = [
            'email' => $user->email,
            'password' => 'wrong-password',
            'device_name' => 'my device'
        ];

        $response = $this->post('/api/v1/tokens', $payload, $this->headers);

        $response->assertForbidden();
        $response->assertHeader('content-type', 'application/json');
    }

    public function test_if_all_fields_are_required_when_an_user_issues_a_token()
    {
        $response = $this->post('/api/v1/tokens', [], $this->headers);

        $response->assertUnprocessable();
        $response->assertInvalid([
            'email' => 'The email field is required',
            'password' => 'The password field is required',
            'device_name' => 'The device name field is required',
        ]);
        $response->assertHeader('content-type', 'application/json');
    }
}
