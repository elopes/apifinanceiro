<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Transfere;
use App\Models\User;
use Tests\TestCase;

class TransfereTest extends TestCase
{

    use RefreshDatabase;

    public function testIndex()
    {
        // Cria algumas transferências de teste
        Transfere::factory()->count(3)->create();

        // Chama o método index do TransfereController
        $response = $this->get('/api/v1/transferencias');

        // Verifica se a resposta tem o código de status 200
        $response->assertStatus(200);

        // Verifica se a resposta contém 3 transferências
        $response->assertJsonCount(3);
    }

    public function testStore()
    {
        // Cria usuários de origem e destino de teste
        $origem = User::factory()->create();
        $destino = User::factory()->create();

        // Define os dados da solicitação
        $data = [
            'origem' => $origem->id,
            'destino' => $destino->id,
            'quantia' => 100,
        ];

        // Chama o método store do TransfereController
        $response = $this->post('/api/v1/transfere', $data);

        // Verifica se a resposta tem o código de status 200
        $response->assertStatus(200);

        // Verifica se uma transferência foi criada com os dados corretos
        $this->assertDatabaseHas('transferencia', [
            'origem' => $origem->id,
            'destino' => $destino->id,
            'quantia' => 100,
        ]);
    }
}
