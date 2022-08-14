<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_can_login_with_email_and_password()
    {
        $user = $this->createUser();
        $response = $this->postJson(route('auth.login'),[
            'email' => $user->email,
            'password' => 'password'
        ])->assertOk();

        $this->assertArrayHasKey('token',$response->json());
    }

    /** @test */
    public function if_a_user_email_not_available_return_error()
    {
        $this->postJson(route('auth.login'),[
            'email' => 'ahmed@test.com',
            'password' => 'secret'
        ])->assertUnauthorized();
    }

    /** @test */
    public function if_password_incorrect_it_raise_error()
    {
        $user = $this->createUser();

        $this->postJson(route('auth.login'),[
            'email' => $user->email,
            'password' => 'random'
        ])->assertUnauthorized();
    }
}
