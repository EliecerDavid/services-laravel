<?php

namespace Tests\Feature\People;

use App\Person;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShowPersonTest extends TestCase
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
    public function mostrar_datos_de_persona_por_su_id()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $person = factory(Person::class)->create();

        $response = $this
                        ->withHeader('Authorization', 'Bearer ' . $token)
                        ->json('GET', '/api/people/' . $person->id);

        $response
                ->assertStatus(200)
                ->assertJson([
                   'data' => [
                       'id'         => $person->id,
                       'first_name' => $person->first_name,
                       'last_name'  => $person->last_name,
                   ],
                ]);
    }

    /**
     * @test
     */
    public function mostrar_error_404_cuando_la_persona_no_existe()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $response = $this
                        ->withHeader('Authorization', 'Bearer ' . $token)
                        ->json('GET', '/api/people/1');

        $response->assertStatus(404);
    }
}
