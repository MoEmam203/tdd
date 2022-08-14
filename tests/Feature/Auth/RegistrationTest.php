<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_register()
    {
        $this->postJson(route('auth.register'),[
            'name' => 'Mustafa Emam',
            'email' => 'mustafa@test.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ])->assertCreated();

        $this->assertDatabaseHas('users',['email' => 'mustafa@test.com']);
    }
}
