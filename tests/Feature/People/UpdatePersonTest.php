<?php

namespace Tests\Feature\People;

use App\Person;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdatePersonTest extends TestCase
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
    public function actualizar_datos_de_persona()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $person = factory(Person::class)->create();

        $response = $this
                        ->withHeader('Authorization', 'Bearer ' . $token)
                        ->json('PUT', '/api/people/' . $person->id, [
                            'first_name' => 'Juanito',
                            'last_name'  => 'Pérez',
                        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id'         => $person->id,
                    'first_name' => 'Juanito',
                    'last_name'  => 'Pérez',
                ],
            ]);
    }

    /**
     * @test
     */
    public function verificar_los_cambios_realizados_en_persona_en_la_base_de_datos()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $person = factory(Person::class)->create();

        $this
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->json('PUT', '/api/people/' . $person->id, [
                'first_name' => 'Juanito',
                'last_name'  => 'Pérez',
            ]);

        $this->assertDatabaseHas('people', [
            'id'         => $person->id,
            'first_name' => 'Juanito',
            'last_name'  => 'Pérez',
        ]);
    }

    /**
     * @test
     */
    public function mostrar_errores_de_validacion()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $person = factory(Person::class)->create();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->json('PUT', '/api/people/' . $person->id, [
                'first_name' => '',
                'last_name'  => '',
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('first_name', 'last_name');
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
            ->json('PUT', '/api/people/1');

        $response->assertStatus(404);
    }
}
