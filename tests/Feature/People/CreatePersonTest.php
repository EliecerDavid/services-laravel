<?php

namespace Tests\Feature\People;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreatePersonTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function mostrar_error_de_autenticacion()
    {
        $response = $this->json('GET', '/api/people');
        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function crear_persona()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $response = $this
                        ->withHeader('Authorization', 'Bearer ' . $token)
                        ->json('POST', '/api/people', [
                            'first_name' => 'Eliecer David',
                            'last_name'  => 'Pari Alhuay',
                        ]);

        $response
                ->assertStatus(201)
                ->assertJsonStructure([
                    'data' => ['id', 'first_name', 'last_name'],
                ])
                ->assertJson([
                    'data' => [
                        'first_name' => 'Eliecer David',
                        'last_name'  => 'Pari Alhuay',
                    ],
                ]);
    }

    /**
     * @test
     */
    public function verificar_insercion_de_persona_en_la_base_de_datos()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $this
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->json('POST', '/api/people', [
                'first_name' => 'Eliecer David',
                'last_name'  => 'Pari Alhuay',
            ]);

        $this->assertDatabaseHas('people', [
            'first_name' => 'Eliecer David',
            'last_name'  => 'Pari Alhuay',
        ]);
    }

    /**
     * @test
     */
    public function mostrar_errores_de_validacion_con_los_campos_vacios()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $response = $this
                        ->withHeader('Authorization', 'Bearer ' . $token)
                        ->json('POST', '/api/people', [
                            'first_name' => '',
                            'last_name'  => '',
                        ]);

        $response
                ->assertStatus(422)
                ->assertJsonValidationErrors('first_name', 'last_name');
    }
}
