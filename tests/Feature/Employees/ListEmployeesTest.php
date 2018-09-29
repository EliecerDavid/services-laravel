<?php

namespace Tests\Feature\Employees;

use App\Employee;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ListEmployeesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function mostrar_error_de_autenticacion()
    {
        $response = $this->json('GET', '/api/employees');
        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function listado_de_empleados()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        factory(Employee::class, 3)->create();

        $response = $this
                        ->withHeader('Authorization', 'Bearer ' . $token)
                        ->json('GET', '/api/employees');

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
}
