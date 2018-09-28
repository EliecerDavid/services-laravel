<?php

namespace Tests\Feature\People;

use App\Person;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeletePersonTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function mostrar_error_de_autenticacion()
    {
        $response = $this->json('DELETE', '/api/people/1');
        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function eliminar_una_persona_por_su_id()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $person = factory(Person::class)->create();

        $response = $this
                        ->withHeader('Authorization', 'Bearer ' . $token)
                        ->json('DELETE', '/api/people/' . $person->id);

        $response
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function verificar_si_la_persona_eliminada_ya_no_existe_en_la_base_de_datos()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $person = factory(Person::class)->create();

        $this
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->json('DELETE', '/api/people/' . $person->id);

        $this->assertDatabaseMissing('people', [
            'id' => $person->id,
        ]);
    }

    /**
     * @test
     */
    public function mostrar_error_404_para_una_persona_que_no_existe()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $response = $this
                        ->withHeader('Authorization', 'Bearer ' . $token)
                        ->json('DELETE', '/api/people/1');

        $response->assertStatus(404);
    }
}
