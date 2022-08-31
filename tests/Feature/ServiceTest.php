<?php

namespace Tests\Feature;

use Google\Client;
use Tests\TestCase;
use Mockery\MockInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    /** @test */
    public function a_user_can_connect_to_a_service()
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setScopes');
            $mock->shouldReceive('createAuthUrl')->andReturn('http://localhost');
        });

        $response = $this->getJson(route('web-service.connect',['google-drive']))->assertOk()->json();

        $this->assertNotNull($response['url']);
        $this->assertEquals('http://localhost',$response['url']);
    }

    /** @test */
    public function service_callback_can_store_token()
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')
                ->andReturn(['access-token' => 'fake-token']);
        });

        $res = $this->postJson(route('web-service.callback',['code' => 'dummy-code']))->assertCreated();

        $this->assertDatabaseHas('web_services',[
            'user_id' => $this->user->id,
            'token' => json_encode(['access-token' => 'fake-token']),
            'name' => 'google-drive'
        ]);

        // $this->assertNotNull($this->user->services->first()->token);
    }

    /** @test */
    public function data_of_a_week_can_be_stored_on_google_drive()
    {
        $this->createTask();
        $this->createTask(['created_at' => now()->subDays(3)]);
        $this->createTask(['created_at' => now()->subDays(4)]);
        $this->createTask(['created_at' => now()->subDays(5)]);
        $this->createTask(['created_at' => now()->subDays(6)]);
        $this->createTask(['created_at' => now()->subDays(8)]);

        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setAccessToken');
            $mock->shouldReceive('getLogger->info');
            $mock->shouldReceive('shouldDefer');
            $mock->shouldReceive('execute');
        });
        $web_service = $this->createWebService();
        $this->postJson(route('web-service.store',$web_service))->assertCreated();
    }
}
