<?php

namespace Tests\Feature\Users;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function logout_correcto()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $response = $this
                        ->withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->json('DELETE', '/api/logout');

        $response
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function logout_incorrecto()
    {
        $response = $this
                        ->json('DELETE', '/api/logout');

        $response
            ->assertStatus(401);
    }
}
