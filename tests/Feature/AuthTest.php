<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_login_exist(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_login_failed(): void
    {
        $response = $this->post('/login', ["email" => "", "password" => ""]);

        $response->assertStatus(302);
    }
}
