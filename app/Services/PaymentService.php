<?php
namespace App\Services;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\FraudeServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentService
{
    protected $paymentRepository; // Repositório de pagamento
    protected $fraudeService; // Serviço de verificação de fraude (simulado)

    public function __construct(PaymentRepositoryInterface $paymentRepository, FraudeServiceInterface $fraudeService)
    {
        $this->paymentRepository = $paymentRepository;
        $this->fraudeService = $fraudeService;
    }

    public function processTransaction(array $data): array
    {
        // Http::post('https://api.pagamentos.falsa/process', [
        //     'amount' => $data['amount'],
        //     'card' => $data['card_number'],
        // ]);

        $isFraudulent = $this->fraudeService->isFraudulent($data);

        $status = $isFraudulent ? 'rejeitado': 'aprovado';

        $paymentData = [
            'amount' => $data['amount'],
            'card_number' => Str::substr($data['card_number'], -4), // Armazena apenas os últimos 4 dígitos do cartão
            'card_holder' => $data['card_holder'],
            'status' => $status,
        ];

        if(!$isFraudulent) {
            $payment = $this->paymentRepository->create($paymentData);
        }else{
            $payment = null;
        }

        return [
            'message' => $isFraudulent ? 'Transação rejeitada por motivos de segurança' : 'Transação processada com sucesso.',
            'status' => $status,
            'payment_id' => $payment?->id
        ];
        }
    }
