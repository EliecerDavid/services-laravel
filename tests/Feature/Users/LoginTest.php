<?php

namespace Tests\Feature\Users;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function login_correcto()
    {
        $user = factory(User::class)->create();

        $response = $this->json('POST', '/api/login', [
            'username' => $user->username,
            'password' => 'secret',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'token_type',
                'expires_in',
            ]);
    }

    /**
     * @test
     */
    public function login_incorrecto()
    {
        $user = factory(User::class)->create();

        $response = $this->json('POST', '/api/login', [
            'username' => $user->username,
            'password' => 'bad password',
        ]);

        $response
            ->assertJsonValidationErrors('username');
    }

    /**
     * @test
     */
    public function validar_campos_vacios()
    {
        $user = factory(User::class)->create();

        $response = $this->json('POST', '/api/login', [
            'username' => '',
            'password' => '',
        ]);

        $response
            ->assertJsonValidationErrors('username', 'password');
    }
}
