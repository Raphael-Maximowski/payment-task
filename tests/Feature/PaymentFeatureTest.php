<?php

namespace Tests\Feature;

use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\FraudeDetectionService;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Mockery;

class PaymentFeatureTest extends TestCase
{
    public function teste_transaction_fraudulent(): void
    {

        // 1. Mock do serviço de fraude para sempre retornar true (fraudulento)
        $fraudeMock = Mockery::mock(FraudeDetectionService::class);
        $fraudeMock->shouldReceive('isFraudulent')->andReturn(true);

        // 2. Mock do repositório de pagamento para garantir que o método create não seja chamado
        $repMock = Mockery::mock(PaymentRepositoryInterface::class);
        $repMock->shouldNotReceive('create');

        // 3. Injeta minhas instâncias mockadas no container de serviços
        $this->app->instance(FraudeDetectionService::class, $fraudeMock);
        $this->app->instance(PaymentRepositoryInterface::class, $repMock);

        $payload = [
            'amount' => 890.00,
            'card_number' => '1234567890123456',
            'card_holder' => 'João Silva'
        ];

        $response = $this->postJson('/api/payment', $payload);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'rejeitado',
                'message' => 'Transação rejeitada por motivos de segurança',
                'payment_id' => null
            ]);
    }
}