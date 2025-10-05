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

        $fraudeMock = Mockery::mock(FraudeDetectionService::class);
        $fraudeMock->shouldReceive('isFraudulent')->andReturn(true);

        $repMock = Mockery::mock(PaymentRepositoryInterface::class);
        $repMock->shouldNotReceive('create');

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