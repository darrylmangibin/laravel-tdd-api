<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('user.login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_user_email_not_exist() {
        $response = $this->postJson(route('user.login'), [
            'email' => 'darryl@gmail.com',
            'password' => 'secret123'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_password_incorrect()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('user.login'), [
            'email' => $user->email,
            'password' => 'random123'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
