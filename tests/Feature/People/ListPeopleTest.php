<?php

namespace Tests\Feature\People;

use App\Person;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ListPeopleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function listado_de_personas()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        factory(Person::class, 3)->create();

        $response = $this
                        ->withHeader('Authorization', 'Bearer ' . $token)
                        ->json('GET', '/api/people');

        $response
                ->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'first_name',
                            'last_name',
                        ],
                    ],
                ])
                ->assertJsonCount(3, 'data');
    }

    /**
     * @test
     */
    public function error_401_por_usuario_no_autenticado()
    {
        $response = $this->json('GET', '/api/people');
        $response->assertStatus(401);
    }
}
